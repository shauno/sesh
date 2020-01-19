<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Sesh\Msw\MswClient;

class MswImportSurfAreas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'msw:import-surf-areas {country : Country name }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new surf areas supported by Magic Seaweed and import them';

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
        $surfAreas = $this->client->importSurfAreas($this->argument('country'));

        $new = $updated = $unchanged = 0;

        foreach ($surfAreas as $area) {
            if ($area->getChanges()) {
                $updated++;
            } elseif ($area->wasRecentlyCreated) {
                $new++;
            } else {
                $unchanged++;
            }
        }

        $this->info($new.' surf areas inserted');
        $this->info($updated.' surf areas updated');
        $this->info($unchanged.' surf areas unchanged');

        if ($surfAreas->isEmpty()) {
            $this->error('No records returned, that\'s probably due to an error. You should probably look into that');
        }
    }
}
