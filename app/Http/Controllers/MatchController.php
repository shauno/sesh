<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Sesh\Spots\SpotRepository;
use Sesh\Surfs\SurfRepository;

class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Guard $user, SpotRepository $spotRepository, SurfRepository $surfRepository)
    {
        return $surfRepository->findMatches(
            $spotRepository->findForUser($user->id()),
            new \DateTime($request->get('date'))
        );
    }
}
