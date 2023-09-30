@isset($modal_title)
<div class="modal-header">
    <h5 class="modal-title">
        {{ $modal_title }}
    </h5>
</div>
@endisset
<div class="modal-body">
    @include($route, $routeParams)
</div>