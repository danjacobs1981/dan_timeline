@extends('layouts.web.master')

@section('content')
    
    <h1>{{ $username }} - profile page</h1>

    <ul>
    @foreach($timelines as $timeline)
        <li>
            {{ $timeline->title }} 
            <ul>
                <li>
                    <a href="{{ route('timeline.show', ['timeline' => $timeline->id,'slug' => $timeline->slug ]) }}">View</a>
                </li>
            </ul> 
        </li>  
    @endforeach
    </ul>

@endsection