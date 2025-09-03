<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Nation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use RuntimeException;

final class NationSeeder extends Seeder
{
    public function run(): void
    {
        $nationsRaw = json_decode(file_get_contents(database_path('datas/nations.json')), true);

        // valid min shape
        if (
            ! is_array($nationsRaw) ||
            ! Arr::exists($nationsRaw, 'data') ||
            ! is_array($nationsRaw['data'])
        ) {
            throw new RuntimeException('Formato inválido: esperado objeto com chave "data" (array).');
        }

        $rows = $this->normalizeRows($nationsRaw['data']);

        foreach (array_chunk($rows, 500) as $i => $part) {
            Nation::upsert($part, ['id'], ['name', 'iso_code']);
        }
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
                if (! isset($nation['id'], $nation['value'], $nation['flag'])) {
                    continue;
                }

                $flag = $nation['flag'];
                $src = $this->extractImgSrc($flag);
                if (! $src) {
                    continue;
                }

                $row = [
                    'id' => (int) $nation['id'],
                    'name' => (string) $nation['value'],
                    'iso_code' => (string) $this->isoFromFlagUrl($src),
                ];

                $rows[] = $row;
            }
        }

        return $rows;
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
