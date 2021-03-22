<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller {
	public function __construct(){
		parent::__construct();
		// respond to preflights
		// Allow from any origin
		$this->user_id = '0';
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			// Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
			// you want to allow, and if so:
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header("Access-Control-Allow-Credentials: true");
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}

		// Access-Control headers are received during OPTIONS requests
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				// may also be using PUT, PATCH, HEAD etc
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
			exit(0);
		}

		$this->users = array(
			"table" => "`patient_data`",
			"create_fields" => ["`portal_username`", "`portal_password`"],
			"create_types" => "sss",
			"read_fields" => "`portal_username`, `pid` AS `id`",
			"read_key" => "`portal_username`",
			"update_fields" => ["`portal_username`"],
			"update_key" => "`portal_username`",
			"delete_key" => "`portal_username`"
		  );
		  
		  $this->auth = array(
			"table" => "`patient_data`",
			"fields" => "*",
			"username_field" => "`portal_username`",
			"password_field" => "`portal_password`",
			"id_field" => "`pid`",
			"service_name" => "patient",
			"cookie_name" => "patient_token"
		  );

		$cookie_data = check_jwt_cookie($this->auth["service_name"], $this->auth["cookie_name"]);
		
		if(!$cookie_data){
			$resp = array(
			  "code" => BAD_CREDENTIALS,
			  "message" => $this->lang->line('expired_token_error')
			);
			response($resp);
			exit(0);
		}else{
			$data = get_jwt_data($this->auth["cookie_name"]);
			$this->user_id = $data['id'];
			
		}
	}
}