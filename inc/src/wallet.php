<?php

namespace Routiz\Inc\Src;

use \Routiz\Inc\Src\Request\Request;

class Wallet {

    use \Routiz\Inc\Src\Traits\Singleton;

    public $user_id;

    function __construct( $user_id = null ) {

        $this->user_id = is_null( $user_id ) ? get_current_user_id() : $user_id;

    }

    public function get_balance() {

        global $wpdb;

        $balance = $wpdb->get_var("
            SELECT balance
            FROM {$wpdb->prefix}routiz_wallet
            WHERE user_id = {$this->user_id}
        ");

        return floatval( $balance );

    }

    public function add_funds( $amount, $order_id = null, $source = null ) {

        global $wpdb;

        $amount = floatval( $amount );

        if( $amount == 0 ) {
            return;
        }

        $wpdb->insert( $wpdb->prefix . 'routiz_wallet_transactions', [
            'user_id' => $this->user_id,
            'order_id' => $order_id,
            'type' => $amount < 0 ? 'debit' : 'credit',
            'source' => $source,
            'amount' => $amount < 0 ? $amount * -1 : $amount
        ]);

        $this->add_to_balance( $amount );

    }

    public function add_to_balance( $amount ) {

        global $wpdb;

        if( ! $this->wallet_exists() ) {
            $this->create_wallet();
        }

        $current_balance = $this->get_balance();
        $new_balance = $current_balance + $amount;

        if( $new_balance < 0 ) {
            return;
        }

        $wpdb->update( $wpdb->prefix . 'routiz_wallet', [
            'balance' => $new_balance,
        ], [
            'user_id' => $this->user_id
        ]);

    }

    public function wallet_exists() {

        global $wpdb;

        $wallet_count = (int) $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->prefix}routiz_wallet
            WHERE user_id = {$this->user_id}
        ");

        return $wallet_count > 0;

    }

    public function create_wallet() {

        global $wpdb;

        $wpdb->insert( $wpdb->prefix . 'routiz_wallet', [
            'user_id' => $this->user_id,
            'balance' => 0,
            'spent' => 0,
        ]);

    }

    public function get_payouts() {

        global $wpdb;

        $request = Request::instance();
        $rows_per_page = 20;
        $page = $request->has('onpage') ? $request->get('onpage') : 1;
        $offset = ( $page - 1 ) * $rows_per_page;

        $results = $wpdb->get_results("
            SELECT *
            FROM {$wpdb->prefix}routiz_wallet_payouts
            WHERE user_id = {$this->user_id}
            ORDER BY id DESC
            LIMIT {$rows_per_page}
            OFFSET {$offset}
        ");

        $results_count = (int) $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->prefix}routiz_wallet_payouts
            WHERE user_id = {$this->user_id}
        ");

        return (object) [
            'results' => $results,
            'max_num_pages' => ceil( $results_count / $rows_per_page ),
        ];

    }

    static function get_all_payouts() {

        global $wpdb;

        $request = Request::instance();
        $rows_per_page = 20;
        $page = $request->has('onpage') ? $request->get('onpage') : 1;
        $offset = ( $page - 1 ) * $rows_per_page;

        $results = $wpdb->get_results("
            SELECT *
            FROM {$wpdb->prefix}routiz_wallet_payouts
            ORDER BY id DESC
            LIMIT {$rows_per_page}
            OFFSET {$offset}
        ");

        $results_count = (int) $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->prefix}routiz_wallet_payouts
        ");

        return (object) [
            'results' => $results,
            'max_num_pages' => ceil( $results_count / $rows_per_page ),
        ];

    }

    public function get_translations() {

        global $wpdb;

        $request = Request::instance();
        $rows_per_page = 20;
        $page = $request->has('onpage') ? $request->get('onpage') : 1;
        $offset = ( $page - 1 ) * $rows_per_page;

        $results = $wpdb->get_results("
            SELECT *
            FROM {$wpdb->prefix}routiz_wallet_transactions
            WHERE user_id = {$this->user_id}
            ORDER BY id DESC
            LIMIT {$rows_per_page}
            OFFSET {$offset}
        ");

        $results_count = (int) $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->prefix}routiz_wallet_transactions
            WHERE user_id = {$this->user_id}
        ");

        return (object) [
            'results' => $results,
            'max_num_pages' => ceil( $results_count / $rows_per_page ),
        ];

    }

    static function approve_payout( $payout_id ) {

        global $wpdb;

        $payout = $wpdb->get_row("
            SELECT *
            FROM {$wpdb->prefix}routiz_wallet_payouts
            WHERE id = {$payout_id}
            ORDER BY id DESC
        ");

        if( ! $payout or $payout->status !== 'pending' ) {
            return;
        }

        $wpdb->update( $wpdb->prefix . 'routiz_wallet_payouts', [
            'status' => 'approved',
        ], [
            'id' => $payout_id
        ]);

    }

    static function decline_payout( $payout_id ) {

        global $wpdb;

        $request = Request::instance();

        if( ! wp_verify_nonce( $request->get('_wpnonce'), "routiz_payout{$payout_id}" ) ) {
            return;
        }

        $payout = $wpdb->get_row("
            SELECT *
            FROM {$wpdb->prefix}routiz_wallet_payouts
            WHERE id = {$payout_id}
            ORDER BY id DESC
        ");

        if( ! $payout or $payout->status !== 'pending' ) {
            return;
        }

        $wallet = new Wallet( $payout->user_id );
        $wallet->add_funds( $payout->amount, null, 'declined_payout' );

        $wpdb->update( $wpdb->prefix . 'routiz_wallet_payouts', [
            'status' => 'declined',
        ], [
            'id' => $payout_id
        ]);

    }

    static function get_earnings( $user_id = null ) {

        global $wpdb;

        if( is_null( $user_id ) ) {
            $user_id = get_current_user_id();
        }

        return $wpdb->get_var(
            $wpdb->prepare("
                    SELECT SUM( amount ) as earnings
                    FROM {$wpdb->prefix}routiz_wallet_transactions
                    WHERE user_id = %d
                    AND type = 'credit'
                    AND source = 'earnings'
                    AND created_at > %s
                ",
                $user_id,
                date('Y-m-d H:i:s', strtotime( 'today', time() ) )
            )
        );

    }

}
