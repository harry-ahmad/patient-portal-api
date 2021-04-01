<?php
/**
 * Gets the body of the current request.
 */
function get_request_body(){
  $input = json_decode(file_get_contents('php://input'), true);
  $string = http_build_query($input);
  parse_str($string, $request);
  return $request;
}

/**
 * Response result 
 */
function response($array = array(), $error = false, $print = true){
	if($error){
		error_response($array);
	}
	
	$encode = json_encode($array);
	if($print){
		echo $encode;
	}else{
		return $encode;
	}
}

function error_response($array = array()){
	$sapi_type = php_sapi_name();
	$text = "";
	if (substr($sapi_type, 0, 3) == 'cgi')
		$text = "Status: ".$array['code']." ".$array['message'];
	else
		$text = "HTTP/1.1 ".$array['code']." ".$array['message'];

	header($text);
}
?>
