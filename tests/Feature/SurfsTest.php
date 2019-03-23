<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Laravel\Passport\Passport;
use Storage;
use Tests\TestCase;

class SurfsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testSurfCreationWithOwnPublicSpot()
    {
        Passport::actingAs(
            User::find(1),
            []
        );

        $response = $this->post('/api/v1/surf', [
            'spot_id' => 1,
            'date_start' => '2018-07-01T08:00:00.000Z',
            'date_end' => '2018-07-01T10:15:00.000Z',
            'swell_size' => 5,
            'wind_speed' => 2,
            'wind_direction' => 1,
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'spot_id',
                'date_start',
                'date_end',
                'swell_size',
                'wind_speed',
                'wind_direction',
            ])
            ->assertJson([
                'spot_id' => 1,
                'date_start' => 1530432000,
                'date_end' => 1530440100,
                'swell_size' => 5,
                'wind_speed' => 2,
                'wind_direction' => 1,
                'spot' => [
                    'id' => 1,
                    'msw_spot_id' => 4625,
                    'msw_spot' => []
                ],
                'photos' => [],
            ]);
    }

    public function testSurfCreationWithSomeoneElsesPublicSpot()
    {
        Passport::actingAs(
            User::find(2),
            []
        );

        $response = $this->post('/api/v1/surf', [
            'spot_id' => 1,
            'date_start' => '2018-07-01T08:00:00.000Z',
            'date_end' => '2018-07-01T10:15:00.000Z',
            'swell_size' => 5,
            'wind_speed' => 2,
            'wind_direction' => 1,
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'spot_id',
                'date_start',
                'date_end',
                'swell_size',
                'wind_speed',
                'wind_direction',
            ])
            ->assertJson([
                'spot_id' => 1,
                'date_start' => 1530432000,
                'date_end' => 1530440100,
                'swell_size' => 5,
                'wind_speed' => 2,
                'wind_direction' => 1,
            ]);
    }

    public function testSurfCreationWithOwnPrivateSpot()
    {
        Passport::actingAs(
            User::find(1),
            []
        );

        $response = $this->post('/api/v1/surf', [
            'spot_id' => 2,
            'date_start' => '2018-07-01T08:00:00.000Z',
            'date_end' => '2018-07-01T10:15:00.000Z',
            'swell_size' => 4,
            'wind_speed' => 2,
            'wind_direction' => 1,
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'spot_id',
                'date_start',
                'date_end',
                'swell_size',
                'wind_speed',
                'wind_direction',
            ])
            ->assertJson([
                'spot_id' => 2,
                'date_start' => 1530432000,
                'date_end' => 1530440100,
                'swell_size' => 4,
                'wind_speed' => 2,
                'wind_direction' => 1,
            ]);
    }

    public function testSurfCreationWithSomeoneElsesPrivateSpot()
    {
        Passport::actingAs(
            User::find(2),
            []
        );

        $response = $this->post('/api/v1/surf', [
            'spot_id' => 2,
            'date_start' => '2018-07-01T08:00:00.000Z',
            'date_end' => '2018-07-01T10:15:00.000Z',
            'swell_size' => 2,
            'wind_speed' => 5,
            'wind_direction' => 1,
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonStructure([
                'spot_id',
            ])
            ->assertJson([
                "spot_id" => ["The selected spot is invalid."],
            ]);
    }

    public function testBlankBodyValidation()
    {
        Passport::actingAs(
            User::find(1),
            []
        );

        $response = $this->post('/api/v1/surf');

        $response
            ->assertStatus(400)
            ->assertJsonStructure([
                "spot_id",
                "msw_forecast_id",
                "swell_size",
                "wind_speed",
                "wind_direction",
            ])
            ->assertJson([
                "spot_id" => ["The selected spot is invalid."],
                "msw_forecast_id" => ["The selected msw forecast id is invalid."],
                "swell_size" => ["The swell size must be an integer."],
                "wind_speed" => ["The wind speed must be an integer."],
                "wind_direction" => ["The selected wind direction is invalid."]
            ]);
    }

    public function testSurfCreationWithImageUpload()
    {
        Passport::actingAs(
            User::find(1),
            []
        );

        Storage::fake();

        $photo = UploadedFile::fake()->image('rad-surf.png');
        $response = $this->post('/api/v1/surf', [
            'spot_id' => 1,
            'date_start' => '2018-07-01T08:00:00.000Z',
            'date_end' => '2018-07-01T10:15:00.000Z',
            'swell_size' => 2,
            'wind_speed' => 5,
            'wind_direction' => 1,
            'photo' => $photo
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'spot_id',
                'date_start',
                'date_end',
                'swell_size',
                'wind_speed',
                'wind_direction',
                'spot' => [
                    'msw_spot'
                ],
                'photos',
            ])
            ->assertJson([
                'spot_id' => 1,
                'date_start' => 1530432000,
                'date_end' => 1530440100,
                'swell_size' => 2,
                'wind_speed' => 5,
                'wind_direction' => 1,
                'spot' => [
                    'id' => 1,
                    'msw_spot' => [
                        'id' => 4625,
                    ]
                ],
                'photos' => [
                    [
                        'id' => 3, //2 photos are created by the seed data
                        'user_id' => 1,
                        'path' => 'surfs/'.date('Y/m/d').'/'.$photo->hashName(),
                    ]
                ],
            ]);

        Storage::disk()->assertExists('surfs/'.date('Y/m/d').'/'.$photo->hashName());
    }
}
