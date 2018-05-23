<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int surf_id
 * @property int user_id
 * @property string path
 */
class Photo extends Model
{
    protected $fillable = [
        'surf_id',
        'user_id',
        'path',
    ];

    public function surf()
    {
        return $this->hasOne(Surf::class, 'surf_id', 'id');
    }
}
