@if (!auth()->user()->premium)
    <div class="control">
        <span class="control__label">
            {{ $title }}
        </span>
        <a href="#" class="premium"><i class="fa-solid fa-crown"></i>Go Premium</a>
        <p>
            {{ $message }}
        </p>
    </div>
@endif