@include('label.index')

<div class="rz-location">
    <div class="rz-grid">

        <div class="rz-form-group rz-col-12 rz-col-md-12 rz-mb-4">
            <label class="rz-block rz-mb-2">{{ $strings->address }}</label>
            <div class="rz-dropdown">
                <input type="text" class="rz-map-address" name="{{ $id }}[]" value="{{ $value[0] }}" placeholder="{{ $strings->e_g_barcelona }}" {{ $readonly ? 'form="fake-form-readonly"' : '' }} autocomplete="off">
                <div class="rz-dropdown-list rz-scrollbar">
                    <ul></ul>
                </div>
            </div>
        </div>
        <div class="rz-form-group rz-col-6 rz-col-md-12 rz-mb-4 rz-none">
            <label class="rz-block rz-mb-2">{{ $strings->latitude }}</label>
            <input type="text" value="{{ $value[1] }}" disabled>
                <input type="hidden" class="rz-map-lat" name="{{ $id }}[]" value="{{ $value[1] }}" {{ $readonly ? 'form="fake-form-readonly"' : '' }}>
        </div>
        <div class="rz-form-group rz-col-6 rz-col-md-12 rz-mb-4 rz-none">
            <label class="rz-block rz-mb-2">{{ $strings->longitude }}</label>
            <input type="text" value="{{ $value[2] }}" disabled>
                <input type="hidden" class="rz-map-lng" name="{{ $id }}[]" value="{{ $value[2] }}" {{ $readonly ? 'form="fake-form-readonly"' : '' }}>
        </div>

        <!-- geo information -->
        <div class="rz-none">
            <input type="text" class="rz-map-country" name="{{ $id }}[]" value="{{ isset( $value[3] ) ? $value[3] : '' }}">
            <input type="text" class="rz-map-city" name="{{ $id }}[]" value="{{ isset( $value[4] ) ? $value[4] : '' }}">
            <input type="text" class="rz-map-city-alt" name="{{ $id }}[]" value="{{ isset( $value[5] ) ? $value[5] : '' }}">
        </div>

    </div>
    <div class="rz-map"></div>
</div>
