@include('label.index')

<div class="rz-upload">

    @if( ! $disabled )

        <!-- input -->
        <textarea class="rz-upload-input rz-none" type="text" name="{{ $id }}" placeholder="{{ $placeholder }}" {{ $v_model ? "v-model={$v_model}" : '' }}>{{ is_array( $value ) ? json_encode( $value ) : $value }}</textarea>

        <!-- button -->
        <label for="rz-upload-{{ $id }}" class="rz-button rz-light rz-upload-button">
            <span>{{ $button['label'] }}</span>
            <span><i class="fas fa-arrow-up rz-ml-1"></i></span>
            {{ Rz()->preloader() }}
        </label>

        <!-- file -->
        @if( ! $is_admin )
            <div class="rz-none">
                <input class="rz-upload-file" type="file" id="rz-upload-{{ $id }}" @php echo $multiple_upload ? 'multiple="true"' : ''; @endphp>
            </div>
        @endif

        <!-- field info -->
        @if( $display_info )
            <div class="rz-field-info">
                <span>{{ sprintf( $strings->max_file_size, number_format( wp_max_upload_size() / 1048576 ) ) }}</span>
                @if( $multiple_upload )
                    <span>{{ $strings->drag_reorder }}</span>
                @endif
                <span style="display:block;"><b>Note: </b>{{ $strings->featured_note }}</span>
            </div>
        @endif

    @endif

    <!-- image preview -->
    <div class="rz-image-preview rz-no-select">
        @if( $preview and is_array( $preview ) )
            @foreach( $preview as $prv )
                <div class="rz-image-prv-wrapper" data-id="{{ $prv->id }}" data-thumb="{{ $prv->thumb }}">
                    <div class="rz-image-prv">
                        <div class="rz-image-prv-outer">
                            <div class="rz-image-prv-inner">
                                @if( $upload_type == 'image' )
                                    <img src="{{ $prv->thumb }}" alt="">
                                @else
                                    <i class="fas fa-file-alt"></i>
                                @endif
                                @if( ! $disabled )
                                    <span class='rz-image-remove rz-transition'><i class='fas fa-times'></i></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <p class="rz-file-name">
                        <a class="rz-ellipsis" href="{{ $prv->url }}" target="_blank">{{ $prv->name }}</a>
                    </p>
                </div>
            @endforeach
        @endif
    </div>

    <!-- error output -->
    <div class="rz-error-output"></div>

</div>
