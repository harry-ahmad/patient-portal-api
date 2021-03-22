
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cholesterol_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Cholesterol_model');

    }
    
//////////////////////////------- For Cholesterol/list.php --------/////////////////////////////////
public function cholesterol_list()
{

    $rows = array();
    if (isset($_REQUEST['dataID']) && $_REQUEST['dataID'] <>""){
        $result = $this->Cholesterol_model->getDataFrom_patient_cholesterol_data($this->userid,$_REQUEST['dataID']);
         if($result){
            $r = $result;
            $r['datetime'] = myDate($r['datetime']);
            $rows[] = $r;
        }  
        
    }
    else{
        
        // $result = $db->executeSQL("SELECT *, vital_id as id FROM form_vitals where pid = ".$pid);
        $result = $this->Cholesterol_model->getDirectData_patient_cholesterol_data($this->userid);
        while ($r = $result){
            $myDateTimeExplode = explode(' ',$r['datetime']);
            $r['mydatetime'] = $myDateTimeExplode[0].'T'.$myDateTimeExplode[1].'Z';
            $r['datetime'] = myDate($r['datetime']);
            $rows[] = $r;
        }//End While
      
    }
    
    echo json_encode($rows);

}
//////////////////////////------- For Cholesterol/save.php --------/////////////////////////////////
public function cholesterol_save()
{
    $postdata = get_request_body();
    $request = json_decode($postdata, true);
	if($request[datetime] <> ""){
		$myDate = converDate($request[datetime]);
	}else{
		$myDate = date("Y-m-d H:i:s");
    }
    	///////------- For Adding Records
            $result = $this->Cholesterol_model->addData_patient_cholesterol_data($this->userid,$request[ldl],$request[hdl],$request[triglycerides],$request[total_cholesterol],$myDate,$request[note]);
			echo compileResponse(200, "Added Successfully!");
			///////------- For Adding Records

}
//////////////////////////------- For Cholesterol/save.php --------/////////////////////////////////


}    