<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int msw_continent_id
 * @property string name
 * @property string latitude_nw
 * @property string longitude_nw
 * @property string latitude_se
 * @property string longitude_se
 */
class MswRegion extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'msw_continent_id',
        'name',
        'latitude_nw',
        'longitude_nw',
        'latitude_se',
        'longitude_se',
    ];

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
