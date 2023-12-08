
<div itemscope itemtype="https://schema.org/Event" class="event-item" data-order="{{ $event->order_overall }}">
    <!--<h2 itemprop="startDate" content="{{ $event->date_iso }}">
        {!! $event->date_html !!}
    </h2>-->
    <div class="event">
        <div class="event-wrapper">
            <div>
                <span class="fa-stack event-close">
                    <i class="fa-solid fa-circle fa-stack-2x"></i>
                    <i class="fa-solid fa-xmark fa-stack-1x"></i>
                </span>
                <div class="event-header">
                    <ul class="event-subheader">
                        <li>
                            Order: {{ $event->order_overall }}
                        </li>
                        <li>
                            Date Type: {{ $event->date_type }}
                        </li>
                        <li>
                            ID: {{ $event->id }}
                        </li>
                        <li>
                            TZ: {{ $event->location_tz }}
                        </li>
                    </ul>
                    <div>
                        <div>
                            <h3 itemprop="name">
                                {{ $event->title }}
                            </h3>
                            <div itemprop="location" itemscope itemtype="https://schema.org/Place">
                                @if($event->location_show == 1 && $event->location)
                                <p itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                    <i class="fas fa-map-marker-alt"></i><span itemprop="addressLocality">{{ $event->location }}</span></span>
                                </p>
                                @endif
                                <a itemprop="hasMap" itemtype="https://schema.org/Map" href="this url to this event loc on map">Map</a>
                            </div>
                            <span class="event-read">
                                Read more<i class="fas fa-chevron-right"></i>
                            </span>                                    
                        </div>
                        @if($event->image)
                            <style>img[data-id="{{ $event->id }}"]{object-position:{{ $event->image_thumbnail }};}.event--open img[data-id="{{ $event->id }}"]{object-position: {{ $event->image_large }};}</style>
                            <img data-id="{{ $event->id }}" itemprop="image" src="{{ asset('storage/images/timeline/'.$event->timeline_id.'/'.$event->id.'/'.$event->image) }}" alt="{{ html_entity_decode($event->title) }}" />
                        @endif
                    </div>
                </div>
                <div class="event-body">
                    <div itemprop="description">
                        {!! preg_replace("#<p>(\s|&nbsp;|</?\s?br\s?/?>)*</?p>#", '', '<p>'.implode('</p><p>', array_filter(explode("\n", $event->description))).'</p>') !!}
                    </div>
                    <div class="event-sources">
                        <h4>Sources</h4>
                        <ul>
                            <li>
                                <i class="far fa-window-maximize"></i><a href="#" target="_blank">NY Post article</a>
                            </li>
                            <li>
                                <i class="fab fa-youtube"></i><a href="#" target="_blank">Van is captured on video in very high quality</a><i class="fas fa-check event-verified"></i>
                            </li>
                            <li>
                                <i class="fab fa-tiktok"></i><a href="#" target="_blank">TikTok videos uploaded</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="event-footer">
                <ul class="event-resources">
                    <li class="event-source">
                        <i class="far fa-file-alt"></i>3 sources
                    </li>
                    <li class="event-map">
                        <i class="fas fa-map-marker-alt"></i>View <span>on</span> map
                    </li>
                </ul>
                <ul class="event-options">
                    <li class="event-comments" data-reveal="comments">
                        <i class="fa-solid fa-comment"></i><span>2 <span>comments</span></span>
                    </li>
                    <li class="event-share">
                        <i class="fa-solid fa-share-nodes"></i>
                    </li>
                    <li class="event-more dropdown-toggle">
                        <i class="fa-solid fa-ellipsis dropdown-close"></i>
                        <div class="dropdown" data-backdrop data-position-x="right" data-position-y="top">
                            <ul>
                                <li>
                                    <a href="#"><i class="fa-solid fa-pencil"></i>Suggest an edit</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa-solid fa-user-group"></i>Request to collaborate</a>
                                </li>
                                <span></span>
                                <li>
                                    <a href="#"><i class="fa-solid fa-circle-exclamation"></i>Report</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>