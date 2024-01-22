<div id="tagHighlight" data-id="{{ $tag->id }}">
    <p>Select the appearance of the tag featuring on the event:</p>
    <ul>
        <li data-color="green" style="background:green;">
            <span>{{ $tag->tag }}</span>
        </li>
        <li data-color="red" style="background:red;">
            <span>{{ $tag->tag }}</span>
        </li>
        <li data-color="fuchsia" style="background:fuchsia;">
            <span>{{ $tag->tag }}</span>
        </li>
        <li data-color="purple" style="background:purple;">
            <span>{{ $tag->tag }}</span>
        </li>
        <li data-color="maroon" style="background:maroon;">
            <span>{{ $tag->tag }}</span>
        </li>
        <li data-color="olive" style="background:olive;">
            <span>{{ $tag->tag }}</span>
        </li>
        <li data-color="navy" style="background:navy;">
            <span>{{ $tag->tag }}</span>
        </li>
        <li data-color="teal" style="background:teal;">
            <span>{{ $tag->tag }}</span>
        </li>
        <li data-color="silver" style="background:silver;">
            <span>{{ $tag->tag }}</span>
        </li>
        <li data-color="gray" style="background:gray;">
            <span>{{ $tag->tag }}</span>
        </li>
        <li data-color="black" style="background:black;">
            <span>{{ $tag->tag }}</span>
        </li>
    </ul>
    <span>
        Or, <em>remove the highlight</em> from this tag.
    </span>
</div>

@isset($modal)
    @vite('resources/js/portal/timeline/tag/highlight.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/tag/highlight.js')
    @endpush
@endif