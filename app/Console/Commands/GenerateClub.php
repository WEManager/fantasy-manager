<?php

namespace App\Console\Commands;

use App\Club;
use Illuminate\Console\Command;
use App\Generators\Club as ClubGenerator;

class GenerateClub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'club:generate {locale=sv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new club';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $clubGenerator = new ClubGenerator();
        $name = $clubGenerator->name($this->argument('locale'));

        Club::create(['name' => $name, 'locale' => $this->argument('locale')]);
    }
}
