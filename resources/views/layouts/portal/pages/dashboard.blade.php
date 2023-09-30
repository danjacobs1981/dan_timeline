@extends('layouts.portal.master')

@section('content')

    <h1>dash</h1>
    the portal dashboard - only auth can see
    <a href="{{ route('logout.perform') }}">logout</a>

    <hr/>

    @include('layouts.portal.snippets.list-timelines')

    <a href="{{ route('timelines.index') }}">see all your timelines</a>
    
@endsection