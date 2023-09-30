<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class ProfileController extends Controller
{
    public function show($username) 
    {

        $user = User::where('username', $username)->first();

        if($user) {

            $timelines = $user->timelines->where('privacy', 3);

            return view('layouts.web.pages.profile', ['username' => $username, 'timelines' => $timelines]);

        } else {

            // error "private timeline" page
            return view('errors.profile');

        }



    }
}
