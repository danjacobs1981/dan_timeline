<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * Display register page.
     * 
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

        return view('layouts.web.pages.register');

    }

    public function showModal()
    {

        $modal_title = 'Register';
        $route = 'snippets.modal.login-register';
        $routeParams = array('show'=>'register');
        return view('layouts.modal.master', compact('route', 'routeParams', 'modal_title'));

    }

    /**
     * Handle account registration request
     * 
     * @param RegisterRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request) 
    {

        $user = User::create($request->validated());

        event(new Registered($user));

        auth()->login($user);

        return redirect('/')->with('action', "Account successfully registered");
        
    }

}