# Sesh.co.za

The source code for the rewritten sesh.co.za website.

### Install

- `$ composer install`
- `$ php artisan passport:keys`

### Setup PHPStorm IDE Helper

If you're using PHPStorm, you can run the following command to generate IDE
helper files:

`$ php artisan ide-helper:generate` 

### Setup the DB
`$ php artisan migrate`

### Setup Environment Variables

You can set your environment vars however you choose. Copying the `.env.sample`
to `.env` and setting them is the simplest esspecially in dev.

### Import Regional Data

- `$ php artisan msw:import-continents`
- `$ php artisan msw:import-regions {continent}`
- `$ php artisan msw:import-countries {region}`
- `$ php artisan msw:import-surf-areas {country}`
- `$ php artisan msw:import-spots {surf area}`
- `$ php artisan msw:import-forecast {spot id}`

### Setup auto importing

Instead of importing a spot at a time, you can run the following command on a
schedule to batch the importing of all spots automatically

- `$ php artisan msw:auto-import-forecast`