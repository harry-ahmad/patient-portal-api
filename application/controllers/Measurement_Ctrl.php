<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Measurement_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Measurement_model');
		$this->load->model('Patient_Portal_Changes_model');

    }
    
//////////////////////////------- For Measurement/list.php --------/////////////////////////////////
public function measurement_list()
{

    $rows = array();
    if (isset($_REQUEST['dataID']) && $_REQUEST['dataID'] <>""){
        $result = $this->Measurement_model->getDataFrom_form_vitals($this->user_id,$_REQUEST['dataID']);
         if($result){
            $r = $result;
        }else{
            $result = $this->Measurement_model->getDataFrom_vitals_readings($this->user_id,$_REQUEST['dataID']);
            $r = $result;
        }
        
        $rows[] = $r;
    }else{
        
        // $result = $db->executeSQL("SELECT *, vital_id as id FROM form_vitals where pid = ".$pid);
        $result = $this->Measurement_model->getAllDataFrom_form_vitals($this->user_id);
        // while ($r = $result){
            
        //     $rows[] = $r;
        // }//End While
        $result1 = $this->Patient_Portal_Changes_model->get_patient_data('form_vitals');        
		array_push($result, $result1); 
        echo json_encode($result);
    }    

}
//////////////////////////------- For Measurement/save.php --------/////////////////////////////////
public function measurement_save()
{
    $request = get_request_body();
	$request["patientId"] = $this->user_id;
    $request["datetime"] = ($request["datetime"] == "" ? date('Y-m-d h:i A') : date('Y-m-d h:i A', strtotime($request["datetime"])));
	$output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = "vitals";
	$change_type = $request['editID'];    

			///////------- For Adding Records
			
            $result = $this->Measurement_model->addData_patient_portal_changes($this->user_id,$table_name,$change_type,$jsonData,$request['hx_id']);
			if($result ){
				echo compileResponse(300, "Your Message has been sent to the clinic. Please wait for them to review and respond.");
			}else{
				echo compileResponse(500, "Bad Parameters!!!");
			}
			///////------- For Adding Records

}

//////////////////////////------- For Measurement/edit.php --------/////////////////////////////////
public function measurement_edit()
{
    $request = get_request_body();	
	$request["patientId"] = $this->user_id;
    $request["datetime"] = ($request["datetime"] == "" ? date('Y-m-d h:i A') : date('Y-m-d h:i A', strtotime($request["datetime"])));
	$output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = $request['tb_name'];
	$change_type = $request['editID'];    

			///////------- For Adding Records
			
            $result = $this->Measurement_model->editData_patient_portal_changes($request["id"],$this->user_id,$table_name,$change_type,$jsonData,$request['hx_id']);
			if($result ){
				echo compileResponse(300, "Your Message has been updated and sent to the clinic. Please wait for them to review and respond.");
			}else{
				echo compileResponse(500, "Bad Parameters!!!");
			}
			///////------- For Adding Records

}
}    