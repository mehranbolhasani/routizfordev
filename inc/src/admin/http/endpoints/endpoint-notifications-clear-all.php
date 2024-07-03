<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use Routiz\Inc\Src\Listing\Listing;

if (!defined('ABSPATH')) {
    exit;
}

class Endpoint_Notifications_Clear_All extends Endpoint {

    public $action = 'rz_notifications_clear_all';

    public function action() {
        // Call your custom function to delete all notifications for the current user


        routiz()->notify->clear_all_notification();
        
        // Send a success response back to the client
       wp_send_json([
            'success' => true
        ]);
    }

}
