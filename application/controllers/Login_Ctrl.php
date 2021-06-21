<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_Ctrl extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Login_model');
		// print_r($_SERVER);
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		}else{
			header("Access-Control-Allow-Origin: *");
		}
		
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			header("Access-Control-Allow-Credentials: true");
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				// may also be using PUT, PATCH, HEAD etc
				header("Access-Control-Allow-Methods: GET, POST,DELETE, OPTIONS");         
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
			
	}

	public function check_login()
	{
		header('Content-Type: application/json');
		if($this->input->method(true) != 'POST'){
		  response(array("code" => BAD_CREDENTIALS,"message:"=> 'BAD_CREDENTIALS'));
		  return;
		}else{
			try{
				header("Access-Control-Allow-Credentials: true");
				// if(check_jwt_cookie($this->auth["service_name"], $this->auth["cookie_name"])){
				// 	response(regenerate_jwt_cookie($this->auth["service_name"], $this->auth["cookie_name"]));
				// 	return;
				// } else {
					$_POST = get_request_body();
					$user_name = $_POST["username"];
					$password = $_POST["password"];
					$status = '-1';
					$row = authorize($this->auth["table"], $this->auth["fields"], $this->auth["username_field"], $this->auth["password_field"], $this->auth["id_field"], $user_name , $password , $this->auth["service_name"], $this->auth["cookie_name"]);
					if(sizeOf($row) > 0){
						$digits = 4;
						$postData = $_POST;
						$postData['ph_no'] = $row['phone_cell'];
						$postData['pid'] = $row['pid'];
						$postData['token'] = random_int( 10 ** ( $digits - 1 ), ( 10 ** $digits ) - 1);
						$this->send_OTP($postData);
						if(send_sms_helper($postData['ph_no'],"Your OTP for Patient Portal is " . $postData['token'])){
							if ($row['system_password'] > 0) {
								$status = "2";
							}else{
								$status = "1";
							}
							response(["status" => $status, "data" => $row, "message" => "Code has been sent to " . $row['phone_cell']]);
							return;
						}

					}										
				// }
			}catch(exception $e){
				// print_r($e);
				 response(array(
					"code" => BAD_CREDENTIALS,
					"message" => 'BAD CREDENTIALS'
				), true);
			}
		}
	}

	public function send_OTP($request){      
        $data = array(
            'otp' => $request['token'],
            );  
		$this->db->where('pid',$request['pid']);
		$this->db->update('patient_data', $data);        
	}

	public function logout_user(){
		header("Access-Control-Allow-Credentials: true");
		if($this->input->method(true) == 'POST'){
		  if(check_jwt_cookie($this->auth["service_name"], $this->auth["cookie_name"])){
			setcookie($this->auth["cookie_name"], "", -1000, "/", NULL, NULL );
			response(array(
				"code" => SUCCESS,
				"message" => 'logout_msg_success'
			  ));
			  return;
		  }
		}else{
			response(array("code" => BAD_CREDENTIALS,"message:"=> 'BAD_CREDENTIALS'), true);
			return;
		}
	}
}
