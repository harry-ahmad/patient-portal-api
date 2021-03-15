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
function response($array = array(), $print = true){
	$encode = json_encode($array);
	if($print){
		echo $encode;
	}else{
		return $encode;;
	}
}
?>
