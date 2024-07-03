<?php

class WC_Product_Listing_Claim extends WC_Product {

    public function get_type() {
        return 'listing_claim';
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
