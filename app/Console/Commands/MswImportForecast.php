<?php

namespace App\Console\Commands;

use App\MswSpot;
use Illuminate\Console\Command;
use Sesh\Msw\MswClient;

class MswImportForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'msw:import-forecast {spot-id : MSW Spot ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import forecast data for a Magic Seaweed spot';

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
        $forecast = $this->client->importForecast($this->argument('spot-id'));

        $new = $updated = $unchanged = 0;

        foreach ($forecast as $time) {
            if ($time->getChanges()) {
                $updated++;
            } else if($time->wasRecentlyCreated) {
                $new++;
            } else {
                $unchanged++;
            }
        }

        $this->info($new.' forecasts inserted');
        $this->info($updated.' forecasts updated');
        $this->info($unchanged.' forecasts unchanged');

        if ($forecast->isEmpty()) {
            $this->error('No records returned, that\'s probably due to an error. You should probably look into that');
        }
    }
}
