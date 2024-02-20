@push('stylesheets')
    @vite('resources/css/timeline/comments.scss')
@endpush
@push('scripts')
    @vite('resources/js/timeline/comments.js')
@endpush
<div class="reveal__wrapper">
    <div class="reveal__header">
        <strong>Comments</strong>
        <span class="fa-stack reveal__close">
            <i class="fa-solid fa-circle fa-stack-2x"></i>
            <i class="fa-solid fa-xmark fa-stack-1x"></i>
        </span>
    </div>
    <div class="reveal__body">
        <div class="comments">
            <div class="loading">
                <div class="dots"><div></div><div></div><div></div><div></div></div>
            </div>
            <div class="comments-wrapper"></div>
        </div>
    </div>
</div>