<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $msw_spot_id
 * @property int $user_id
 * @property string $name
 * @property bool $public
 */
class Spot extends Model
{
    protected $fillable = [
        'id',
        'msw_spot_id',
        'user_id',
        'name',
        'public',
    ];
}
