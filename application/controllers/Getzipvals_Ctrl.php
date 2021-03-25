<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medical_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Medical_model');

    }
    
//////////////////////////------- For Getzipvals.php/ --------/////////////////////////////////

public function getzipvals_func()
{

    $rows = array();
    if(isset($_POST["myZipCode"]) && $_POST["myZipCode"] <> ""){
        ///////------- Get Statid and City ID
        $result = $this->Getzipvals_model->getDataFrom_zipcodes($_POST["myZipCode"]);
        $myRows = $result;
        if($myRows > 0){
            $myStateAbbr = $myRows["State"];
            $myStateID = $myRows["stateid"];
            $myCityID = $myRows["cityid"];
            
            if($myCityID <> "")
                exit($myStateAbbr.'__'.$myStateID.'__'.$myCityID);
            else
                exit($myStateAbbr.'__'.$myStateID.'__0');
        }else
            exit('1');
        ///////------- Get Statid and City ID
    }else if(isset($_POST["myCityID"]) && $_POST["myCityID"] <> ""){
        ///////------- Get Cities List According to StateID
        $result = $this->Getzipvals_model->getDataFrom_tblcity($_POST["myCityID"]);
        while ($r = $result)
            $rows[] = $r;
        ///////------- Get Cities List According to StateID
    }
    echo (json_encode($rows));
    exit();



}
}