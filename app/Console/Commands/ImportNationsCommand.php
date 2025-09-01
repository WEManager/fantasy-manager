<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Sofifa\NationService;
use Illuminate\Console\Command;

final class ImportNationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:nations
        {--file=sofifa/nation.json : Caminho relativo no disco local (storage/app)}
        {--chunk=500 : Tamanho do chunk para upsert em massa}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa nations a partir de um arquivo JSON local (dump do SoFIFA)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $nationService = new NationService();

        $file = (string) $this->option('file');
        $chunk = (int) $this->option('chunk');
        
        $this->info("Importando nações do arquivo: storage/app/{$file}");

        if ($nationService->updateFromFile($file, $chunk)) {
            $this->info('Nations imported successfully from file');
            return self::SUCCESS;
        }
        
        $this->error('Failed to import nations from file');
        return self::FAILURE;
    }
}
