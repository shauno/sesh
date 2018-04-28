<?php

namespace App\Http\Controllers;

use App\Spot;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Sesh\Spots\Create;
use Sesh\Spots\SpotRepository;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Guard $user, SpotRepository $spotRepository)
    {
        return $spotRepository->findForUser($user->id());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Create $createSpot
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Create $createSpot)
    {
        try {
            return response($createSpot($request->only([
                'msw_spot_id',
                'name',
                'public',
            ])), 201);
        } catch (ValidationException $exception) {
            return response($exception->errors(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function show(Spot $spot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spot $spot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spot $spot)
    {
        //
    }
}
