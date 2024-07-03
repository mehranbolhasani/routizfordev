<li class="rz-flex rz-align-center">
    <div class="rz-list-drag">
        <i class="fas fa-arrows-alt"></i>
    </div>
    <div class="rz-list-name">
        {{ $option_name }}
        <input type="hidden" name="{{ $id }}[]" value="{{ $option_id }}" @if( $disabled ) disabled="disabled" @endif>
    </div>
</li>