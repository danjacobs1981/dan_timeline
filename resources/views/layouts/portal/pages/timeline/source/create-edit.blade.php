@if(isset($source))
    <meta name="source" content="{{ $source->id }}">
@endif

<div id="timelineSourceCreateEdit">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="formSourceCreateEdit" method="post" action="{{ isset($source) ? route('timelines.sources.update', [ 'timeline' => $timeline, 'source' => $source ]) : route('timelines.sources.store', [ 'timeline' => $timeline ]) }}">

        @php
            $fa_icon = null;
            if (isset($source)) {
                $fa_icon = $source->fa_icon;
            }
        @endphp

        @if(isset($source))
            @method('put')
        @endif

        @csrf

        @if(isset($source))
            <p>
                This source currently features on <strong>{{ $event_count == 1 ? '1 event' : $event_count.' events' }}</strong>.
            </p>
        @endif

        <div class="control control--textbox">
            <label class="control__label" for="url">
                URL
            </label>
            <input type="text" name="url" id="url" placeholder="Enter the URL of the source" value="{{ old('url', isset($source) ? $source->url : '') }}"/>
            <p>
                This can be a URL of a webpage, image, PDF, YouTube video, etc.
            </p>
        </div>

        <div class="control control--textbox">
            <label class="control__label" for="source">
                Title
            </label>
            <input type="text" name="source" id="source" value="{{ old('source', isset($source) ? $source->source : '') }}"/>
            <p>
                <a href="#" class="update-title">Auto-fill the title</a> by getting the page title from the URL above. This may not always work, so you may have to manually enter it!
            </p>
        </div>

        <!---<div class="control control--select">
            <label class="control__label" for="fa_icon">Icon</label>
            <select name="fa_icon" id="fa_icon" class="fontawesome">
                <option value="file-lines" {{ old('fa_icon') == 'file-lines' || ($fa_icon == 'file-lines') ? 'selected' : '' }}>&#xf15c;</option>
                <option value="file" {{ old('fa_icon') == 'file' || ($fa_icon == 'file') ? 'selected' : '' }}>&#xf15b;</option>
                <option value="folder-open" {{ old('fa_icon') == 'folder-open' || ($fa_icon == 'folder-open') ? 'selected' : '' }}>&#xf07c;</option>
                <option value="file-contract" {{ old('fa_icon') == 'file-contract' || ($fa_icon == 'file-contract') ? 'selected' : '' }}>&#xf56c;</option>
                <option value="receipt" {{ old('fa_icon') == 'receipt' || ($fa_icon == 'receipt') ? 'selected' : '' }}>&#xf543;</option>
                <option value="file-invoice" {{ old('fa_icon') == 'file-invoice' || ($fa_icon == 'file-invoice') ? 'selected' : '' }}>&#xf570;</option>
                <option value="download" {{ old('fa_icon') == 'download' || ($fa_icon == 'download') ? 'selected' : '' }}>&#xf019;</option>
                <option value="box-archive" {{ old('fa_icon') == 'box-archive' || ($fa_icon == 'box-archive') ? 'selected' : '' }}>&#xf187;</option>
                <option value="note-sticky" {{ old('fa_icon') == 'note-sticky' || ($fa_icon == 'note-sticky') ? 'selected' : '' }}>&#xf249;</option>
                <option value="file-arrow-down" {{ old('fa_icon') == 'file-arrow-down' || ($fa_icon == 'file-arrow-down') ? 'selected' : '' }}>&#xf56d;</option>
            </select>
            <p>If the URL is of a webpage then select an icon.</p>
        </div>--->

    </form>

    @if(isset($source))
        <div class="control">
            <span class="control__label">
                Delete Source
            </span>
            <a href="{{ route('timelines.sources.delete.showModal', [ 'timeline' => $timeline->id, 'source' => $source->id ]) }}" class="btn btn-danger" data-modal data-modal-class="modal-timeline-source-delete modal-delete" data-modal-size="modal-sm" data-modal-showclose="false" data-modal-clickclose="false">
                <i class="fa-regular fa-trash-can"></i>Delete
            </a>                            
        </div>
    @endif

</div>

@isset($modal)
    @vite('resources/css/portal/timeline/source/create-edit.scss')
    @vite('resources/js/portal/timeline/source/create-edit.js')
@else
    @push('stylesheets')
        @vite('resources/css/portal/timeline/source/create-edit.scss')
    @endpush
    @push('scripts')
        @vite('resources/js/portal/timeline/source/create-edit.js')
    @endpush
@endif
