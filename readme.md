# Sesh.co.za

The source code for the rewritten sesh.co.za website.

### Install

- `$ composer install`

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

- `$ php artisan import-continents`
- `$ php artisan import-regions {continent}`
- `$ php artisan import-countries {region}`
- `$ php artisan import-surf-areas {country}`
- `$ php artisan import-spots {surf area}`