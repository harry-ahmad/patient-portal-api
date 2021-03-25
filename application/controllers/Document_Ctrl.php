<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Document_model');

    }
    
//////////////////////////------- For Document/list.php/ --------/////////////////////////////////
public function document_list()
{
    $result = $this->Document_model->getDataFrom_document($this->userid);
    $rows = [];
    while ($r = $result)
            $rows[] = $r;
   
    echo json_encode($rows);        
}




}