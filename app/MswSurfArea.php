<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $msw_country_id
 * @property string $name
 * @property string $timezone
 * @property float $latitude
 * @property float $longitude
 *
 */
class MswSurfArea extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'msw_country_id',
        'name',
        'timezone',
        'latitude',
        'longitude',
    ];

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
