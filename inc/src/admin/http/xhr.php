<?php

namespace Routiz\Inc\Src\Admin\Http;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Xhr {

	use \Routiz\Inc\Src\Traits\Singleton;

    public function __construct() {

		if( wp_doing_ajax() ) {

            if( isset( $_REQUEST['action'] ) ) {

				$this->display_errors();

		        add_action( 'init', [ $this, 'forgery' ] );


				new Endpoints\Endpoint_Field_Repeater();
				new Endpoints\Endpoint_Field_Icon();
				new Endpoints\Endpoint_Field_Upload();
				new Endpoints\Endpoint_Field_Term();
				new Endpoints\Endpoint_Field_Autocomplete();

				new Endpoints\Endpoint_Dynamic_Explore();
				new Endpoints\Endpoint_Dynamic_Search();

				new Endpoints\Endpoint_Submission_Next();
				new Endpoints\Endpoint_Add_Booking();
				new Endpoints\Endpoint_Add_Booking_Hour();
				new Endpoints\Endpoint_Add_Appointment();
				new Endpoints\Endpoint_Purchase();
				new Endpoints\Endpoint_Notifications_Mark_Read();
				new Endpoints\Endpoint_Notifications_Clear_All();
				new Endpoints\Endpoint_Listing_Edit();
				new Endpoints\Endpoint_Listing_Update();
				new Endpoints\Endpoint_Listing_Edit_Booking_Calendar();
				new Endpoints\Endpoint_Listing_Edit_Booking_ICal();
				new Endpoints\Endpoint_Listing_Calendar_Toggle_Day();
				new Endpoints\Endpoint_Get_Promotions();
				new Endpoints\Endpoint_Promote_Listing();
				new Endpoints\Endpoint_Send_Report();
				new Endpoints\Endpoint_Request_Payout();

				new Endpoints\Endpoint_Get_Favorites();
				new Endpoints\Endpoint_Add_Favorite();

				new Endpoints\Endpoint_Get_Conversation();
				new Endpoints\Endpoint_Send_Message();

				new Endpoints\Endpoint_Action_Application_Form();
				new Endpoints\Endpoint_Action_Application_Send();
				new Endpoints\Endpoint_Action_Booking_Pricing();
				new Endpoints\Endpoint_Action_Booking_Hour_Pricing();
				new Endpoints\Endpoint_Action_Purchase_Pricing();
				new Endpoints\Endpoint_Action_Get_Appointments();
				new Endpoints\Endpoint_Appointments_Get_More_Dates();

				new Endpoints\Endpoint_Action_Claim();
				new Endpoints\Endpoint_Action_Claim_Send();

				new Endpoints\Endpoint_Get_Review();
				new Endpoints\Endpoint_Submit_Review();
				new Endpoints\Endpoint_Get_Comment();
				new Endpoints\Endpoint_Load_More_Reviews();

				new Endpoints\Endpoint_Get_Review_Reply();
				new Endpoints\Endpoint_Submit_Review_Reply();

				new Endpoints\Endpoint_Account_Get_Chart_Views();

				new Endpoints\Endpoint_Signup();
				new Endpoints\Endpoint_Signin_Standard();
				new Endpoints\Endpoint_Signin_Reset_Password();
				new Endpoints\Endpoint_Signin_Facebook();
				new Endpoints\Endpoint_Signin_Google();

				new Endpoints\Endpoint_Account_Entry();

				new Endpoints\Endpoint_Demo_Import();

				new Endpoints\Endpoint_Dev_Export_Options();

				new Endpoints\Endpoint_Upload_Icon_Set();

				new Endpoints\Endpoint_Trigger_Webhook();

            }
        }
	}

    function display_errors() {

		if ( defined( 'WP_DEBUG' ) and WP_DEBUG === true ) {
			@ini_set( 'display_errors', 1 );
		}

    }

	public function forgery() {

		global $rz_explore;
		$rz_explore = \Routiz\Inc\Src\Explore\Explore::instance();

	}
}
