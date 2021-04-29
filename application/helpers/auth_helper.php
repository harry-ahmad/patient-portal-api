<?php
/**
 * Provides authorized access to the system for a user, based on the provided
 * credentials, using a query to the database. If the authorization is
 * successful, a unique JSON Web Token is generated and stored in a cookie.
 * @param  $table - The database table to query.
 * @param $fields - An array of names for the fields to be requested.
 * @param $username_field - The name of the username field.
 * @param $password_field - The name of the password field.
 * @param $id_field - The name of the id field.
 * @param $username_value - The value of the username field.
 * @param $password_value - The value of the password field.
 * @param $service_name - The name of the service.
 * @param $cookie_name - The name of the cookie used to store the authorization
 *  token.
 * @return associative array
 */
function authorize($table, $fields, $username_field, $password_field, $id_field, $username_value, $password_value, $service_name, $cookie_name){
	$CI = & get_instance();
  // Load the appropriate helpers
  $ci =& get_instance(); $ci->load->helper('error_code');
  $password_field = str_replace("`", "", $password_field);
  $id_field = str_replace("`", "", $id_field);
  $user = $CI->db->query("SELECT ".$fields." FROM ".$table." WHERE ".$username_field."='".$username_value."' " );
  if($user->num_rows() == 0)
    return array(
      "code" => BAD_CREDENTIALS,
      "message"=>"No user with this username."
    );
	$user = $user->result_array();
  if(password_verify_match($password_value, $user[0][$password_field])){
    generate_jwt_cookie($username_value, $user[0][$id_field],  $service_name, $cookie_name);
    unset($user[0][$password_field]);
	$user[0]["code"] = 200;
	// user_log($user);
    return $user[0];
  }
  else
    return array(
      "code" => BAD_CREDENTIALS,
      "message"=>"Password is incorrect."
    );
}

function password_verify_match($password, $hash)
	{
		$secret_key = 'M@#TTOO&*hj88_-##^2';
		$secret_iv = '&^%YYUHfr%tII#UOT2';

		$output = false;
		$encrypt_method = "AES-256-CBC";
		$key = hash( 'sha256', $secret_key );
		$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
		$qEncoded = base64_encode( openssl_encrypt( $password, $encrypt_method, $key, 0, $iv ) );
		// return( $qEncoded );
		if($qEncoded == $hash){
			return true;
		}
		return false;
	}

function generate_jwt($password){  
  $secret_key = 'M@#TTOO&*hj88_-##^2';
  $secret_iv = '&^%YYUHfr%tII#UOT2';

  $output = false;
  $encrypt_method = "AES-256-CBC";
  $key = hash( 'sha256', $secret_key );
  $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
  $qEncoded = base64_encode( openssl_encrypt( $password, $encrypt_method, $key, 0, $iv ) );
  return $qEncoded;
}

function user_log($user_detail){
	$CI = & get_instance();
	$ua = getBrowser();
	$log['ip_address'] 		= $_SERVER['REMOTE_ADDR'];
	$log['browser'] 		= $ua['name'];
	$log['browser_version'] = $ua['version'];
	$log['platform']		= $ua['platform'];
	$log['user_id'] 		= $user_detail[0]['pid'];
	$CI->db->insert('login_log', $log);
}

function getBrowser() 
{
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    }
    elseif(preg_match('/OPR/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 


/**
 * Generates a unique JSON Web Token from the values provided.
 * @param $username_value - The user's unique username.
 * @param $id_value - The user's unique id.
 * @param $service_name - The name of the service.
 * @param $cookie_name - The name of the cookie used to store the authorization
 *  token.
 * @return void
 */
function generate_jwt_cookie($username_value, $id_value, $service_name, $cookie_name){
  $secret = parse_ini_file(__DIR__.'/../../config.ini')["secret"];

  $timestamp = date_timestamp_get(date_create());
  mt_srand(intval(substr($timestamp,-16,12)/substr(join(array_map(function ($n) { return sprintf('%03d', $n); }, unpack('C*', $secret))),0,2)));
  $stamp_validator = mt_rand();

  $token = array(
    "iat" => $timestamp,
    "chk" => $stamp_validator,
    "username" => $username_value,
    "id" => $id_value,
    "iss" => $service_name
  );
  $cookie = array (
    "id" => $id_value,
    "token" => jwt_encode($token, $secret)
  );
  // Change the first NULL below to set a domain, change the second NULL below
  // to make this only transmit over HTTPS
  setcookie($cookie_name, json_encode($cookie), 0, "/", NULL, NULL, TRUE );
}

/**
 * Regenerates a unique JSON Web Token from the values provided. Will return a
 * message if no existing cookie is found.
 * @param $service_name - The name of the service.
 * @param $cookie_name - The name of the cookie used to store the authorization
 *  token.
 * @return associative array
 */
function regenerate_jwt_cookie($service_name, $cookie_name){
  // Load the appropriate helpers
  $ci =& get_instance(); $ci->load->helper('jwt'); $ci->load->helper('error_code');
  $secret = parse_ini_file(__DIR__.'/../../config.ini')["secret"];

  if(!isset($_COOKIE[$cookie_name]))
    return array(
      "code" => NO_COOKIE,
      "message" => "Token not found."
    );

  $cookie_contents = json_decode($_COOKIE[$cookie_name], true);
  $token = (array)jwt_decode($cookie_contents["token"], $secret);

  generate_jwt_cookie($token["username"], $token["id"], $service_name, $cookie_name);
  return array(
    "code" => SUCCESS,
    "message" => "Token regenerated successfully."
  );
}

/**
 * Regenerates a unique JSON Web Token from the values provided. Will return a
 * message if no existing cookie is found.
 * @param $service_name - The name of the service.
 * @param $cookie_name - The name of the cookie used to store the authorization
 *  token.
 * @return associative array
 */
function remove_jwt_cookie($service_name, $cookie_name){
  // Load the appropriate helpers
  $ci =& get_instance(); $ci->load->helper('jwt'); $ci->load->helper('error_code');
  $secret = parse_ini_file(__DIR__.'/../../config.ini')["secret"];

  if(!isset($_COOKIE[$cookie_name]))
    return array(
      "code" => NO_COOKIE,
      "message" => "Token not found."
    );

  $cookie_contents = json_decode($_COOKIE[$cookie_name], true);
  $token = (array)jwt_decode($cookie_contents["token"], $secret);

  // generate_jwt_cookie($token["username"], $token["id"], $service_name, $cookie_name);
  // return array(
    // "code" => SUCCESS,
    // "message" => "Token regenerated successfully."
  // );
}

/**
 * Checks the validity of a unique JSON Web Token.
 * @param $service_name - The name of the service.
 * @param $cookie_name - The name of the cookie used to store the authorization
 *  token.
 * @return true if the cookie is found and the JWT is valid, false otherwise
 */
function check_jwt_cookie($service_name, $cookie_name){
  // Load the appropriate helpers
  $ci =& get_instance(); $ci->load->helper('jwt');
  $secret = parse_ini_file(__DIR__.'/../../config.ini')["secret"];
  if(!isset($_COOKIE[$cookie_name]))
    return false;

  $cookie_contents = json_decode($_COOKIE[$cookie_name], true);
  $token = (array)jwt_decode($cookie_contents["token"], $secret);

  if($token["iss"] != $service_name)
    return false;

  if($token["id"] != $cookie_contents["id"])
    return false;

  mt_srand(intval(substr($token["iat"],-16,12)/substr(join(array_map(function ($n) { return sprintf('%03d', $n); }, unpack('C*', $secret))),0,2)));
  $stamp_validator = mt_rand();
  if($stamp_validator != $token["chk"])
    return false;

  return true;
}

 function logout_log($service_name){
	 $data = get_jwt_data($service_name."_token");
	 //printr($data);
	 $ci =& get_instance();
	 $result = $ci->db->query("Select MAX(id)as id FROM login_log WHERE user_id = '".$data['id']."' ");
	 if($result->num_rows() > 0){
		 $id = $result->row_array();
		 $ci->db->where("id", $id['id']);
		 $ci->db->set('logout_time', 'NOW()', FALSE);
		 $ci->db->update("login_log");
	 }
 }
/**
 * Gets the data stored in a unique JSON Web Token.
 * @param $cookie_name - The name of the cookie used to store the authorization
 *  token.
 * @return associative array
 */
function get_jwt_data($cookie_name){
  // Load the appropriate helpers
  $ci =& get_instance();
  $ci->load->helper('jwt');
  $ci->load->helper('error_code');
  $secret = parse_ini_file(__DIR__.'/../../config.ini')["secret"];

  if(!isset($_COOKIE[$cookie_name]))
    return array(
      "code" => NO_COOKIE,
      "message" => "Token not found."
    );

  $cookie_contents = json_decode($_COOKIE[$cookie_name], true);
  return (array)jwt_decode($cookie_contents["token"], $secret);
}
?>
