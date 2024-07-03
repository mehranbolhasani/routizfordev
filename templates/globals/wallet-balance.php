<?php

use \Routiz\Inc\Src\Wallet;

defined('ABSPATH') || exit;

$wallet = new Wallet();

?>

<div class="rz-wallet-balance">
    <?php echo Rz()->format_price( $wallet->get_balance() ); ?>
</div>