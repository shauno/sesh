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
     * Display a listing of the resource.
     *
     * @param SurfRepository $surfRepository
     * @param Guard $user
     * @return \Illuminate\Http\Response
     */
    public function index(SurfRepository $surfRepository, Guard $user)
    {
        return $surfRepository->findAllForUser($user, 5);
    }

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
            ])), 201);
        } catch (ValidationException $exception) {
            return response($exception->errors(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Surf  $surf
     * @return \Illuminate\Http\Response
     */
    public function show(Surf $surf)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Surf  $surf
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Surf $surf)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Surf  $surf
     * @return \Illuminate\Http\Response
     */
    public function destroy(Surf $surf)
    {
        //
    }
}
