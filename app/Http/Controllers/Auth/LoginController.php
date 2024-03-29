<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display login page.
     * 
     * @return Renderable
     */
    public function show()
    {

        return view('layouts.web.pages.login');

    }


    public function showModal(Request $request)
    {

        $modal_title = 'Log In';
        $incentive = $request->incentive;
        $route = 'layouts.global.snippets.modal.login-register';
        $routeParams = array('modal' => true, 'show' => 'login');
        return view('layouts.modal.master', compact('route', 'routeParams', 'modal_title', 'incentive'));

    }

    /**
     * Handle account login request
     * 
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {

        $credentials = $request->getCredentials();

        if(!Auth::validate($credentials)):
            return redirect()->to('login')->withErrors(trans('auth.failed'));
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        Auth::login($user);

        return $this->authenticated($request, $user);

    }

    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user) 
    {
        //return redirect()->intended();
        return redirect($request['current_page'])->with('action', 'You have been logged in');
    }
}