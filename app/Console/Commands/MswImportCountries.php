<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Sesh\Msw\MswClient;

class MswImportCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'msw:import-countries {region : Region name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new countries supported by Magic Seaweed and import them';

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
        $countries = $this->client->importCountries($this->argument('region'));

        $new = $updated = $unchanged = 0;

        foreach ($countries as $country) {
            if ($country->getChanges()) {
                $updated++;
            } elseif ($country->wasRecentlyCreated) {
                $new++;
            } else {
                $unchanged++;
            }
        }

        $this->info($new.' countries inserted');
        $this->info($updated.' countries updated');
        $this->info($unchanged.' countries unchanged');

        if ($countries->isEmpty()) {
            $this->error('No records returned, that\'s probably due to an error. You should probably look into that');
        }
    }
}
