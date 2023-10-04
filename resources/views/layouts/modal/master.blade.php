@isset($modal_title)
<div class="modal-header">
    <h5 class="modal-title">
        {{ $modal_title }}
    </h5>
</div>
@endisset
<div class="modal-body">
    @include($route, isset($routeParams) ? $routeParams : array('modal' => true))
</div>
@isset($modal_buttons)
<div class="modal-buttons">
    @isset($modal_buttons['close'])
        <a href="#" rel="modal:close">
            {{ $modal_buttons['close'] }}
        </a>
    @endisset
    @isset($modal_buttons['action'])
        <button class="btn">
            {{ $modal_buttons['action'] }}
        </button>
    @endisset
</div>
@endisset