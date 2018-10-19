# Sesh.co.za

[![Build Status](https://travis-ci.org/shauno/sesh.svg?branch=master)](https://travis-ci.org/shauno/sesh)

The source code for the rewritten sesh.co.za website.

### Install via docker

- Run composer install \
  `$ docker run --rm --interactive --tty --volume $PWD:/app composer install`
- Bring up the containers\
  `$ docker-compose up --build`
- Generate encryption keys for API tokens \
  `docker-compose exec app php artisan passport:keys`
- If you're using PHPStorm, you can run the following command to generate IDE
helper files: \
  `$ docker-compose exec app php artisan ide-helper:generate` 
- Create the DB \
  `$ docker-compose exec app php artisan migrate`
- Seed the DB \
  `$ docker-compose exec app php artisan db:seed`
- Browse to: \
  http://127.0.0.1:8080

### Setup Environment Variables

You can set your environment vars however you choose. Copying the `.env.sample`
to `.env` and setting them is the simplest especially in dev.

### Import Regional Data

- `$ docker-compose exec app php artisan msw:import-continents`
- `$ docker-compose exec app php artisan msw:import-regions {continent}`
- `$ docker-compose exec app php artisan msw:import-countries {region}`
- `$ docker-compose exec app php artisan msw:import-surf-areas {country}`
- `$ docker-compose exec app php artisan msw:import-spots {surf area}`
- `$ docker-compose exec app php artisan msw:import-forecast {spot id}`

### Setup auto importing

Instead of importing a single spot at a time, you can run the following command 
on a schedule to batch the importing of all spots automatically

- `$ docker-compose exec app php artisan msw:auto-import-forecast`