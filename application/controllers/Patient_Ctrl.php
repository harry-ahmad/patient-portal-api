<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Surgical_model');

    }
    
//////////////////////////------- For Patient/list.php --------/////////////////////////////////
public function patient_list()
{
    $json_str_obj = $this->get_patient_json($this->userid);
	echo $json_str_obj;

}
//////////////////////////------- For Patient/save.php --------/////////////////////////////////
public function patient_save()
{
    $request = get_request_body();
	$request["patient_data"] = $this->userid;
    $output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = "patient_data";
	$change_type = $request['editID'];

			///////------- For Adding Records
			
            $result = $this->Patient_model->addData_patient_portal_changes($this->userid,$table_name,$change_type,$jsonData,$request['hx_id']);
			if($result ){
				echo compileResponse(300, "<h1>Your Message has been sent to the clinic.<br/> please wait for them to review and respond.</h1>");
			}else{
				echo compileResponse(500, "Bad Parameters!!!");
			}
			///////------- For Adding Records

}

//////////////////////////------- For Patient/save.php --------/////////////////////////////////

//////////////////////////------- For faPatientmily/fileupload.php --------/////////////////////////////////
public function patient_file_upload()
{
	$directory = '../../include/patient_data/patient_'.$this->userid;
	if(!is_dir($directory))
		mkdir($directory, 0777, true);
	
	if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] <> 4){
		$file_name = $_FILES['profile_pic']['name'];
		$file_size = $_FILES['profile_pic']['size'];
		$file_tmp =$_FILES['profile_pic']['tmp_name'];
		$file_type=$_FILES['profile_pic']['type'];
		$file_ext = strtolower(end(explode('.',$_FILES['profile_pic']['name'])));
		//exit('File Size: '.$file_size);
		$extensions = array("jpeg","jpg","png","bmp");
		
		if(in_array($file_ext,$extensions)=== false){
		   $errors .= "Extension not allowed, please choose a JPEG or PNG file.";
		}
	
		if($file_size > 2097152){
		   $errors .= 'File size must be excately or less than 2 MB';
		}
		$mbSize = number_format($file_size / 1024, 2);
		//exit('MB: '.$mbSize);
		
		///////------- New File Name
		$file_name = $this->readyToLink(getImageName($file_name));
		$file_name .= '-'.substr(time(),-3).'.'.$file_ext;
		///////------- New File Name
		
		if($errors === ""){
			///////------- Check If File Already Exist
			// $resultSub = $db->executeSQL("SELECT file_name FROM patient_documents where category = '6' and pid=".$this->userid);
			$resultSub = $this->Patient_model->getDataFrom_patient_documents($this->userid);
			$rowSub = $resultSub;
			$myFileName = $rowSub["file_name"];
			if($myFileName <> ""){
				///////------- Delete OLD Image
				$filename = $directory.'/'.$myFileName;
				if (file_exists($filename)) {
					unlink($filename);
				} 
				///////------- Delete OLD Image
				$result = $this->Patient_model->update_patient_documents($file_name,$file_ext,$mbSize,$this->userid);
				}else{
					$result = $this->Patient_model->insert_patient_documents($file_name,$file_ext,$mbSize,$this->userid);
				}
			///////------- Check If File Already Exist
			move_uploaded_file($file_tmp,$directory."/".$file_name);
			exit('<img src="include/patient_data/patient_'.$pid.'/'.$file_name.'" />');
		}else{
		   exit($errors);
		}
	}else{
		exit('Please Select Image.');
	}
}
//////////////////////////------- For Patient/fileupload.php --------/////////////////////////////////

private function get_patient_json($pid) 
	{   
		
		$result =  $this->Patient_model->get_patient_json($pid);
		$r = $result;
		return $r['totalCount'];
	}

private	function readyToLink($str)
	{
		$str = str_replace("-"," ",$str);
		$before = array(
			'àáâãäåòóôõöøèéêëðçìíîïùúûüñšž',
			'/[^a-z0-9\s]/',
			array('/\s/', '/--+/', '/---+/')
		);
		$after = array( 'aaaaaaooooooeeeeeciiiiuuuunsz', '');	
		$str = strtolower($str);
		$str = strtr($str, $before[0], $after[0]);
		$str = preg_replace($before[1], $after[1], $str);
		$str = trim($str);
		$str = str_replace("  "," ",$str);
		$str = str_replace("   "," ",$str);
		$str = str_replace(" ","-",$str);
		return $str;
	}	


}    