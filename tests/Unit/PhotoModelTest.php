<?php

namespace Tests\Unit;

use App\Photo;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PhotoModelTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testPhotoRelations()
    {
        // This is a hasMany relation so relying on predictable seed data for this test
        $photo = Photo::find(1);

        $this->assertEquals(1, $photo->surf->id);
    }
}
