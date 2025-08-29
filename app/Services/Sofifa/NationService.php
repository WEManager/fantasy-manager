<?php

declare(strict_types=1);

namespace App\Services\Sofifa;

use App\Models\Nation;
use App\Services\Sofifa\Dto\NationPayload;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

final class NationService
{
    /**
     * Atualiza nações a partir de uma API externa
     */
    public function updateFromApi(string $url = 'https://sofifa.com/api/nation?hl=pt-BR&gender=0&roster=250044'): bool
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json, text/plain, */*',
                    'Accept-Language' => 'pt-BR,pt;q=0.9,en;q=0.8',
                ])
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();

                foreach ($data as $continent => $nations) {
                    foreach ($nations as $nation) {
                        $this->createOrUpdateNation($nation);
                    }
                }

                Log::info('Nations updated successfully from API');

                return true;
            }
            Log::error('Failed to fetch nations data from Sofifa API');

            return false;

        } catch (Throwable $e) {
            Log::error('Error updating nations from API: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Atualiza nações a partir de um arquivo JSON local
     */
    public function updateFromFile(string $filePath, int $chunkSize = 500): bool
    {
        try {
            $raw = $this->readLocalFile($filePath);
            $json = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

            // valida shape mínimo
            if (! is_array($json) || ! Arr::exists($json, 'data') || ! is_array($json['data'])) {
                throw new RuntimeException('Formato inválido: esperado objeto com chave "data" (array).');
            }

            $rows = $this->normalizeRows($json['data']);
            $total = count($rows);

            if ($total === 0) {
                Log::warning('Nenhum registro para processar.');

                return true;
            }

            foreach (array_chunk($rows, $chunkSize) as $i => $part) {
                Nation::upsert($part, ['id'], ['name', 'iso_code']);
                Log::info('Chunk '.($i + 1).' upserted ('.count($part).')');
            }

            Log::info('Nations updated from file', ['file' => $filePath, 'total' => $total]);

            return true;
        } catch (Throwable $e) {
            Log::error('Failed to update nations from file', ['file' => $filePath, 'error' => $e->getMessage()]);

            return false;
        }
    }

    private function readLocalFile(string $path): string
    {
        if (! Storage::disk('local')->exists($path)) {
            throw new RuntimeException("Arquivo não encontrado: storage/app/{$path}");
        }

        return (string) Storage::disk('local')->get($path);
    }

    /**
     * @param  array{id:int, value:string, flag:string}  $nationData
     */
    private function createOrUpdateNation(array $nationData): void
    {
        try {
            $isoCode = $this->extractIsoCodeFromFlag($nationData['flag']);

            Nation::updateOrCreate(
                ['id' => $nationData['id']],
                [
                    'name' => $nationData['value'],
                    'iso_code' => $isoCode,
                ]
            );
        } catch (Throwable $e) {
            Log::warning('Failed to process nation: '.json_encode($nationData).' - '.$e->getMessage());
        }
    }

    private function extractIsoCodeFromFlag(string $flagUrl): string
    {
        // Extract ISO code from URL like "https://cdn.sofifa.net/flags/ar.png"
        $pathInfo = pathinfo($flagUrl);

        return mb_strtolower($pathInfo['filename']);
    }

    /**
     * @param  array<int, array<string,mixed>>  $continents
     * @return array<int, array{id:int, name:string, iso_code:string}>
     */
    private function normalizeRows(array $continents): array
    {
        $rows = [];

        foreach ($continents as $continent) {
            $childs = Arr::get($continent, 'childs', []);
            if (! is_array($childs)) {
                continue;
            }

            foreach ($childs as $nation) {
                // [ALTERADO] valida e instancia o DTO antes de mapear
                if (! isset($nation['id'], $nation['value'], $nation['flag'])) {
                    continue; // [ALTERADO]
                }

                $payload = new NationPayload( // [ALTERADO]
                    (int) $nation['id'],
                    (string) $nation['value'],
                    (string) $nation['flag'],
                );

                $row = $this->mapNation($payload); // [ALTERADO]
                if ($row) {
                    $rows[] = $row;
                }
            }
        }

        return $rows;
    }

    /**
     * @return array{id:int, name:string, iso_code:string}|null
     */
    private function mapNation(NationPayload $nation): ?array
    {
        $id = $nation->id;
        $name = $nation->value;
        $flag = $nation->flag;

        $src = $this->extractImgSrc($flag);
        if (! $src) {
            return null;
        }

        return [
            'id' => $id,
            'name' => $name,
            'iso_code' => $this->isoFromFlagUrl($src),
        ];
    }

    private function extractImgSrc(string $imgHtml): ?string
    {
        if (preg_match('/\bsrc="([^"]+)"/i', $imgHtml, $m)) {
            return $m[1];
        }

        return null;
    }

    private function isoFromFlagUrl(string $flagUrl): string
    {
        // .../flags/ar.png -> ar
        return mb_strtolower((string) pathinfo($flagUrl, PATHINFO_FILENAME));
    }
}
