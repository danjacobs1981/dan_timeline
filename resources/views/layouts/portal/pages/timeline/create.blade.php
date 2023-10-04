@extends('layouts.portal.master')

@section('content')

    <h1>create timeline</h1>

    @include('layouts.global.snippets.messages')

    <form action="{{ route('timelines.store') }}" method="POST">

        @csrf

        <div>
            <label for="title">Title:</label>
            <input name="title" placeholder="">
        </div>

        <div>
            <label for="comments">Show Comments?</label>
            <input type="hidden" name="comments" value="0">
            <input type="checkbox" name="comments" value="1" checked>
        </div>

        <hr/>

        <button type="submit" class="btn">Create Timeline</button> (disable until title is added)

    </form>
    
@endsection