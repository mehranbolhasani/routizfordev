<div class="rz-form-group rz-col-12">

    <div class="rz-filter-heading">
        @if( $name )
            <h5 class="rz--heading">{{ $name }}</h5>
            @if( isset( $description ) and ! empty( $description ) )
                <p>{{ $description }}</p>
            @endif
        @endif
    </div>

</div>
