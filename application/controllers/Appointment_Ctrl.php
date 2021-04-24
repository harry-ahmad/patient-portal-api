<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointment_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Appointment_model');

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

$result = $this->Appointment_model->appoitment_list($this->user_id);

echo json_encode($result);
}
//////////////////////////------- For appointment/list.php --------/////////////////////////////////





}    