@extends('layouts.portal.master')

@section('content')

    <h1>Create Timeline</h1>

    <br>

    @include('layouts.global.snippets.messages')

    <form action="{{ route('timelines.store') }}" method="POST">

        @csrf

        <div class="control control--textbox">
            <label class="control__label" for="title">Timeline Title</label>
            <input name="title" id="title">
            <p>The title should reflect your timeline in just a few words. This will also make up your timeline URL.</p>
        </div>

        <div class="control control--checkbox">    
            <span class="control__label">Comments</span>
            <label class="control__label" for="comments">Show comments
                <input type="hidden" name="comments" value="0">
                <input type="checkbox" name="comments" id="comments" value="1" checked>
                <div></div>
            </label>
            <label class="control__label" for="comments_event">Show comments per event
                <input type="hidden" name="comments_event" value="0">
                <input type="checkbox" name="comments_event" id="comments_event" value="1" checked>
                <div></div>
            </label>
        </div>

        <p>(more initial options will be here)</p>

        <hr/>

        <br>

        <button type="submit" class="btn">Create Timeline</button>

    </form>
    
@endsection