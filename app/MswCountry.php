<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $msw_region_id
 * @property string $name
 */
class MswCountry extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'msw_region_id',
        'name',
    ];

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
