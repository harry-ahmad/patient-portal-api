<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OTP_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		// $this->load->model('Dashboard_model');
    }

    public function send_OTP(){      
        $request = get_request_body();
       
        $data = array(
            'otp' => $request['token'],
            );  
		$this->db->where('pid',$this->user_id);
		$this->db->update('patient_data', $data);
        if($this->db->affected_rows() > 0){
            // $otpService = send_sms_helper($request['ph_no'],"Your OTP for Patient Portal is " . $request['token'])
            response(array(
                "code" => SUCCESS,
                "message" => "Code has been sent to " . $request['ph_no']
            ));
        }else{
            response(array(
                "code" => BAD_CREDENTIALS,
                "message" => 'BAD CREDENTIALS'
            ), true);
        }        
	}

    public function verifyOTP(){
        $request = get_request_body();
		$this->db->select('*');
        $this->db->where('otp',$request['otp']);
		$this->db->from('patient_data');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $data = array(
                'otp' => 0,
                );  
            $this->db->where('pid',$this->user_id);
            $this->db->update('patient_data', $data);
            if($this->db->affected_rows() > 0){
                // $otpService = send_sms_helper($request['ph_no'],"Your OTP for Patient Portal is " . $request['token'])
                $row = $query->row_array();
                if ($row['system_password'] > 0) {
                    $status = "2";
                }else{
                    $status = "1";
                }
                response(["status" => $status, "data" => $row]);
            }else{
                response(array(
					"code" => BAD_CREDENTIALS,
					"message" => 'BAD CREDENTIALS'
				), true);
            }  
        }else{
            response(array(
                "code" => BAD_CREDENTIALS,
                "message" => 'Invalid OTP!!!'
            ), true);          
        }
    }

    public function changePassword(){
        $request = get_request_body();
        // $password = generate_jwt($request['password'])        
        $secret_key = 'M@#TTOO&*hj88_-##^2';
        $secret_iv = '&^%YYUHfr%tII#UOT2';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        $password = base64_encode( openssl_encrypt( $request['password'], $encrypt_method, $key, 0, $iv ) );
        $data = array(
            'system_password' => '1',
            'portal_password' => $password
            );  
		$this->db->where('pid',$this->user_id);
		$this->db->update('patient_data', $data);                    
        if($this->db->trans_status() == 1){
            response(array(
                "code" => SUCCESS,
                "message" => "Password Successfully Changed!"
            ));
        }else{
            response(array(
                "code" => BAD_CREDENTIALS,
                "message" => 'BAD CREDENTIALS'
            ), true);
        }
    }

}