<?php

namespace App\Http\Controllers;

use App\MswCountry;
use Illuminate\Http\Request;
use Sesh\Msw\MswSpotRepository;

class MswSpotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param MswSpotRepository $mswSpotRepository
     * @return \Illuminate\Http\Response
     */
    public function index(MswSpotRepository $mswSpotRepository)
    {
        //TODO we're going to hard code this to South Africa for now, but can be tweaked to take query param
        $mswCountry = (new MswCountry())->where('name', 'South Africa')->first();

        return $mswSpotRepository->findByCountry($mswCountry);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
