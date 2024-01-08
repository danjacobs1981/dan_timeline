@extends('layouts.portal.master')

@section('content')

    <h1>God</h1>
    <p><a href="{{ route('logout.perform') }}">Logout</a></p>

    <br>
    <h2>Currently there are {{ $users->where('premium', 1)->count() }} premium users</h2>
    <br>

    <form method="POST" action="/god/users/promote">
        @csrf 
        @method('PUT')
        <div class="control control--select control--button">
            <label class="control__label" for="promote">Promote users to Premium</label>
            <div>
                <select name="ids[]" id="promote" multiple>
                    @foreach($users->where('premium', 0)->sortBy('username') as $user) 
                        <option value="{{ $user->id }}">{{ $user->username }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                <button type="submit" class="btn">
                    <i class="fa-solid fa-crown"></i>Promote
                </button>
            </div>
        </div>
    </form>

    <form method="POST" action="/god/users/demote">
        @csrf 
        @method('PUT')
        <div class="control control--select control--button">
            <label class="control__label" for="demote">Demote users to Regular</label>
            <div>
                <select name="ids[]" id="demote" multiple>
                    @foreach($users->where('premium', 1)->sortBy('username') as $user) 
                        <option value="{{ $user->id }}">{{ $user->username }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-crown"></i>Demote
                </button>
            </div>
        </div>
    </form>

    <br>
    <h2>Currently there are {{ $timelines->where('featured', 1)->count() }} featured timelines</h2>
    <br>

    <form method="POST" action="/god/timelines/feature">
        @csrf 
        @method('PUT')
        <div class="control control--select control--button">
            <label class="control__label" for="feature">Feature Timelines</label>
            <div>
                <select name="ids[]" id="feature" multiple>
                    @foreach($timelines->where('featured', 0)->sortBy('title') as $timeline) 
                        <option value="{{ $timeline->id }}">{{ $timeline->title }} (/{{ $timeline->slug }}/)</option>
                    @endforeach
                </select>
                <button type="submit" class="btn">
                    <i class="fa-solid fa-star"></i>Feature
                </button>
            </div>
        </div>
    </form>

    <form method="POST" action="/god/timelines/defeature">
        @csrf 
        @method('PUT')
        <div class="control control--select control--button">
            <label class="control__label" for="defeature">Defeature Timelines</label>
            <div>
                <select name="ids[]" id="defeature" multiple>
                    @foreach($timelines->where('featured', 1)->sortBy('title') as $timeline) 
                        <option value="{{ $timeline->id }}">{{ $timeline->title }} (/{{ $timeline->slug }}/)</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-star"></i>Defeature
                </button>
            </div>
        </div>
    </form>
    
@endsection