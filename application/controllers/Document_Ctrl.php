<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Document_model');
		$this->load->model('Patient_Portal_Changes_model');

    }
    
//////////////////////////------- For Document/list.php/ --------/////////////////////////////////
public function document_list()
{
    $post = get_request_body();
    $result = array();
    if($post['date'] == 'all'){
      $result = $this->Document_model->getDataFrom_document($this->user_id);
      $result1 = $this->Patient_Portal_Changes_model->get_patient_data('documents');
      array_push($result, $result1);   
    }else{
      $result = $this->Document_model->getDataByTimeFilter($this->user_id, $post);
    }
		// $merged_arr = array_merge($result,$result1);
    echo json_encode($result);        
}

public function save_document()
{
  // print_r($_POST);
  // print_r($_FILES['file']);exit;
  $pid = $this->user_id;
  $table_name = "documents";
  $change_type = $_POST['editID'];
  $file_paths = [];
  if(isset($_FILES['file'])&& count($_FILES['file']) > 0){

    if($_POST["datetime"] <> "")
      $myDate = converDate($_POST["datetime"]);
    else
      $myDate = date("Y-m-d H:i:s");
  
    $directory = 'upload/patient_'.$pid;
    $db_path = 'upload/patient_'.$pid;
    // $db_path = 'patient_portal_api/include/portal_data/patient_'.$pid;
    if(!is_dir($directory))
      mkdir($directory, 0777, true);
    
    // The posted data, for reference
    // foreach ($_FILES['file'] as $key => $value) {
      //print_r($value);exit;
      $file = $_FILES['file']['tmp_name'];
      $name = $_FILES['file']['name'];

      $file_ext = explode('.',strtolower($name));
      $file_ext = end($file_ext);
      //exit('File Size: '.$file_size);
      $extensions = array("jpeg","jpg","png","bmp","zip","pdf","doc","docx","txt");
      
      if(in_array($file_ext,$extensions) === false){
        response(array(
          "status" => BAD_DATA,
          "message" => "Extension (".$file_ext.") not allowed."
         ), true);
      }
    //        if($file_size > 5242880)
    //           $errors .= 'File size must be excately or less than 5 MB';
    //        
    //        $mbSize = number_format($file_size / 1024, 2);
      //exit('MB: '.$mbSize);
      $mbSize = '';
      ///////------- New File Name
      $file_name = readyToLink(getImageName($name));
      $file_name .= '-'.substr(time(),-3).'.'.$file_ext;
      ///////------- New File Name
    
      // Separate out the data
      // $data = explode(',', $file);
      
      // // Encode it correctly
      // $encodedData = str_replace(' ','+',$data[1]);
      // $decodedData = base64_decode($encodedData);
      
      if(move_uploaded_file($file, $directory."/".basename($file_name))) {
        array_push($file_paths, $db_path."/".$file_name);
      }
      else {
        exit('<div class="alert errorMsg">'.$errors.'</div>');
      }
    // }
    
    $_FILES['file'] = implode(',', $file_paths);
    $output = str_replace(array("\r\n", "\n", "\r"),'',$_POST);
    $output['file'] = str_replace(array("\r\n", "\n", "\r"),'',$_FILES);
    $jsonData = json_encode($output);    
    //echo'<pre>',count($jsonData);print_r($jsonData);exit();
    ///////------- For Adding Records
    $result = $this->db->query(
      "insert into patient_portal_changes(pid,table_name,change_type,changes,update_id,status,approved_deny_by,comment)
      VALUES 
      ('".$pid."','".$table_name."','".$change_type."','".$jsonData."', '".$_POST['hx_id']."','0','0','')"
    );
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
    /////------- For Adding Records
  }else
    exit('1');
}


}