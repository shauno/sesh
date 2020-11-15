<?php

namespace Tests\Unit;

use App\MswCountry;
use App\MswRegion;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MswRegionModelTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testMswRegionGetters()
    {
        // This is a hasMany relation so relying on predictable seed data for this test
        $mswRegion = MswRegion::where('name', 'South Africa')->first();
        $this->assertEquals(3, $mswRegion->getId());
    }
}
