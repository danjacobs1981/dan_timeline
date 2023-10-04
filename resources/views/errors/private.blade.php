@extends('layouts.web.master')

@section('content')
    
    <p>timeline is private</p>

    @auth
        @if(!auth()->user()->hasVerifiedEmail())
        <p class="lead">logged in but not verified your email yet!</p>
        @endif
    @endauth

    @guest
    <p class="lead">you ain't logged in - try logging in</p>
    @endguest

@endsection