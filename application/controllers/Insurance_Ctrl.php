
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insurance_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Insurance_model');

    }
    
//////////////////////////------- For Insurance/list.php --------/////////////////////////////////
public function Insurance_list()
{    
    // $result = $this->Insurance_model->insurance_data_all($this->user_id);
    $result = [];
    $result1 = $this->Insurance_model->insurance_data_one($this->user_id);
    $result2 = $this->Insurance_model->insurance_data_two($this->user_id);
    $result3 = $this->Insurance_model->insurance_data_three($this->user_id);
    if($result1){
        array_push($result, $result1);    
    }
    if($result2){
        array_push($result, $result2);    
    }
    if($result3){
        array_push($result, $result3);    
    }    
    
    // if ($_REQUEST['type'] == 'primary') {
    //     $result = $this->Insurance_model->insurance_data_one($this->userid);
    //       }else if ($_REQUEST['type'] == 'secondary') {
    //     $result = $this->Insurance_model->insurance_data_two($this->userid);
    //     } else if ($_REQUEST['type'] == 'tertiary') {
    //     $result = $this->Insurance_model->insurance_data_three($this->userid);
    //    }
    // print_r($result);exit;
    // $rows = array();
    //     $rows[] = $r;
    echo json_encode($result);

}

public function insurance_save()
{
    $request = get_request_body();
	$request["patientId"] = $this->user_id;
    $output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = "insurance_data";
	$change_type = $request['editID'];

			///////------- For Adding Records
			
            $result = $this->Insurance_model->addData_patient_portal_changes($this->user_id,$table_name,$change_type,$jsonData,$request['hx_id']);
			if($result ){
				echo compileResponse(300, "<h1>Your Message has been sent to the clinic.<br/> please wait for them to review and respond.</h1>");
			}else{
				echo compileResponse(500, "Bad Parameters!!!");
			}
			///////------- For Adding Records

}

}    