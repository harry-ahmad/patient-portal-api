<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Getzipvals_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Getzipvals_model');

    }
    
//////////////////////////------- For Getzipvals.php/ --------/////////////////////////////////

public function getzipvals_func()
{
    $POST = get_request_body();	
    $rows = array();

    if(isset($POST["myZipCode"]) && $POST["myZipCode"] <> ""){
        ///////------- Get Statid and City ID
        $result = $this->Getzipvals_model->getDataFrom_zipcodes($POST["myZipCode"]);
        $myRows = $result;
        if($myRows > 0){
            $myStateAbbr = $myRows[0]["State"];
            $myStateID = $myRows[0]["stateid"];
            $myCityID = $myRows[0]["cityid"];
            
            if($myCityID <> ""){
                $cityData = $this->Getzipvals_model->getDataFrom_tblcity($myCityID);
                $respnseData = [
                    'myStateAbbr' => $myStateAbbr,
                    'myStateID' => $myStateID,
                    'myCityID' => $myCityID,
                    'myCity' => $cityData['cityname']
                ]; 
            }else{
                $respnseData = [
                    'myStateAbbr' => $myStateAbbr,
                    'myStateID' => $myStateID,
                    'myCityID' => 0,
                    'myCity' => ''
                ];
            }
            echo json_encode($respnseData);
        }else
            exit('1');
        ///////------- Get Statid and City ID
    }else if(isset($POST["myCityID"]) && $POST["myCityID"] <> ""){
        ///////------- Get Cities List According to StateID
        $result = $this->Getzipvals_model->getDataFrom_tblcity($POST["myCityID"]);
        while ($r = $result)
            $rows[] = $r;
        ///////------- Get Cities List According to StateID
        echo (json_encode($rows));
    }
    exit();
}
}