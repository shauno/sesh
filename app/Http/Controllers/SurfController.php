<?php

namespace App\Http\Controllers;

use App\Surf;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Sesh\Surfs\Create;
use Sesh\Surfs\SurfRepository;

class SurfController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Create $createSurf
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Create $createSurf)
    {
        try {
            return response($createSurf($request->only([
                'spot_id',
                'user_id',
                'date_start',
                'date_end',
                'swell_size',
                'wind_speed',
                'wind_direction',
                'photo'
            ])), 201);
        } catch (ValidationException $exception) {
            return response($exception->errors(), 400);
        }
    }
}
