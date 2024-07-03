<?php

namespace Routiz\Inc\Utils;

use \Routiz\Inc\Src\Woocommerce\Account\Account;

class Woocommerce {

    use \Routiz\Inc\Src\Traits\Singleton;

    public $account;

    function __construct() {

        $this->account = Account::instance();

    }

    public function add_account_page( $page ) {
		$this->account->add_page( $page );
	}

}
