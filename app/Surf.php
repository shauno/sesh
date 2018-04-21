<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

/**
 * @property int spot_id
 * @property int msw_forecast_id
 * @property int user_id
 * @property string date_start
 * @property string date_end
 * @property int swell_size
 * @property int wind_speed
 * @property string wind_direction
 */
class Surf extends Model
{
    protected $table = 'surfs';

    protected $fillable = [
        'spot_id',
        'user_id',
        'date_start',
        'date_end',
        'swell_size',
        'wind_speed',
        'wind_direction',
    ];

    public function spot()
    {
        return $this->hasOne(Spot::class, 'id', 'spot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mswForecast()
    {
        return $this->hasOne(MswForecast::class, 'id', 'msw_forecast_id');
    }

    /**
     * @param $value
     */
    public function setDateStartAttribute($value)
    {
        $this->attributes['date_start'] = (new \DateTime($value))->getTimestamp();
    }

    /**
     * @param $value
     */
    public function setDateEndAttribute($value)
    {
        $this->attributes['date_end'] = (new \DateTime($value))->getTimestamp();
    }

    /**
     * @param array $options
     * @return bool
     * @throws ValidationException
     */
    public function save(array $options = [])
    {
        $userId = $this->user_id;

        $validator = \Validator::make([
            'spot_id' => $this->spot_id,
            'msw_forecast_id' => $this->msw_forecast_id,
            'user_id' => $this->user_id,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'swell_size' => $this->swell_size,
            'wind_speed' => $this->wind_speed,
            'wind_direction' => $this->wind_direction,
        ], [
            'spot_id' => [
                'bail',
                'exists:spots,id',
                function($attribute, $value, $fail) use($userId) {
                    $spot = Spot::find($value); //TODO, do we need a repo?
                    if(!$spot || (!$spot->public && $spot->user_id != $userId)) { //if it's not a valid spot, or not *your* private spot
                        return $fail('The selected spot id is invalid.');
                    }
                },
            ],
            'msw_forecast_id' => 'exists:msw_forecasts,id',
            'user_id' => 'exists:users,id',
            'date_start' => 'date_format:U',
            'date_end' => 'date_format:U',
            'swell_size' => ['integer', 'min:0'], //TODO, what range is reasonable
            'wind_speed' => ['integer', 'min:0'],
            'wind_direction' => 'in:onshore,cross-onshore,cross-offshore,offshore',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return parent::save($options);
    }
}
