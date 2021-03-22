<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Measurement_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Measurement_model');

    }
    
//////////////////////////------- For Measurement/list.php --------/////////////////////////////////
public function measurement_list()
{

    $rows = array();
    if (isset($_REQUEST['dataID']) && $_REQUEST['dataID'] <>""){
        $result = $this->Measurement_model->getDataFrom_form_vitals($this->userid,$_REQUEST['dataID']);
         if($result){
            $r = $result;
        }else{
            $result = $this->Measurement_model->getDataFrom_vitals_readings($this->userid,$_REQUEST['dataID']);
            $r = $result;
        }
        
        $rows[] = $r;
    }else{
        
        // $result = $db->executeSQL("SELECT *, vital_id as id FROM form_vitals where pid = ".$pid);
        $result = $this->Measurement_model->getDirectData_form_vitals($this->userid);
        while ($r = $result){
            
            $rows[] = $r;
        }//End While
      
    }
    
    echo json_encode($rows);

}
//////////////////////////------- For Measurement/save.php --------/////////////////////////////////
public function measurement_save()
{
    $request = get_request_body();
	$request["patientId"] = $this->userid;
    $request["date"] = ($request["date"] == "" ? date('Y-m-d h:i A') : date('Y-m-d h:i A', strtotime($request["date"])));
	$output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = "vitals";
	$change_type = $request['editID'];

			///////------- For Adding Records
			
            $result = $this->Measurement_model->addData_patient_portal_changes($this->userid,$table_name,$change_type,$jsonData,$request['hx_id']);
			if($result ){
				echo compileResponse(300, "<h1>Your Message has been sent to the clinic.<br/> please wait for them to review and respond.</h1>");
			}else{
				echo compileResponse(500, "Bad Parameters!!!");
			}
			///////------- For Adding Records

}

//////////////////////////------- For Measurement/save.php --------/////////////////////////////////


}    