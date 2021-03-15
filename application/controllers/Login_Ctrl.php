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
		  response(array("code" => BAD_CREDENTIALS,"message:"=> $this->lang->line('method_warning')));
		  return;
		}else{
			try{
				// header("Access-Control-Allow-Credentials: true");
				if(check_jwt_cookie($this->auth["service_name"], $this->auth["cookie_name"])){
					response(regenerate_jwt_cookie($this->auth["service_name"], $this->auth["cookie_name"]));
					return;
				} else {
					$_POST = get_request_body();
					$user_name = $_POST["username"];
					$password = $_POST["password"];
					$status = '-1';
					if ($row['system_password'] > 0) {
						$status = "2";
					}else{
						$status = "1";
					}
					response(["status" => $status, "data" => authorize($this->auth["table"], $this->auth["fields"], $this->auth["username_field"], $this->auth["password_field"], $this->auth["id_field"], $user_name , $password , $this->auth["service_name"], $this->auth["cookie_name"])]);
					return;
				}
			}catch(exception $e){
				 response(array(
						 "code" => NO_COOKIE,
						 "message" => $this->lang->line('expired_token_error')
						));
			}
		}
	}

	/////-----Login User
	public function login()
	{
		
		header('Content-Type: application/json');
		if($this->input->method(true) != 'POST'){
		  response(array("code" => BAD_CREDENTIALS,"message:"=> $this->lang->line('method_warning')));
		  return;
		}
		else{
				header("Access-Control-Allow-Credentials: true");
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				$password = $this->encryptIt($password);
				$response = $this->Login_model->login($username,$password);
				if ($response) {
					
					echo json_encode($response);
				}
				else{

					echo json_encode('-1');
				}
		}
	}
	
	
   private function encryptIt($q)
	{
		//$cryptKey	= 'qJB0rGtIn5UB1xG03efyCp';
		//$qEncoded	= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $q, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
		$secret_key = 'M@#TTOO&*hj88_-##^2';
		$secret_iv = '&^%YYUHfr%tII#UOT2';

		$output = false;
		$encrypt_method = "AES-256-CBC";
		$key = hash( 'sha256', $secret_key );
		$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
		$qEncoded = base64_encode( openssl_encrypt( $q, $encrypt_method, $key, 0, $iv ) );
		return( $qEncoded );
	}
}
