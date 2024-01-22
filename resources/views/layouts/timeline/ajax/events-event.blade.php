
<div itemscope itemtype="https://schema.org/Event" class="event-item" data-order="{{ $event->order_overall }}" data-id="{{ $event->id }}">
    <div class="event">
        <div class="event-wrapper">
            <div>
                <span class="fa-stack event-close">
                    <i class="fa-solid fa-circle fa-stack-2x"></i>
                    <i class="fa-solid fa-xmark fa-stack-1x"></i>
                </span>
                <div class="event-header">
                    @if($event->tagsHighlighted->count())
                        <ul class="event-subheader">
                            @foreach($event->tagsHighlighted as $highlight) 
                                <li style="background:{{ $highlight->color }};">
                                    {{ $highlight->tag }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
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
                                    <a itemprop="hasMap" itemtype="https://schema.org/Map" href="this url to this event loc on map">Map</a>
                                @endif
                            </div>
                            @if($event->description)
                            <span class="event-read">
                                Read more<i class="fas fa-chevron-right"></i>
                            </span>   
                            @endif                                 
                        </div>
                        @if($event->image)
                            <style>.events--md .event-item[data-id="{{ $event->id }}"] .event-header{min-height: 136px;} .event-item[data-id="{{ $event->id }}"] img{object-position:{{ $event->image_thumbnail }};}.event--open .event-item[data-id="{{ $event->id }}"] img{object-position: {{ $event->image_large }};}</style>
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
                            <i class="far fa-file-alt"></i>{{ $event->sources->count() > 1 ? $event->sources->count().' sources' : '1 source' }}
                        </li>
                    @endif
                    @if($event->location_show == 1 && $event->location)
                        <li class="event-map">
                            <i class="fas fa-map-marker-alt"></i>View <span>on</span> map
                        </li>
                    @endif
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