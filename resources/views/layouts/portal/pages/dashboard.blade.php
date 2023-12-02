@extends('layouts.portal.master')

@section('content')

    <h1>Dashboard</h1>
    <p>(the portal dashboard - only auth can see)</p>
    <a href="{{ route('logout.perform') }}">Logout</a>

    <hr/>

    <h3>Timeline list</h3>
    @include('layouts.portal.snippets.list-timelines')
    <br>
    <br>
    <a href="{{ route('timelines.index') }}">See all your timelines</a>
    <br>
    <strong><a href="{{ route('timelines.index') }}">Create a timeline</a></strong>
    
@endsection