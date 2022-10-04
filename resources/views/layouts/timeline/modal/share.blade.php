@extends('layouts.modal.master')

@section('modal_content')
    @include('layouts.timeline.elements.social', ['more'=>false])
    <div class="form-input form-input-copy">
        <input type="text" readonly value="{{ request()->getSchemeAndHttpHost() }}/{{ $timeline->id }}/{{ $timeline->slug }}" />
        <button>
            Copy
        </button>
    </div>
@endsection