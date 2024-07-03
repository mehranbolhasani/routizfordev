<?php

namespace Routiz\Inc\Utils;

class Notify {

    use \Routiz\Inc\Src\Traits\Singleton;

    protected function get_namespace( $id ) {

        $namespace = sprintf( '\Routiz\Inc\Src\Notification\Notifications\%s', str_replace( '-', '_', $id ) );

        if( class_exists( $namespace ) ) {
            return $namespace;
        }

        return null;

    }

    /*
     * attempt to notify through channels
     *
     */
    public function distribute( $id, $args, $params = [] ) {

        if( $namespace = $this->get_namespace( $id ) ) {
            $notification = new $namespace( $args, $params );
            $notification->send();
        }

    }

    /*
     * get user current active site notifications
     *
     */
    public function get_active_site( $user_id = null ) {

        global $wpdb;

        if( is_null( $user_id ) ) {
            $user_id = get_current_user_id();
        }

        // check if user exists
        if( get_userdata( $user_id ) === false ) {
            return;
        }

        return $wpdb->get_var( $wpdb->prepare("
            SELECT COUNT(*)
            FROM {$wpdb->prefix}routiz_notifications
            WHERE user_id = %d
            AND active = 1
        ", $user_id ) );

    }

    /*
     * get user latest site notifications
     *
     */
    public function get_latest_site( $user_id = null, $num = 20, $active = false ) {

        global $wpdb;

        if( is_null( $user_id ) ) {
            $user_id = get_current_user_id();
        }

        // check if user exists
        if( get_userdata( $user_id ) === false ) {
            return [];
        }

        $active_sql = $active ? 'AND active = 1' : '';

        $results = $wpdb->get_results( $wpdb->prepare("
            SELECT *
            FROM {$wpdb->prefix}routiz_notifications
            WHERE user_id = %d
            $active_sql
            ORDER BY created_at DESC
            LIMIT $num
        ", $user_id ), OBJECT );

        $out = [];

        foreach( $results as &$row ) {

            // get message content
            if( $namespace = $this->get_namespace( $row->code ) ) {
                $notification = new $namespace( $row );
                $out[] = (object) array_merge( $notification->get_site(), [
                    'active' => $row->active,
                    'created_at' => $row->created_at
                ]);
            }

        }
        return $out;

    }

    public function mark_site_as_read( $user_id = null ) {

        global $wpdb;

        if( is_null( $user_id ) ) {
            $user_id = get_current_user_id();
        }

        // check if user exists
        if( get_userdata( $user_id ) === false ) {
            return;
        }

        $results = $wpdb->query(
            $wpdb->prepare("
                    UPDATE {$wpdb->prefix}routiz_notifications SET active = 0 WHERE user_id = %d
                ",
                $user_id
            )
        );

    }


   public function clear_all_notification($user_id = null) {
    global $wpdb;

    if (is_null($user_id)) {
        $user_id = get_current_user_id();
    }

    // Check if user exists
    if (get_userdata($user_id) === false) {
        return;
    }

    $table_name = $wpdb->prefix . 'routiz_notifications';

    // Delete all notifications for the specified user from the table
    $wpdb->delete($table_name, array('user_id' => $user_id));
}



}
