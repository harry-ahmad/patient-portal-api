<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Dashboard_model');
    }
    
//////////////////////////------- For Dashboard/.php --------/////////////////////////////////

public function dashboard(){
	$post = get_request_body();
    $pid = $this->user_id;
    //Allergy
		
	$result1 = $this->get_dashboard_data($post['type'],$pid);
	echo json_encode($result1);

}

public function get_dashboard_data($type,$pid){
    $query = $query2 = "";
	if ($type == 'allergy') {
		$query = "select name from patient_allergies where endDate IS NULL and pid = ".$pid." limit 10";
		//exit($query);  
	} else if ($type == 'medical') {
		$query = "select *, IF(active = '1','Yes','No') as activeMed from medicalhx where active = 1 and pid=".$pid." limit 10";		
	} else if ($type == 'bp') {
		$query = "select *,MonthName(date) from form_vitals where pid = ".$pid." and date != 0 order by date asc limit 10";
		$query2 = "select *,MonthName(date) from vitals_readings where pid = ".$pid." and vital_type = 'bp' order by date asc limit 10";
	} else if ($type == 'bmi') {
		$query = "select *,MonthName(date) from form_vitals where pid = ".$pid." and date != 0 order by date asc limit 5";
		$query2 = "select *,MonthName(date) from vitals_readings where pid = ".$pid." and vital_type = 'BMI' order by date asc limit 5";
	} else if ($type == 'cholestrol') {
		$query = "select * from patient_cholesterol_data a where a.pid = ".$pid." order by a.cholesterol_id desc limit 5";
	}

	$result = [];
	$result1 = $this->dynamic_query_execution($query);	
	// array_merge($result, $result1);
	if($query2 <> ""){
		$result2 = $this->dynamic_query_execution($query2);			
	if($result2 <> ""){
		$result1 = array_merge($result1, $result2);
	}			
	}
	return $result1;
}

public function dynamic_query_execution($qry)
{
    $sql = $qry;
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }

}

public function downloadPDF(){
	print_r($this->user_id);exit;
}


}