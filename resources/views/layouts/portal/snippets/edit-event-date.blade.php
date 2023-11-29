@inject('carbon', 'Carbon\Carbon')

<div id="timelineEventEditDate">

    <header>

        <ul class="tabs tabs--event">
            <li>
                <a href="#event-details-tab">Date &amp; Time</a>
            </li>
            <li>
                <a href="#event-map-tab">Map / Location</a>
            </li>
        </ul>

    </header>

    <section class="scrollbar">

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

            <section id="event-details-tab" class="event__tab" style="display:none;">

                <div class="eventDate">

                    @include('layouts.portal.snippets.form-date', [ 'timezone' => isset($event) ? $event->location_tz : null ])

                </div>

            </section>

            <section id="event-map-tab" class="event__tab" style="display:none;">

                <div class="eventMap">

                    @include('layouts.portal.snippets.form-location', [ 'date' => true ])

                </div>

            </section>

        </form>

    </section>

</div>

@isset($modal)
    @vite('resources/css/portal/timeline/event/create-edit.scss')
    @vite('resources/js/portal/timeline/event/edit-date-location.js')
    @vite('resources/js/portal/timeline/event/form-date-location.js')
@else
    @push('stylesheets')
        @vite('resources/css/portal/timeline/event/create-edit.scss')
    @endpush
    @push('scripts')
        @vite('resources/js/portal/timeline/event/edit-date-location.js')
        @vite('resources/js/portal/timeline/event/form-date-location.js')
    @endpush
@endif