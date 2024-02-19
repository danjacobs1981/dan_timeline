@extends('layouts.web.master')

@section('content')
    
    @auth
    <h1>Homepage / Logged in</h1>
    <p>(you're logged in - only authenticated users can access this section)</p>
    <p><a href="{{ route('logout.perform') }}">Log out</a> | <a href="{{ route('dashboard.show') }}">Go to your Dashboard</a></p>
    @endauth

    @guest
    <h1>Homepage</h1>
    <p>You're viewing the home page. You're not logged in - please login to view the restricted data (can't see this if you're logged in)</p>
    <p><a data-modal data-modal-scroll data-modal-class="modal-login" data-modal-size="modal-lg" data-modal-showclose="false" href="{{ route('login.showModal') }}">Login (modal)</a> | <a data-modal data-modal-scroll data-modal-class="modal-register" data-modal-size="modal-lg" data-modal-showclose="false" href="{{ route('register.showModal') }}">Register (modal)</a></p>
    @endguest

    <div>
        <br>
        <hr>
        <br>
        (everyone can see this)
        <h3>Public Timelines List:</h3>
        @include('layouts.web.snippets.list-timelines')
    </div>

@endsection