@push('stylesheets')
    @vite('resources/css/timeline/map.scss')
@endpush
@push('scripts')
    @vite('resources/js/timeline/map-start.js')
@endpush
<div class="loading">
    <div class="dots"><div></div><div></div><div></div><div></div></div>
</div>
<div class="map-overlay">
    <span class="map-more">
        <i class="fa-solid fa-chevron-left"></i>
        <span>
            <i class="fa-solid fa-chevron-right"></i>Show Timeline
        </span>
    </span>
    <span class="map-close">
        <i class="fa-solid fa-chevron-down"></i>
    </span>
    <span class="map-auto fa_checkbox">
        <input type="checkbox" id="map_auto" name="map_auto" checked />
        <label for="map_auto">
            <span class="fa-stack">
                <i class="fa-regular fa-square fa-stack-1x"></i>
                <i class="fa-solid fa-square-check fa-stack-1x"></i>
            </span><em>Sync Map<em>/Timeline</em></em>
        </label>
    </span>
    <div class="map-options">
        <span class="map-map">
            Map
        </span>
        <span class="map-satellite">
            Satellite
        </span>        
    </div>
    <span class="map-fullscreen">
        <i class="fa-solid fa-expand"></i>
        <i class="fa-solid fa-compress"></i>
    </span>
    <div class="map-zoom">
        <span class="map-in">
            <i class="fa-solid fa-plus"></i>
        </span>
        <span class="map-out">
            <i class="fa-solid fa-minus"></i>
        </span>
    </div>
</div>
<div class="resizer">
    <span></span>
</div>
<div id="gmap"></div>