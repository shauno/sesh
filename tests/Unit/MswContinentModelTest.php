<?php

namespace Tests\Unit;

use App\MswContinent;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MswContinentModelTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testMswContinentModelRelationsAndGetters()
    {
        // This is a hasMany relation so relying on predictable seed data for this test
        $mswContinent = MswContinent::where('name', 'Africa')->first();
        $this->assertEquals('South Africa', $mswContinent->regions[0]->name);

        $this->assertEquals(2, $mswContinent->getId());
    }
}
