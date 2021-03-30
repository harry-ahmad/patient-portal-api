<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Options_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Options_model');

    }
    
//////////////////////////------- For Getzipvals.php/ --------/////////////////////////////////

public function getOptions_func()
{

    try {
        $GET = get_request_body();	
        $result = $this->Options_model->geOptions($GET);	
		echo json_encode($result);
	} catch (Exception $ex) {
		echo '{"code":"500","message":"' . $ex->getMessage() . '"}';
	}
    exit();
}

public function getOptionsLists_func()
{
    try {
        $GET = get_request_body();
        $result = $this->Options_model->getOptionList($GET['type']);	
		echo json_encode($result);
	} catch (Exception $ex) {
		echo '{"code":"500","message":"' . $ex->getMessage() . '"}';
	}
    exit();
}
}