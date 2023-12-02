@extends('layouts.portal.master')

@section('content')

    <h1>Timelines</h1>

    @include('layouts.portal.snippets.list-timelines')

    <br>
    <br>

    <a href="{{ route('timelines.create') }}">Create new timeline</a>
    
@endsection