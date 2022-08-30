<?php

namespace App\Console\Commands;

use App\Models\Nation;
use Illuminate\Console\Command;

class UploadNations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:nations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload .JSON file with nations.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $nations = json_decode(file_get_contents(storage_path() . "/nations.json"), true);
        
        foreach ($nations as $key => $nation) {
            Nation::create($nation);
        }
    }
}
