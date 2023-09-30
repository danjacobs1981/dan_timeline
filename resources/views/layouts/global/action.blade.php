@if(session('action'))
    <div class="action {{ session('type') ? session('type') : 'success' }}">
        <div>
            <em>
                {{ session('action') }}
            </em>
            <span class="fa-stack">
                <i class="fa-solid fa-circle fa-stack-2x"></i>
                <i class="fa-solid fa-xmark fa-stack-1x"></i>
            </span>
        </div>
    </div>
@endif