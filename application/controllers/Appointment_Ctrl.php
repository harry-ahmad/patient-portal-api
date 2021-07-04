<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointment_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Appointment_model');
		$this->load->model('Patient_Portal_Changes_model');

    } 
//////////////////////////------- For appointment/calender.php --------/////////////////////////////////
public function calender(){

    if(isset($_POST["type"]) && $_POST["type"] == "fetch"){
		$myCurrentDate = date('Y-m-d');
		$result = $this->Appointment_model->get_postcalendar_events($myCurrentDate,$this->user_id);
		while ($myRows = $result){
			$e = array();
			$e['id'] = $myRows['eid'];
			$e['catid'] = $myRows['catid'];
			$e['title'] = $myRows['title'];
			$e['start'] = $myRows['event_date'].'T'.$myRows['start_time'];
			$e['end'] = $myRows['event_date'].'T'.$myRows['end_time'];
			$e['startTime'] = $myRows['start_time'];
			$e['endTime'] = $myRows['end_time'];
			$allday = false;
			$e['allDay'] = $allday;
			array_push($rows, $e);
		}
		echo json_encode($rows);
		exit();
	}else{
		exit('Bad Parameters!!!');
    }
}
//////////////////////////------- For appointment/calender.php --------/////////////////////////////////


//////////////////////////------- For appointment/calenderevents.php --------/////////////////////////////////

public function calenderevents(){

$request = get_request_body();
$request["patientId"] = $this->userid; //$pid
$output = str_replace(array("\r\n", "\n", "\r"),'',$request);
$jsonData = json_encode($output);
$table_name = "postcalendar_events";
$change_type = $request['editID'];

///////------- For Adding Records
$result = $this->Appointment_model->AddRecordsTo_patient_portal_changes($this->user_id,$table_name,$change_type,$jsonData,$request['hx_id']);

if($result){
    echo compileResponse(300, "<h1>Your Message has been sent to the clinic.<br/> please wait for them to review and respond.</h1>");
}else{
    echo compileResponse(500, "Bad Parameters!!!");
}


}
//////////////////////////------- For appointment/calenderevents.php --------/////////////////////////////////

//////////////////////////------- For appointment/list.php --------/////////////////////////////////
public function appoitment_list() {
// $query = "SELECT 
// concat('{title:\"',e.title,'\",start:\"',e.event_date,'T',e.start_time,'\",end:\"',e.event_date,'T',e.end_time,'\"}') event 
// FROM 
// postcalendar_events e WHERE CAST(e.time as Date) = CURDATE() and patient_id=".$pid;
$body = get_request_body();
$result = array();
$result['last_provider'] = $body['providerId'];
if($result['last_provider'] == ""){
	$get_last_provider = $this->Appointment_model->get_last_provider($this->user_id);
}
if(isset($get_last_provider['provider_id']))
	$result['last_provider'] = $get_last_provider['provider_id'];

$appoitment_list = $this->Appointment_model->appoitment_list($this->user_id,$result['last_provider'], $body['date']);
$pending_list = $this->Appointment_model->pending_list($this->user_id,$result['last_provider'], $body['date']);
$result['appoitment_list'] = $appoitment_list;
$result['pending_list'] = $pending_list;

echo json_encode($result);
}
//////////////////////////------- For appointment/list.php --------/////////////////////////////////

public function appoitment_save()
{
    $request = get_request_body();
	$request["pid"] = $this->user_id;
    $output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);	
    $table_name = "postcalendar_events";
	$change_type = $request['editID'];
			///////------- For Adding Records
			
            $result = $this->Appointment_model->AddRecordsTo_patient_portal_changes($this->user_id,$table_name,$change_type,$jsonData,$request['hx_id']);
			if($result ){
				echo compileResponse(300, "Your Message has been sent to the clinic. Please wait for them to review and respond.");
			}else{
				echo compileResponse(500, "Bad Parameters!!!");
			}
			///////------- For Adding Records

}
///////------- For Search Providers
public function appoitment_search()
{
	$post = get_request_body();
	$search_tbl = 'providers';
	$search_term         = $post["searchValue"];
	$select_qry     = "provider_id, title, first_name, last_name, clinic_name";
	$like_search    = "(first_name LIKE '%".$search_term."%' OR last_name LIKE '%".$search_term."%' OR clinic_name LIKE '%".$search_term."%')";	
	$order_by       = "ORDER BY CASE WHEN LEFT(TRIM(first_name), LENGTH('".$search_term."')) = '" . $search_term . "' THEN 1 ELSE 2 END";
	$sql = "SELECT $select_qry "
						. "FROM  $search_tbl WHERE $like_search "
						. "$order_by ";			
	$query = $this->db->query($sql);
	$result = $query->result_array($query);                                         
	echo json_encode($result);
}
///////------- For Search Providers
///////------- Get Appoitment times-------/////

public function appoitment_time(){
	$post = get_request_body();
	$appoitment_time = $this->Appointment_model->appoitment_time($post);
	$bookedTime = $this->Appointment_model->available_time($post['provider_id'],$post['date']);
	$result['appoitment_time'] = $appoitment_time;
	$result['bookedTime'] = $bookedTime;
	// array_push($appoitment_time, $result);
	// array_push($bookedTime, $result1);
	// print_r($result);		
	// if($result != 'empty'){
	// 	$nextInterval = $result[0]['start_time'];		
	// 	$endInterval = array();
	// 	$counter = true;
	// 		$i = 0;
	// 	while($counter){
	// 		$obj['start_time'] = $nextInterval;
	// 		$endTime = strtotime("+".$result[0]['slot_interval']." minutes", strtotime($nextInterval));
	// 		$nextInterval = date('H:i:s', $endTime);
	// 		$obj['end_time'] = $nextInterval;			
	// 		if(date("Y-m-d") == $post['date']){
	// 			if($post['time'] <= $obj['start_time'])
	// 			array_push($endInterval, $obj);
	// 		}else{
	// 			array_push($endInterval, $obj);
	// 		}
	// 		if($nextInterval == $result[0]['end_time']){
	// 			$counter = false;
	// 			break;
	// 		}
	// 		$i++;		
	// 	}
	// 	$newTime = array();
	// 	$newEndTime = array();
	// 	$result1;
	// 	// if($bookedTime != 'empty'){
	// 		// foreach($bookedTime as $val){
	// 		// 	array_push($newTime,$val['start_time']); 
	// 		// 	array_push($newEndTime,$val['end_time']); 
	// 		// }
	// 		// $availableTime['interval'] = $result[0]['slot_interval'];
	// 		// $availableTime['start_time'] = array_diff($timeIntervals, $newTime);
	// 		// $availableTime = array_diff($endInterval, $bookedTime);
	// 		$availableTime = array_diff(array_map('serialize',$endInterval), array_map('serialize',$bookedTime));
	// 		$result1 = array_map('unserialize',$availableTime);						
			// echo json_encode($result1);
			echo json_encode($result);
		// }else{			
		// 	echo json_encode($endInterval);
		// }
	// }
}

public function provider_list(){
	$result = $this->Appointment_model->provider_list();
	echo json_encode($result);
}

public function appoitment_hours(){
	$post = get_request_body();	
	$result = $this->Appointment_model->appoitment_hours($post);
	echo json_encode($result);		
}

///////------- Get Appoitment times-------/////

}    