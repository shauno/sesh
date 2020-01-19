<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Sesh\Msw\MswClient;

class MswImportSpots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'msw:import-spots {surf-area : Surf area name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new spots supported by Magic Seaweed and import them';

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
        $spots = $this->client->importSpots($this->argument('surf-area'));

        $new = $updated = $unchanged = 0;

        foreach ($spots as $area) {
            if ($area->getChanges()) {
                $updated++;
            } elseif ($area->wasRecentlyCreated) {
                $new++;
            } else {
                $unchanged++;
            }
        }

        $this->info($new.' spots inserted');
        $this->info($updated.' spots updated');
        $this->info($unchanged.' spots unchanged');

        if ($spots->isEmpty()) {
            $this->error('No records returned, that\'s probably due to an error. You should probably look into that');
        }
    }
}
