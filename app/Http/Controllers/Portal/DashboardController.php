<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

//use Illuminate\Http\Request;

class DashboardController extends Controller
{

    /*public function __construct()
    {
      $this->middleware('auth')->only(['index']);
    }*/

    public function index() 
    {

        /*$user = auth()->user();
        $timelines = $user->timelines;
        $likes = $user->likes;*/

        return view('layouts.portal.pages.dashboard');
        
    }

}