<?php

class WC_Product_Listing_Subscription_Plan extends WC_Product_Subscription {

    public function get_type() {
        return 'listing_subscription_plan';
    }

    public function is_sold_individually() {
		return true;
	}

    public function is_purchasable() {
		return true;
	}

    public function is_virtual() {
		return true;
	}

    public function is_visible() {
		return false;
	}

}
