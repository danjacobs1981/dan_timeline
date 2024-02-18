@push('stylesheets')
    @vite('resources/css/timeline/filters.scss')
@endpush
@push('scripts')
    @vite('resources/js/timeline/filters.js')
@endpush
<div class="reveal__wrapper">
    <div class="reveal__header">
        <strong>Filters</strong>
        <span class="fa-stack reveal__close">
            <i class="fa-solid fa-circle fa-stack-2x"></i>
            <i class="fa-solid fa-xmark fa-stack-1x"></i>
        </span>
    </div>
    @if($timeline->tagging)
        <div class="reveal__body filter__wrapper">
            <ul class="filter__group">
                @foreach($tags->sortBy('group', SORT_NATURAL|SORT_FLAG_CASE)->unique('group_id') as $group)
                    <li>
                        <h3>{{ $group->group }}</h3>
                        <ul class="filter__checkboxes">
                            @foreach($tags->where('group_id', $group->id)->sortBy('tag', SORT_NATURAL|SORT_FLAG_CASE)->unique('tag_id') as $tag)
                                <li class="filter__checkbox fa_checkbox">
                                    <input type="checkbox" id="f_{{ $tag->tag_id }}" name="tag" value="{{ $tag->tag_id }}" />
                                    <label for="f_{{ $tag->tag_id }}">
                                        <span class="fa-stack">
                                            <i class="fa-regular fa-square fa-stack-1x"></i>
                                            <i class="fa-solid fa-square-check fa-stack-1x"></i>
                                        </span>{{ $tag->tag }}
                                    </label>
                                </li>  
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="reveal__body">
            <ul class="filter__wrapper">
                @foreach($tags->sortBy('tag', SORT_NATURAL|SORT_FLAG_CASE)->unique('id') as $tag) 
                    <li class="filter__checkbox fa_checkbox">
                        <input type="checkbox" id="f_{{ $tag->id }}" name="tag" value="{{ $tag->id }}" />
                        <label for="f_{{ $tag->id }}">
                            <span class="fa-stack">
                                <i class="fa-regular fa-square fa-stack-1x"></i>
                                <i class="fa-solid fa-square-check fa-stack-1x"></i>
                            </span>{{ $tag->tag }}
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="reveal__footer">
        <span class="filter__clear">
            Clear all
        </span>
        <span class="btn filter__show">
            Show {{ $timeline->events->count() }} results
        </span>
    </div>
</div>