@push('stylesheets')
    @vite('resources/css/map.scss')
@endpush
@push('scripts')
    <script src="https://rawgit.com/RickStrahl/jquery-resizable/master/src/jquery-resizable.js"></script>
    @vite('resources/js/map.js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ App::environment() == 'local' ? config('constants.map.key.local') : config('constants.map.key.production') }}&callback=initMap&v=weekly" defer></script>
@endpush
<span class="map-open fa-stack fa-2x">
    <i class="fa-solid fa-circle fa-stack-2x"></i>
    <i class="fa-solid fa-map-location fa-stack-1x"></i>
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
<div id="map"></div>