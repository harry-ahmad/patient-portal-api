<?php

class Report_Ctrl extends MY_Controller {

public function __construct(){
    parent::__construct();
    $this->load->model('Dashboard_model');

}

public function generate_report(){
    $username = get_request_body();
    $pid = $this->user_id;
    require('Dashboard_Ctrl.php');
    $Dashboard_Ctrl = new Dashboard_Ctrl();
    $data = [];
    $data['measurements'] = $Dashboard_Ctrl->get_dashboard_data('bp',$pid);
    $data['medical'] = $Dashboard_Ctrl->get_dashboard_data('medical',$pid);
    $data['allergy'] = $Dashboard_Ctrl->get_dashboard_data('allergy',$pid);
    $data['bmi'] = $Dashboard_Ctrl->get_dashboard_data('bmi',$pid);
    $data['username'] = $username['username'];
    $html = $this->load->view("reports/dashboard_report.php", $data, true);
    print_r($html);
}

}
