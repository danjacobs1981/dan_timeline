@extends('layouts.portal.master')

@section('content')

    <h1>timelines</h1>

    <a href="{{ route('timelines.create') }}">create new timeline</a>

    @include('layouts.portal.snippets.list-timelines')
    
@endsection