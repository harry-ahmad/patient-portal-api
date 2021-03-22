<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bmi_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Bmi_model');

    }
    
//////////////////////////------- For bmi/list.php --------/////////////////////////////////
public function bmi_list()
{

    $rows = array();
    if (isset($_REQUEST['dataID']) && $_REQUEST['dataID'] <>""){
        $result = $this->Bmi_model->getDataFrom_form_vitals($this->userid,$_REQUEST['dataID']);
         if($result){
            $r = $result;
        }else{
            $result = $this->Bmi_model->getDataFrom_vitals_readings($this->userid,$_REQUEST['dataID']);
            $r = $result;
        }
        
        $rows[] = $r;
    }else{
        
        // $result = $db->executeSQL("SELECT *, vital_id as id FROM form_vitals where pid = ".$pid);
        $result = $this->Bmi_model->getDirectData_form_vitals($this->userid);
        while ($r = $result){
            
            $rows[] = $r;
        }//End While
      
    }
    
    echo json_encode($rows);

}
//////////////////////////------- For bmi/save.php --------/////////////////////////////////
public function bmi_save()
{
    $request = get_request_body();
	$request["patientId"] = $this->userid;
	$output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = "vitals";
	$change_type = $request['editID'];

			///////------- For Adding Records
			
            $result = $this->Bmi_model->addData_patient_portal_changes($this->userid,$table_name,$change_type,$jsonData,$request['hx_id']);
			if($result ){
				echo compileResponse(300, "<h1>Your Message has been sent to the clinic.<br/> please wait for them to review and respond.</h1>");
			}else{
				echo compileResponse(500, "Bad Parameters!!!");
			}
			///////------- For Adding Records

}

//////////////////////////------- For bmi/save.php --------/////////////////////////////////


}    