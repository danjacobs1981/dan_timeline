@push('stylesheets')
    @vite('resources/css/map.scss')
@endpush
@push('scripts')
    @vite('resources/js/timeline/map-start.js')
@endpush
<div class="loading">
    <div class="dots"><div></div><div></div><div></div><div></div></div>
</div>
<span class="map-open fa-stack fa-2x">
    <i class="fa-solid fa-circle fa-stack-2x"></i>
    <i class="fa-solid fa-map-marker-alt fa-stack-1x"></i>
</span>
<div class="resizer">
    <div>
        <i class="map-close fa-solid fa-angle-down"></i> 
    </div>
    <span>
        <div></div>
    </span>
    <div>
        <i class="map-layer fa-solid fa-layer-group" data-type="satellite"></i>
        <i class="map-expand fa-solid fa-expand"></i>
        <i class="map-compress fa-solid fa-compress"></i>
    </div>
</div>
<div id="gmap"></div>