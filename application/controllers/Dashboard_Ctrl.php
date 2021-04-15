<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Dashboard_model');

    }
    
//////////////////////////------- For Dashboard/.php --------/////////////////////////////////

public function dashboard(){
    $query = $query2 = "";
    $pid = $this->user_id;
    //Allergy
		if ($_REQUEST['type'] == 'allergy') {
			$query = "select name from patient_allergies where endDate IS NULL and pid = ".$pid." limit 10";
			//exit($query);  
		} else if ($_REQUEST['type'] == 'medical') {
			$query = "select *, IF(active = '1','Yes','No') as activeMed from medicalhx where active = 1 and pid=".$pid." limit 10";		
		} else if ($_REQUEST['type'] == 'bp') {
			$query = "select *,MonthName(date) from form_vitals where pid = ".$pid." order by date asc limit 10";
			$query2 = "select *,MonthName(date) from vitals_readings where pid = ".$pid." and vital_type = 'bp' order by date asc limit 10";
		} else if ($_REQUEST['type'] == 'bmi') {
			$query = "select *,MonthName(date) from form_vitals where pid = ".$pid." order by date asc limit 5";
			$query2 = "select *,MonthName(date) from vitals_readings where pid = ".$pid." and vital_type = 'BMI' order by date asc limit 5";
		} else if ($_REQUEST['type'] == 'cholestrol') {
			$query = "select * from patient_cholesterol_data a where a.pid = ".$pid." order by a.cholesterol_id desc limit 5";
		}

	
		$result = $this->Dashboard_model->dynamic_query_execution($query);
		$rows = array();
		print_r($rows);
		while ($r = $result)
			$rows[] = $r;

		if($query2 <> ""){
			$result = $this->Dashboard_model->dynamic_query_execution($query2);
			while ($r = $result)
				$rows[] = $r;
		}
	
		echo json_encode($rows);

}




}