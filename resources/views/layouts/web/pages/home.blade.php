@extends('layouts.web.master')

@section('content')
    
    @auth
    <h1>logged in</h1>
    <p class="lead">you're logged in - Only authenticated users can access this section.</p>
    <a href="{{ route('logout.perform') }}">log out</a> | 
    <a href="{{ route('dashboard.show') }}">dashboard</a>
    @endauth

    @guest
    <h1>Homepage</h1>
    <p class="lead">You're viewing the home page. you're not logged in - Please login to view the restricted data - can't see this if you're logged in</p>
    <a data-modal data-modal-class="bob" data-modal-size="modal-lg" data-modal-showclose="false" href="{{ route('login.showModal') }}">Login (modal)</a>
    <a data-modal href="{{ route('register.showModal') }}">Register (modal)</a>
    <hr/>
    @endguest

    <div>
        everyone can see this:
        <h3>timelines list:</h3>
        @include('layouts.web.snippets.list-timelines')
    </div>

@endsection