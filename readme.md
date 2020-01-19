# Sesh.co.za

[![Build Status](https://travis-ci.org/shauno/sesh.svg?branch=master)](https://travis-ci.org/shauno/sesh)
![](https://github.com/shauno/sesh/workflows/PHPStan/badge.svg)
![](https://github.com/shauno/sesh/workflows/PHP%20CS/badge.svg)

The source code for the rewritten sesh.co.za website.

### Install via docker

- Run composer install \
  `$ docker run --rm --interactive --tty --volume $PWD:/app composer install`
- Build javascript dependencies
  `$ docker-compose run node npm install`
- Bring up the containers\
  `$ docker-compose up --build`
- Set application environment variables however you wish. The easiest option for
dev is to copy the `/env.sample` to `.env` and set the appropriate properties
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

The `node` container will run an `npm run watch` on the project and rebuild the
JS and CSS when changes are detected.

**@TODO:** Still need to create a proper deployment pipeline so the all the
dependencies are built for production instead of dev. For now you can run: \
- `docker-compose exec node npm run prod`

### Setup Environment Variables

You can set your environment vars however you choose. Copying the `.env.sample`
to `.env` and setting them is the simplest especially in dev.

### Run Test Suite
After following the setup steps above you should be able to run the test
and code quality suites:
- Tests: \
  `$ docker-compose exec app vendor/bin/phpunit`
- PHPStan Static Analysis: \
  `$ docker run -it --rm -v "$PWD":/app -w /app adamculp/php-code-quality:latest php /usr/local/lib/php-code-quality/vendor/bin/phpstan analyse app tests`
- PHP CS Code Style (PSR2): \
  `$ docker run -it --rm -v "$PWD":/app -w /app adamculp/php-code-quality:latest php /usr/local/lib/php-code-quality/vendor/bin/phpcs`

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