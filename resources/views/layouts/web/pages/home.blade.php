@extends('layouts.web.master')

@section('content')
<hr/>
    
        @auth
        <h1>Dashboard</h1>
        <p class="lead">Only authenticated users can access this section.</p>
        @endauth

        @guest
        <h1>Homepage</h1>
        <p class="lead">You're viewing the home page. Please login to view the restricted data.</p>
        <a data-modal data-modal_class="bob" data-modal_size="modal-lg" data-modal_close="false" href="{{ route('login.showModal') }}">Login (modal)</a>
        <a data-modal href="{{ route('register.showModal') }}">Register (modal)</a>
        @endguest

        <div>
        <i class="fa-solid fa-cart-shopping"></i>
        <i class="fa-solid fa-image"></i>
            <h3>timelines list:</h3>
            @include('layouts.web.snippets.timelines')
        </div>
    
<hr/>
@endsection