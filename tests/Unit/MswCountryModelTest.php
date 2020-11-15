<?php

namespace Tests\Unit;

use App\MswCountry;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MswCountryModelTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testMswCountryModelGetters()
    {
        // This is a hasMany relation so relying on predictable seed data for this test
        $mswCountry = MswCountry::where('name', 'South Africa')->first();
        $this->assertEquals(7, $mswCountry->getId());
    }
}
