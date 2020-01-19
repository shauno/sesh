<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Sesh\Msw\MswClient;

class MswImportContinents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'msw:import-continents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new continents supported by Magic Seaweed and import them';

    /**
     * @var MswClient
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @param MswClient $client
     */
    public function __construct(MswClient $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $continents = $this->client->importContinents();

        $new = $updated = $unchanged = 0;

        foreach ($continents as $continent) {
            if ($continent->getChanges()) {
                $updated++;
            } elseif ($continent->wasRecentlyCreated) {
                $new++;
            } else {
                $unchanged++;
            }
        }

        $this->info($new.' continents inserted');
        $this->info($updated.' continents updated');
        $this->info($unchanged.' continents unchanged');

        if ($continents->isEmpty()) {
            $this->error('No records returned, that\'s probably due to an error. You should probably look into that');
        }
    }
}
