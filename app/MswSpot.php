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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mswForecasts()
    {
        return $this->hasMany(MswForecast::class, 'msw_spot_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mswSurfArea()
    {
        return $this->hasOne(MswSurfArea::class, 'id', 'msw_surf_area_id');
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
