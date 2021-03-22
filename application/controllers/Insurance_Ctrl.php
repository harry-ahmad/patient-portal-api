
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
    $result = null;
    if ($_REQUEST['type'] == 'primary') {
        $result = $this->Insurance_model->insurance_data_one($this->userid);
          }else if ($_REQUEST['type'] == 'secondary') {
        $result = $this->Insurance_model->insurance_data_two($this->userid);
        } else if ($_REQUEST['type'] == 'tertiary') {
        $result = $this->Insurance_model->insurance_data_three($this->userid);
       }
    
    $rows = array();
    while ($r = $result)
        $rows[] = $r;
    echo json_encode($rows);

}

}    