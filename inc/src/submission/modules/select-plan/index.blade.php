<div class="rz-submission-heading">
    <h4 class="rz--title">{{ $strings->select_plan }}</h4>
</div>

<form>
    <div class="rz-select-plan">
        <div class="rz-plans rz-grid rz-no-select">

            @if( $products )
                @foreach( $products as $product )
                    @php
                        $plan = new \Routiz\Inc\Src\Woocommerce\Packages\Plan( $product->get_id() );
                        $type = $product->get_type();
                        $availability = $plan->availability();
                        $short_desc = $product->get_short_description();
                    @endphp
                    <div class="rz-col-6 rz-col-md-12 rz-mb-3 rz-flex">
                        <label>
                            <input type="radio" name="rz_plan" value="{{ $product->get_id() }}">
                            <span class="rz-plan rz--plan-type-{{ $type }} @if( $product->get_featured() ) rz-highlight @endif @if( $availability and $plan->is_purchasable() ) rz-has-available @endif ">
                                @if( $product->get_featured() )
                                    <span class="brk--badge">{{ $strings->most_popular }}</span>
                                @endif
                                @if( $availability and $plan->is_purchasable() )
                                    <div class="rz-available">
                                        <span><i class="fas fa-certificate rz-mr-1"></i>{{ $strings->owned }}:</span>
                                        @if( $availability == 'unlimited' )
                                            <span>{{ $strings->unlimited }}</span>
                                        @else
                                            <span><?php echo sprintf( _n( $strings->available_submission, $strings->available_submissions, $availability, 'routiz' ), $availability ); ?></span>
                                        @endif
                                    </div>
                                @endif
                                <span class="rz-heading">
                                    @if( $product->get_price_html() )
                                        <span class="rz-price">
                                            <p>{!! $product->get_price_html() !!}</p>
                                        </span>
                                    @endif
                                    <span class="rz--name">{{ $product->get_name() }}</span>
                                    @if( $short_desc )
                                        <span class="rz--desc">{{ $short_desc }}</span>
                                    @endif
                                </span>
                                <span class="rz-content">
                                    {!! do_shortcode( $product->get_description() ) !!}
                                </span>
                                <span class="rz-action">
                                    <span class="rz-button {{ $product->get_featured() ? 'rz-button-accent' : 'rz-white-gray' }}">
                                        <i>{{ $strings->select_plan }}</i>
                                    </span>
                                </span>
                            </span>
                        </label>
                    </div>
                @endforeach
            @else
                <div class="rz-col-12">
                    <div class="rz-notice rz-notice-alert">
                        <p>
                            {{ $strings->no_packages }}
                        </p>
                    </div>
                </div>
            @endif

        </div>
        <p class="rz-select-plan-error rz-text-center">
            <span class="rz-error"></span>
        </p>
    </div>
</form>
