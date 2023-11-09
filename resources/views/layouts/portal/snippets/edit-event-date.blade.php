@inject('carbon', 'Carbon\Carbon')

<div id="timelineEventEditDate">

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="formEventEditDate" method="post" action="{{ route('timelines.events.update', [ 'timeline' => $timeline, 'event' => $event, 'date' => true ]) }}">

        @php
            if ($event->date_type == 1) {
                $predate = $event->date_year;
            } else if ($event->date_type == 2) {
                $predate = $carbon::parse($event->date_unix)->format('Y|n');
            } else if ($event->date_type == 3) {
                $predate = $carbon::parse($event->date_unix)->format('Y|n|j');
            } else if ($event->date_type == 4) {
                $predate = $carbon::parse($event->date_unix)->format('Y|n|j|h|i|a');
            }
        @endphp

        @csrf
        @method('put')

        @include('layouts.portal.snippets.date-picker')

    </form>

</div>

@isset($modal)
    @vite('resources/js/portal/timeline/event/edit-date.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/event/edit-date.js')
    @endpush
@endif