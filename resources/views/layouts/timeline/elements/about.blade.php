@push('stylesheets')
    @vite('resources/css/about.scss')
@endpush
<div class="reveal__wrapper">
    <div class="reveal__header">
        <strong>About</strong>
        <span class="fa-stack reveal__close">
            <i class="fa-solid fa-circle fa-stack-2x"></i>
            <i class="fa-solid fa-xmark fa-stack-1x"></i>
        </span>
    </div>
    <div class="reveal__body">
        <p>Timeline: {{ $timeline->title }}</p>
        <em>
            by <strong><a href="{{ route('profile.show', ['username' => $timeline->user->username ]) }}">{{ $timeline->user->username }}</a></strong> <span>&plus; <span>2 collaborators</span></span>
        </em>
    </div>
</div>