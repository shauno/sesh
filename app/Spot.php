<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mswSpot()
    {
        return $this->hasOne(MswSpot::class, 'id', 'msw_spot_id');
    }

    public function save(array $options = [])
    {
        $validator = \Validator::make([
            'msw_spot_id' => $this->msw_spot_id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'public' => $this->public,
        ], [
            'msw_spot_id' => 'exists:msw_spots,id',
            'user_id' => 'exists:users,id',
            'name' => 'required|string',
            'public' => 'boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return parent::save();
    }
}
