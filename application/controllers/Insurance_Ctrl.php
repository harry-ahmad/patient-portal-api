
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

	$_POST["patientId"] = $this->user_id;
    $change_type = $_POST['editID'];
    $table_name = "insurance_data";
    $file_paths = [];
    if(isset($_FILES['file']) && count($_FILES['file']) > 0){        
      
        $directory = 'upload/patient_'.$_POST["patientId"];
        $db_path = 'patient_portal_api/include/portal_data/patient_'.$_POST["patientId"];
        if(!is_dir($directory))
          mkdir($directory, 0777, true);
        
        // The posted data, for reference
        foreach ($_FILES['file']['name'] as $key => $value) {                    
            $file = $_FILES['file']['tmp_name'][$key];
            $name = $value;
            print_r($file,$name);
            $file_ext = explode('.',strtolower($name));
            $file_ext = end($file_ext);
            $extensions = array("jpeg","jpg","png","bmp","zip","pdf","doc","docx","txt");
            
            if(in_array($file_ext,$extensions) === false){
              response(array(
                "status" => BAD_DATA,
                "message" => "Extension (".$file_ext.") not allowed."
               ), true);
            }
            $mbSize = '';
            ///////------- New File Name
            $file_name = readyToLink(getImageName($name));
            $file_name .= '-'.substr(time(),-3).'.'.$file_ext;
            
            if(move_uploaded_file($file, $directory."/".basename($file_name))) {
              array_push($file_paths, $db_path."/".$file_name);
            }
            else {
              exit('<div class="alert errorMsg">'.$errors.'</div>');
            }
          }
          $_FILES['file'] = implode(',', $file_paths);
    }
    
    $formData = str_replace(array("\r\n", "\n", "\r"),'',$_POST);
    $formData['files'] = str_replace(array("\r\n", "\n", "\r"),'',$_FILES);    
	$jsonData = json_encode($formData);    	

    ///////------- For Adding Records
    
    $result = $this->Insurance_model->addData_patient_portal_changes($this->user_id,$table_name,$change_type,$jsonData,$_POST['hx_id']);
    if($result> 0){
        response(array(
            "status" => SUCCESS,
            "message" => 'Your Message has been sent to the clinic. Please wait for them to review and respond'
        ));
        }else{
        response(array(
            "status" => BAD_DATA,
            "message" => 'Error while saving the record'
            ), true);
        }
    ///////------- For Adding Records

}

}    