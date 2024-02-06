
<div itemscope itemtype="https://schema.org/Event" class="event-item" data-order="{{ $event->order_overall }}" data-id="{{ $event->id }}">
    <div class="event">
        <div class="event-wrapper">
            <div>
                <span class="fa-stack event-close">
                    <i class="fa-solid fa-circle fa-stack-2x"></i>
                    <i class="fa-solid fa-xmark fa-stack-1x"></i>
                </span>
                <div class="event-header{{ $event->image ? ' event-image' : ''}}">
                    <div>
                        <div>
                            <h3 itemprop="name">
                                {{ $event->title }}
                            </h3>
                            @if($event->location_show == 1 && $event->location)
                                <div class="event-location" itemprop="location" itemscope itemtype="https://schema.org/Place" data-zoom="{{ $event->location_zoom }}" data-lat="{{ $event->location_lat }}" data-lng="{{ $event->location_lng }}">
                                    <p itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                        <i class="fas fa-map-marker-alt"></i><span itemprop="addressLocality">{{ $event->location }}</span></span>
                                    </p>
                                    <a itemprop="hasMap" itemtype="https://schema.org/Map" href="this url to this event loc on map">Map</a>
                                </div>
                            @endif
                            @if($event->description)
                            <span class="event-read">
                                Read more<i class="fas fa-chevron-right"></i>
                            </span>   
                            @endif                                 
                        </div>
                        @if($event->image)
                            <style>.event-item[data-id="{{ $event->id }}"] img{object-position:{{ $event->image_thumbnail }};} .event-item[data-id="{{ $event->id }}"] .event--open img{object-position: {{ $event->image_large }};}</style>
                            <img itemprop="image" src="{{ asset('storage/images/timeline/'.$event->timeline_id.'/'.$event->id.'/'.$event->image) }}" alt="{{ html_entity_decode($event->title) }}" />
                        @endif
                    </div>
                </div>
                <div class="event-body">
                    @if($event->description)
                        <div itemprop="description">
                            {!! preg_replace("#<p>(\s|&nbsp;|</?\s?br\s?/?>)*</?p>#", '', '<p>'.implode('</p><p>', array_filter(explode("\n", $event->description))).'</p>') !!}
                            <span class="event-read">
                                Read less<i class="fas fa-chevron-up"></i>
                            </span> 
                        </div> 
                    @endif
                    @if($event->tags->count())
                        <div class="event-tags">
                            <h4>Tags</h4>
                            <ul>
                                @foreach($event->tags as $tag) 
                                    <li>
                                        <span class="tag tag-{{ $tag->color }}">
                                            {{ $tag->tag }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if($event->sources->count())
                        <div class="event-sources">
                            <h4>Sources</h4>
                            <ul>
                                @foreach($event->sources as $source) 
                                    <li>
                                        <i class="{{ $source->fa_icon }}"></i><a href="{{ $source->url }}" target="_blank" rel="nofollow" title="">{{ $source->source }}</a><!--<i class="fas fa-check source-verified" title="Source has been verified"></i>-->
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <div class="event-footer">
                <ul class="event-resources">
                    @if($event->sources->count())
                        <li class="event-source">
                            <i class="far fa-file-alt"></i>Sources ({{ $event->sources->count() }})
                        </li>
                    @endif
                    @if($event->location_show == 1 && $event->location)
                        <li class="event-map">
                            <i class="fas fa-map-marker-alt"></i><span>View on&nbsp;</span> Map
                        </li>
                    @endif
                </ul>
                <ul class="event-options">
                    @if($event->timeline->comments && $event->timeline->comments_event)
                        <li class="event-comments" data-reveal="comments">
                            <i class="fa-regular fa-comment"></i><span>2&nbsp;<span> comments</span></span>
                        </li>
                    @endif
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
                                @if($event->timeline->collab)
                                    <li>
                                        <a href="#"><i class="fa-solid fa-user-group"></i>Request to collaborate</a>
                                    </li>
                                @endif
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