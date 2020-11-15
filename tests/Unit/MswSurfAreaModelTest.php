<?php

namespace Tests\Unit;

use App\MswSurfArea;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MswSurfAreaModelTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testMswSurfAreaGetters()
    {
        // This is a hasMany relation so relying on predictable seed data for this test
        $mswSurfArea = MswSurfArea::where('name', 'Cape Peninsula')->first();

        $this->assertEquals(224, $mswSurfArea->getId());
        $this->assertEquals(-32.31360000, $mswSurfArea->getLatitude());
        $this->assertEquals(18.33070000, $mswSurfArea->getLongitude());
    }
}
