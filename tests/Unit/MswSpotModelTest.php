<?php

namespace Tests\Unit;

use App\MswSpot;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MswSpotModelTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testMswSpotRelationsAndGetters()
    {
        // This is a hasMany relation so relying on predictable seed data for this test
        $mswSpot = MswSpot::where('name', 'Big Bay')->first();

        $this->assertEquals(2, $mswSpot->mswForecasts->count());
        $this->assertEquals('Cape Peninsula', $mswSpot->mswSurfArea->name);

        $this->assertEquals(4625, $mswSpot->getId());
    }
}
