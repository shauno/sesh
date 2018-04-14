<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $msw_surf_area_id
 * @property string $name
 * @property float $latitude
 * @property float $longitude
 * @property string $timezone
 * @property bool $is_big_wave
 */
class MswSpot extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'msw_surf_area_id',
        'name',
        'latitude',
        'longitude',
        'timezone',
        'is_big_wave',
    ];

}
