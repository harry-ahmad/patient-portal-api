<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medical_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Medical_model');

    }
    
//////////////////////////------- For Medical/deleteDx.php --------/////////////////////////////////
public function deleteDx()
{
    $request = get_request_body();
	$request["patientId"] = $this->userid;
    $output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = "medicalhx";
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

//////////////////////////------- For Medical/deleteDx.php --------/////////////////////////////////
//////////////////////////------- For Medical/deleteRx.php --------/////////////////////////////////
public function deleteRx()
{
    $request = get_request_body();
	$request["patientId"] = $this->userid;
    $output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = "planned_meds";
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

//////////////////////////------- For Medical/deleteRx.php --------/////////////////////////////////
//////////////////////////------- For Medical/editDx.php --------/////////////////////////////////
public function editDx()
{
    $request = get_request_body();
	$request["patientId"] = $this->userid;
    $unsetKeys = array('No', 'Yes', 'active', 'comments', 'diagnose', 'diagnose_hid', 'end_date', 'start_date', 'text_diagnose', 'update_version');
	$request = array_diff_key($request, array_flip($unsetKeys));
	$output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = "planned_meds";
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

//////////////////////////------- For Medical/editDx.php --------/////////////////////////////////

//////////////////////////------- For Medical/list.php --------/////////////////////////////////
public function medical_list()
{

    $rows = array();
    if (!isset($_REQUEST['dxID'])){
        $result = $this->Medical_model->getDataFrom_medicalhx($this->userid);
        while ($r = $result)
				$rows[] = $r;
        }else{
            $result = $this->Medical_model->getDataFromJoin_medicalhx($_REQUEST['dxID']);
            while ($r = $result)
            $rows[] = $r;
        }
        
    echo json_encode($rows);

}
//////////////////////////------- For Medical/save.php --------/////////////////////////////////
public function medical_save()
{
    $request = get_request_body();
	$request["patientId"] = $this->userid;
    $output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = "medicalhx";
	$change_type = $request['editID'];

			///////------- For Adding Records
			
            $result = $this->Medical_model->addData_patient_portal_changes($this->userid,$table_name,$change_type,$jsonData,$request['hx_id']);
			if($result ){
				echo compileResponse(300, "<h1>Your Message has been sent to the clinic.<br/> please wait for them to review and respond.</h1>");
			}else{
				echo compileResponse(500, "Bad Parameters!!!");
			}
			///////------- For Adding Records

}

//////////////////////////------- For Medical/save.php --------/////////////////////////////////


}    