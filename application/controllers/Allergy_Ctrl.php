<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allergy_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Allergy_model');


    }    

//////////////////////////------- For allergy/list.php --------/////////////////////////////////
public function getlist(){
    
	if($this->input->method(true) == 'POST'){
		$post = get_request_body();    
	}
    if(isset($post['search']) && $post['search'] == "search_allergy"){
        $sessiontoken 		=  $this->script_sure_login();
        $searchAllergy = "";
        if(isset($post['q_word']) && $post['q_word'] <> ""){
            $searchAllergy = $post['q_word'][0];
        }elseif(isset($post['searchValue']) && $post['searchValue'] <> ""){
            $searchAllergy = $post['searchValue'];
        }
        if ($searchAllergy <> ""){
            $data = array(
                            "query"=> array("name"=>$searchAllergy)
                        ); // data u want to post			                                                                  
            $json_string = json_encode($data);
            
            $ch = curl_init();
            //curl_setopt($ch, CURLOPT_URL, "https://stage.scriptsure.com/v1.0/allergy/search?sessiontoken=$sessiontoken"); // for test server
            curl_setopt($ch, CURLOPT_URL, "https://us.scriptsure.com/v1.0/allergy/search?sessiontoken=$sessiontoken"); // production    
            //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
            curl_setopt($ch, CURLOPT_POST, true);                                                                   
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //it means the HTML from the web page returned goes into the $output variable rather than echoed out to standard output.    
            //curl_setopt($ch, CURLOPT_USERPWD, $api_key.':'.$password);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
                'Accept: application/json',
                'Content-Type: application/json',
                'apiKey: 6aebcbce-acdf-40d8-adf6-24418db4bc9e')    //old => c2a1c2c0-602e-11e8-9b2f-1228366d55aa                                                       
            ); //apiKey: 348e917e-375b-11e8-a1de-00ffb9b01905   => For test server
            $result = curl_exec($ch);
            curl_close($ch);
            $result_arr = json_decode($result, true);
            //$result_arr[]['sessiontoken'] =$sessiontoken;
            //printr($result_arr);
            /*return $result;*/
            //printr($result_arr);
            
            $page_number    = 1;
            $page_size      = isset($_POST["pageSize"]) ? $_POST["pageSize"] : 200 - 1;
            
            $total_row  = sizeof($result_arr);
            $total_page = ($total_row / $page_size);

            $final_data = array("values" => array("gridResult" => array(
                    "pageSize"   => $page_size,
                    "pageNumber" => $page_number,
                    "totalRow"   =>  $total_row,
                    "totalPage"  => round($total_page),
                    "list"       => $result_arr)));
            echo json_encode($final_data);
        }
        return false;
    }
    if (isset($post['dataID']) && $post['dataID'] <>""){
        
        if($post['dataID'] == 0){
            $rows["editdata"] = '0';
            $rows[] = $rows["editdata"];
        }else{
            // $result = $db->executeSQL("SELECT * FROM patient_allergies where pid =".$pid." and id=".$_REQUEST['dataID']);
            $result = $this->Allergy_model->getPatient_allergies($this->user_id,$post['dataID']);
            $r = $result;
            $rows["editdata"] = '1';
            $rows[] = $r;
        }
        // $result = $db->executeSQL("SELECT * FROM allergy_reactions");
        $result =  $this->Allergy_model->getAllergy_reactions();
        while ($r = $result)
        $rows['reactions'][] = $r;

       
        $result = $this->Allergy_model->getAll_allergy_types();
        while ($r = $result)
        $rows['allergy_types'][] = $r;
        
        $result = $this->Allergy_model->getAll_allergy_severities();
        while ($r = $result)
        $rows['allergy_severities'][] = $r;
        $rows['message'] = '';
        // not done
        // $query = $db->executeSQL("select count(id) as total_changes, (select count(id) from patient_portal_changes where pid = ".$pid." and status = '0' and table_name = 'patient_allergies' and change_type = '1' and update_id = ".$_REQUEST['dataID'].") as pending_changes from patient_portal_changes where pid = ".$pid."");
        $query = $this->Allergy_model->patient_portal_changes($this->user_id,$post['dataID']);
        $log = $query;
        if (count($log) > 0) {
            if($log['pending_changes'] > 0){
                $rows['message'] = 'Your '.$log["pending_changes"].' change(s) regarding this allergy are pending by the medical staff.';
            }
        }
    }elseif(isset($post['param']) && $post['param'] == "act"){
        // $result = $db->executeSQL("SELECT * FROM patient_allergies where pid =".$pid." and endDate is NULL");
        $result = $this->Allergy_model->getPat_allergies_nullendDate($this->user_id);
        while ($r = $result)
        $rows[] = $r;
    }elseif(isset($post['param']) && $post['param'] == "inact"){
        // $result = $db->executeSQL("SELECT * FROM patient_allergies where pid =".$pid." and endDate is not NULL");
        $result = $this->Allergy_model->getPat_allergies_NotnullendDate($this->user_id);
        while ($r = mysqli_fetch_assoc($result))
        $rows[] = $r;
    }elseif(isset($post['param']) && $post['param'] == "severity"){
        // $result = $db->executeSQL("SELECT severity_name FROM allergy_severities where severity_id =".$_REQUEST['id']);
        $result = $this->Allergy_model->get_severity_nameByid($post['id']);
        while ($r = $result)
        $rows[] = $r;
    }else{
        // $result = $db->executeSQL("SELECT * FROM patient_allergies where pid =".$pid);
        $result = $this->Allergy_model->get_patient_allergiesBypid($this->user_id);        
        echo json_encode($result);
        
    }

}


private function script_sure_login(){
    //$data_string = http_build_query($data);
    //For Test environment
    $data = array(
                    "apikey" => "348e917e-375b-11e8-a1de-00ffb9b01905~503512b069429877d62cd1db34445e24702abba7~1522929791271.04",
                    "email"=> "yousaf.qureshi@gmail.com"
                ); // data u want to post 
    //For Production environment
    //double qoutes give error in api key contain $F: Undefined variable: F			
    $data = array( //old=>c2a1c2c0-602e-11e8-9b2f-1228366d55aa
                    "apikey" => '6aebcbce-acdf-40d8-adf6-24418db4bc9e~$2a$10$F.HPJHIPshEuE0/5A.bRAe4c4ylGoP3fCB2xxWhMlJo1EtRp1h4ha',
                    "email"=> "muradsikandar@gmail.com"
                ); // data u want to post
                
    $data = array(
                    "email"=> "muradsikandar@gmail.com",
                    "password" => "Pakistan@1"
                    
                ); // data u want to post			                                                                  
    $data_string = json_encode($data);	
    
    $ch = curl_init(); 
    //curl_setopt($ch, CURLOPT_URL, "https://stage-platform.scriptsure.com/v1.0/login/byapp"); // For test server
    //curl_setopt($ch, CURLOPT_URL, "https://platform.scriptsure.com/v1.0/login/byapp"); //404 Page Not found
    curl_setopt($ch, CURLOPT_URL, "https://platform.scriptsure.com/v1.0/login");//It works   
    //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    //curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);	
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
    curl_setopt($ch, CURLOPT_POST, true);                                                                   
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //it means the HTML from the web page returned goes into the $output variable rather than echoed out to standard output.    
    //curl_setopt($ch, CURLOPT_USERPWD, $api_key.':'.$password);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
    //curl_setopt($ch, CURLOPT_HEADERFUNCTION, "HandleHeaderLine");//call back function
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
        'Accept: application/json',
        'Content-Type: application/json',
        'apiKey: 6aebcbce-acdf-40d8-adf6-24418db4bc9e')     //old=>c2a1c2c0-602e-11e8-9b2f-1228366d55aa                                                      
    ); 
    
    $result = curl_exec($ch);
    //$returnCode = curl_getinfo($ch);
    curl_close($ch); 
    
    $result=explode("\r\n\r\nHTTP/", $result, 2);    //to deal with "HTTP/1.1 100 Continue\r\n\r\nHTTP/1.1 200 OK...\r\n\r\n..." header
    $result=(count($result)>1 ? 'HTTP/' : '').array_pop($result);
    list($str_resp_headers, $result)=explode("\r\n\r\n", $result, 2); //Some times ErrorException: Undefined offset: 1
    $str_resp_headers=explode("\r\n", $str_resp_headers);
    array_shift($str_resp_headers);    //get rid of "HTTP/1.1 200 OK"
    $resp_headers=array();
    foreach ($str_resp_headers as $k=>$v)
    {
        $v=explode(': ', $v, 2);
        $resp_headers[$v[0]]=$v[1];
    }
    
    //print "<pre>";print_r($resp_headers);
    $sessiontoken = $resp_headers['sessiontoken'];
    return $sessiontoken;			
}
//////////////////////////------- For allergy/list.php --------/////////////////////////////////    

//////////////////////////------- For allergy/save.php --------///////////////////////////////// 

public function save(){

 $request = get_request_body();
 $request["patientId"] = $this->user_id; //$pid
if(isset($request["reaction_date"])){
    $request["reaction_date"] = date('Y-m-d', strtotime($request["reaction_date"]));
}
if(isset($request["end_date"])){
    $request["end_date"] = date('Y-m-d', strtotime($request["end_date"]));
}
$output = str_replace(array("\r\n", "\n", "\r"),'',$request);
$jsonData = json_encode($output);
$table_name = "patient_allergies";
$change_type = $request['editID'];

///////------- For Adding Records

$result = $this->Allergy_model->AddRecordsTo_patient_portal_changes($this->user_id,$table_name,$change_type,$jsonData,$request['hx_id']);
if($result){
    echo compileResponse(300, "Your Message has been sent to the clinic. Please wait for them to review and respond.");
}else{
    echo compileResponse(500, "Bad Parameters!!!");
}
///////------- For Adding Records




}
//////////////////////////------- For allergy/save.php --------///////////////////////////////// 










}    
