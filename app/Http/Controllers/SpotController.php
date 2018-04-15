<?php

namespace App\Http\Controllers;

use App\Spot;
use Illuminate\Http\Request;
use Sesh\Spots\Create;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //TODO create a validation object to pass to CreateSpot

        return $createSpot(
            $request->get('msw_spot_id'),
            $request->get('name'),
            (bool)$request->get('public', false)
        );
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
