<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Sofifa\NationService;
use Illuminate\Console\Command;

final class UpdateNationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nations:update
        {--file=sofifa/nation.json : Caminho relativo no disco local (storage/app)}
        {--chunk=500 : Tamanho do chunk para upsert em massa}
        {--api : Usar API externa ao invés de arquivo local}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza nations a partir de um arquivo JSON local (dump do SoFIFA) ou API externa';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $nationService = new NationService();

        if ($this->option('api')) {
            $this->info('Atualizando nações via API externa...');

            if ($nationService->updateFromApi()) {
                $this->info('Nations updated successfully from API');

                return self::SUCCESS;
            }
            $this->error('Failed to update nations from API');

            return self::FAILURE;

        }

        $file = (string) $this->option('file');
        $chunk = (int) $this->option('chunk');
        $this->info("Lendo arquivo: storage/app/{$file}");

        if ($nationService->updateFromFile($file, $chunk)) {
            $this->info('Nations updated successfully from file');

            return self::SUCCESS;
        }
        $this->error('Failed to update nations from file');

        return self::FAILURE;

    }
}
