<?php
///----General Function for  EMAIL(Mails sends from admin)
if(!function_exists("send_email_func")){
	function send_email_func($to, $msg, $subject, $extra_params = array() ){
		$CI = & get_instance();
		$config = array(
		'protocol' 	=>  "smtp",
		'smtp_host'	=>	"ssl://smtp.googlemail.com",
		'smtp_port'	=>	465,
		'smtp_user'	=>	"hnas2011@gmail.com",
		'smtp_pass'	=>	"hi.ahmed",
		'mailtype'	=>	"html",
		'newline'	=>	"\r\n",
		'charset' 	=> "utf-8"
		);
		$CI->load->library('email',$config);
		//$CI->email->set_newline("\r\n");
		$CI->email->from('hnas2011@gmail.com','VERAUT');
		$CI->email->to($to);
		$CI->email->subject($subject);
		$CI->email->message(nl2br($msg));
		/* if(sizeof($extra_params) > 0){
			if(isset($extra_params["cc"])){
				$CI->email->cc($extra_params["cc"]);
			}

			if(isset($extra_params["bcc"])){
				$CI->email->bcc($extra_params["bcc"]);
			}

			if(isset($extra_params["attachments"])){
				foreach($extra_params["attachments"] as $attachment){
					$CI->email->attach($attachment);
				}
			}
		} */
		if($CI->email->send()){
			return "1";
		}else{
			show_error($CI->email->print_debugger());	
			return "-1";
		}
	}
}
if(!function_exists("printr")){
	function printr($data = array() ){
		echo "<pre>";print_r($data);exit;
	}
}

if(!function_exists("org_lang")){
	function org_lang($lang){
		
		$languages = array("en"=>"English");
		
		if(isset($languages[$lang])){
			return $languages[$lang];
		}else{
			return "English";
		}
	}
}

/**
 * Encrypt and decrypt
 * @param string $string string to be encrypted/decrypted
 * @param string $action what to do with this? e for encrypt, d for decrypt
 */
if(!function_exists("crypt_dcrypt")){ 
	function crypt_dcrypt( $string, $action = 'e' ) {
		// you may change these values to your own
		$secret_key = 'M@#TTOO&*hj88_-##^2';
		$secret_iv = '&^%YYUHfr%tII#UOT2';

		$output = false;
		$encrypt_method = "AES-256-CBC";
		$key = hash( 'sha256', $secret_key );
		$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

		if( $action == 'e' ) {
			$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
		}
		else if( $action == 'd' ){
			$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
		}
		return $output;
	}
}

if(!function_exists("handleLog")){ 
	// Update Log in database
	function handleLog($data){
		$CI = & get_instance();
		$columns = implode(",",array_values(array_keys($data)));
		$values = "'".implode("','",array_values($data))."'";
		$sql = "INSERT INTO cron_log (".$columns.") VALUES ($values) ";
		$CI->db->query($sql);
		$notifyData = $data;
		if($notifyData['status'] == "fail"){
			$notifyData['severity'] = 2;
			$notifyData['type'] = "meter_reading";
			unset($notifyData['status'], $notifyData['file_name']);
			$CI->db->insert('notifications', $notifyData);
		}
		//echo $CI->db->last_query(); exit;
		
		if($data['ftp_id'] > 0)
			 $CI->db->query("UPDATE ftp_servers set in_process = '0' , next_scan_time = ADDTIME(NOW(), SEC_TO_TIME(ftp_scan_interval*60) ) WHERE ftp_server_id = '".$data['ftp_id']."' ");
			//echo $CI->db->last_query();
		if($data['status'] == 'success'){
			$CI->db->query("COMMIT");
		}
		//echo $CI->db->last_query();
	}
}

if(!function_exists("checkTimeTrans")){ 
	// Update Log in database
	function checkTimeTrans($meter_id){
		$CI = & get_instance();
		$CI->db->select('mp.*');
		$CI->db->from('measurement_point_details mpd');
		$CI->db->join('measurement_point mp','mpd.measure_point_id = mp.measure_id', "INNER");
		$CI->db->where('mpd.details_id', $meter_id);
		$data = $CI->db->get();
		//echo $CI->db->last_query(); exit;
		if($data->num_rows() > 0){
			return $data->row_array();
		}else{
			return false;
		}
	}
}

if(!function_exists("getMeterName")){ 
	// Update Log in database
	function getMeterName($meter_id){
		$CI = & get_instance();
		$CI->db->select('*');
		$CI->db->from('measurement_point_details');
		$CI->db->where('details_id', $meter_id);
		$data = $CI->db->get();
		//echo $CI->db->last_query(); exit;
		if($data->num_rows() > 0){
			return $data->row_array();
		}else{
			return false;
		}
	}
}

if(!function_exists("checkInTrans")){ 
	function checkInTrans($rdate, $rtime){
		$CI = & get_instance();
		//$r =[];
		$r ='';
		//$date =  date('Y-m-d')." ".substr(date('H:i:s'),0,5);
		$date =  $rdate." ".substr($rtime,0,5);
		$year = explode('-',$date)[0];
		 $date2 =  date('Y-m-d',strtotime('last Sunday', strtotime($year.'-04-01')))." 02:00";
		 $date3 =  date('Y-m-d',strtotime('last Sunday', strtotime($year.'-11-01')))." 02:00";
		if($date >= $date2 && $date < $date3){
			//echo "summer transition From ".$date2 ." TO ".$date3;
			//in summer status of transition will be 1
			$r = "summer";
			/* $r['date0'] = 'DATE_FORMAT(vbv.reading_time_trans, "%Y-%m-%d")';
			$r['date1'] = 'DATE_FORMAT(vcv.reading_time_trans, "%Y-%m-%d")';
			
			$r['time0'] = 'DATE_FORMAT(vbv.reading_time_trans, "%H:%i:%s")';
			$r['time1'] = 'DATE_FORMAT(vcv.reading_time_trans, "%H:%i:%s")'; */
		}else{
			//echo "winter transition From ".$date2 ." TO ".$date3;
			//in winter status of transition will be 0
			$r = "winter";
			/* $r['date0'] = 'vbv.reading_date';
			$r['date1'] = 'vcv.reading_date';
			
			$r['time0'] = 'vbv.reading_time';
			$r['time1'] = 'vcv.reading_time'; */
		}
		return $r;	
		//echo $this->db->last_query(); exit;
	}
}

function list_options($id){
	$CI = & get_instance();
	$result = $CI->db->query("Select option_value FROM list_options WHERE list_id = '".$id."'  ");
	return $result->row_array();
}

if(!function_exists("myDate")){ 
	function myDate($pressDate){
		$myDate = date("j M, Y, g:i A", strtotime($pressDate));
		return $myDate;

	}
}		
if(!function_exists("converDate")){ 
	function converDate($pressDate){
		$myDate = date('Y-m-d H:i:s', strtotime($pressDate));
			return $myDate;

	}
}		
if(!function_exists("compileResponse")){ 
	function compileResponse($code, $msg){
		if ($code == 200)
				$msg = '{"code":"' . $code . '","message":"Updated Successfully!"}';
			else
				$msg = '{"code":"' . $code . '","message":"' . $msg . '"}';
			return $msg;

	}
}		
?>