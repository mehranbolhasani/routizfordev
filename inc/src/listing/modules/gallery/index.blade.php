@if( ! empty( $gallery ) )
    @if( $display == 'adaptive' )
        <div class="brk-mod-listing brk-mod-listing-gallery" data-type="gallery">
            @if( ! empty( $name ) )
                <h4>{{ $name }}</h4>
            @endif

            <div class="brk-cover brk--gallery-lighbox">

                <div class="brk--images" data-size="{{ min( 3, $gallery_num ) }}">
                    @foreach( $gallery as $key => $image )
                        @if( isset( $image['ulz_gallery_large'] ) )
                            <a href="#" class="brk--image" style="background-image: url('{{ $image['ulz_gallery_large'] }}');"></a>
                        @endif
                        <?php if( $key >= 2 ) { break; } ?>
                    @endforeach
                </div>

                <ul class="brk-lightbox-stack" style="margin:0;list-style:none;">
                    @foreach( $gallery as $key => $image )
                        <li class="brk-lightbox" data-image="{{ $image['ulz_gallery_large'] }}"></li>
                    @endforeach
                </ul>
                @if( count( $gallery ) > 3 )
                    <ul class="brk-gallery-actions brk--bottom">
                        <li>
                            <a href="#" data-action="expand-gallery">
                                <i class="far fa-images"></i>
                                <span>{{ sprintf( $strings->more_images, $gallery_num - 3 ) }}</span>
                            </a>
                        </li>
                    </ul>
                @endif

            </div> <!-- cover -->

        </div>
    @else
        <div class="brk-mod-listing brk-mod-listing-gallery-files" data-type="gallery-files">
            @if( ! empty( $name ) )
                <h4>{{ $name }}</h4>
            @endif

            <div class="brk-cover brk-cover-adaptive brk--gallery-lighbox">

                <ul>
                    @foreach( $gallery as $key => $image )
                        <li data-id="{{ $image['id'] }}">
                            <a href="{{ $image['src'] }}" target="_blank">
                                <i class="material-icon-file_copy"></i>
                                <span>{{ $image['name'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

            </div> <!-- cover -->

        </div>
    @endif
@endif
