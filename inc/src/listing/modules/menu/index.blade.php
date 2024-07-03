@if( is_array( $items ) and ! empty( $items ) )
    <div class="rz-mod-menu">
        @if( ! empty( $name ) )
            <h4>{{ $name }}</h4>
        @endif
        <div class="rz-price-menu">
            <div class="rz--tabs">
                <ul>
                    @foreach( $items as $section_key => $section )
                        <li class="{{ $section_key == 0 ? 'rz-active' : '' }}">
                            <a href="#">
                                {{ $section->fields->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="rz--sections">
                @foreach( $items as $section_key => $section )
                    <section class="rz--section{{ $section_key == 0 ? ' rz-active' : '' }}">
                        @if( $section->fields->items )
                            <ul>
                                @foreach( $section->fields->items as $item )
                                    @if( ! $item->fields->name ) @continue @endif
                                    <li class="{{ $item->fields->focus ? 'rz--is-focus' : '' }}">
                                        @if( $item->fields->focus )
                                            <span class="rz--focus">{{ $item->fields->focus }}</span>
                                        @endif
                                        <div class="rz--heading">
                                            <div class="rz--name">
                                                <h4>{{ $item->fields->name }}</h4>
                                            </div>
                                            <span class="rz--price rz-font-heading">
                                                {!! is_numeric( $item->fields->price ) ? Rz()->format_price( $item->fields->price ) : $item->fields->price !!}
                                            </span>
                                        </div>
                                        @if( $item->fields->description )
                                            <div class="rz--description">{{ $item->fields->description }}</div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                @endforeach
            </div>
        </div>
    </div>
@endif
