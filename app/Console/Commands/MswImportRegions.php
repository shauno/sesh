<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Sesh\Msw\MswClient;

class MswImportRegions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'msw:import-regions {continent : Continent name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new regions supported by Magic Seaweed and import them';

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
        $continent = $this->argument('continent');

        $regions = $this->client->importRegions($continent);

        $new = $updated = $unchanged = 0;

        foreach ($regions as $region) {
            if ($region->getChanges()) {
                $updated++;
            } else if($region->wasRecentlyCreated) {
                $new++;
            } else {
                $unchanged++;
            }
        }

        $this->info($new.' regions inserted');
        $this->info($updated.' regions updated');
        $this->info($unchanged.' regions unchanged');

        if ($regions->isEmpty()) {
            $this->error('No records returned, that\'s probably due to an error. You should probably look into that');
        }
    }
}
