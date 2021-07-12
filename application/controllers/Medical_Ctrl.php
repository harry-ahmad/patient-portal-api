<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medical_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Medical_model');
		$this->load->model('Patient_Portal_Changes_model');

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
			
            $result = $this->Measurement_model->addData_patient_portal_changes($this->user_id,$table_name,$change_type,$jsonData,$request['hx_id']);
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
	$post = array();
    if($this->input->method(true) == 'POST'){
        $data = [];
        $post = get_request_body();
		if($post['search'] == "search_allergy"){
        array_push($data, array(
                "id" => "1", "reaction_id" => "2", "name" => "Rash"
            ));
            array_push($data, array(
                "id" => "2", "reaction_id" => "2", "name" => "LOL"
                ));
                array_push($data, array(
                    "id" => "3", "reaction_id" => "2", "name" => "Hives"
                    ));
        echo json_encode($data);
		}
		if($post['search'] == "search_medication_name"){
			array_push($data, array(
					"id" => "1", "reaction_id" => "2", "name" => "Name"
				));
				array_push($data, array(
					"id" => "2", "reaction_id" => "2", "name" => "Med"
					));
					array_push($data, array(	
						"id" => "3", "reaction_id" => "2", "name" => "Hives"
						));
			echo json_encode($data);
			}
        exit;
    }
	$result = "";
    // $rows = array();
    if (!isset($_REQUEST['dxID'])){
        $result = $this->Medical_model->getDataFrom_medicalhx($this->user_id);
        // while ($r = $result)
		// 		$rows[] = $r;
        }else{
            $result = $this->Medical_model->getDataFromJoin_medicalhx($_REQUEST['dxID']);
            // while ($r = $result)
            // $rows[] = $r;
        }
		$result1 = $this->Patient_Portal_Changes_model->get_patient_data('medicalhx');			
		array_push($result, $result1);    
    echo json_encode($result);

}

//////////////////////////------- For Medical/search --------/////////////////////////////////

public function medical_search()
{
	$post = get_request_body();
	if(isset($post['search']) && $post['search'] == "search_diagnose"){
		$search_tbl = 'diagnosis';
		$search_term         = $post["searchValue"];
		$select_qry     = "CONCAT(LONG_DESCRIPTION ,' (', DCODE, ')') as name, DCODE as icd, DCODE, LONG_DESCRIPTION, user_defined, id";
		$like_search    = "(LONG_DESCRIPTION LIKE '%".$search_term."%' OR DCODE LIKE '%".$search_term."%')";
		$where          = " AND active = '1' ";
		$order_by       = "ORDER BY CASE WHEN LEFT(TRIM(LONG_DESCRIPTION), LENGTH('".$search_term."')) = '" . $search_term . "' THEN 1 ELSE 2 END";
		$sql = "SELECT $select_qry "
                            . "FROM  $search_tbl WHERE $like_search $where "
                            . "$order_by ";			
		$query = $this->db->query($sql);
		$result = $query->result_array($query);                                         
		echo json_encode($result);

	}
}

//////////////////////////------- For Medical/save.php --------/////////////////////////////////
public function medical_save()
{
    $request = get_request_body();
	$request["patientId"] = $this->user_id;
    $output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = "medicalhx";
	$change_type = $request['editID'];

			///////------- For Adding Records
			
            $result = $this->Medical_model->addData_patient_portal_changes($this->user_id,$table_name,$change_type,$jsonData,$request['hx_id']);
			if($result ){
				echo compileResponse(300, "Your Message has been sent to the clinic. Please wait for them to review and respond.");
			}else{
				echo compileResponse(500, "Bad Parameters!!!");
			}
			///////------- For Adding Records

}

//////////////////////////------- For Medical/save.php --------/////////////////////////////////

public function psyc_list()
{
	$result = $this->Medical_model->psyc_list($this->user_id);
	echo json_encode($result);
}

public function gyne_list()
{
	$result = $this->Medical_model->gyne_list($this->user_id);
	echo json_encode($result);
}

public function medical_status()
{
	$request = get_request_body();
	$result = $this->Medical_model->medical_status($this->user_id, $request);
	echo json_encode($result);
}

   //////////////////////////------- For Medical/edit.php --------/////////////////////////////////
   public function medical_edit()
   {
	   $request = get_request_body();	
	   $request["patientId"] = $this->user_id;        
	   $request["datetime"] = date('Y-m-d h:i A');
	   $output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	   $jsonData = json_encode($output);
	   $table_name = $request['tb_name'];
	   $change_type = $request['editID'];    

			   ///////------- For Adding Records
			   
			   $result = $this->Medical_model->editData_patient_portal_changes($request["id"],$this->user_id,$table_name,$change_type,$jsonData,$request['hx_id']);
			   if($result ){
				   echo compileResponse(300, "Your Message has been updated and sent to the clinic. Please wait for them to review and respond.");
			   }else{
				   echo compileResponse(500, "Bad Parameters!!!");
			   }
			   ///////------- For Adding Records

   }

}    