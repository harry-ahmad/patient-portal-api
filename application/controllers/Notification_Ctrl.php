<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Notification_model');
    }
    
//////////////////////////------- For Notification.php --------/////////////////////////////////
    public function notification_list()
    {        
        $result = $this->Notification_model->getDataFrom_portal_changes($this->user_id);        
        $result1 = array('pending' => array(),'accepted' => array(),'rejected' => array());        
        foreach($result as $val){             
            if($val['status'] == 1){
                if($val['table_name'] == 'postcalendar_events'){
                    $appointments = 'Appointments';
                    array_push($result1['accepted'],$appointments); 
                }else if($val['table_name'] == 'form_vitals'){
                    $form_vitals = 'Measurements';
                    array_push($result1['accepted'],$form_vitals); 
                }else if($val['table_name'] == 'patient_data'){
                    $patient_data = 'Demographics';
                    array_push($result1['accepted'],$patient_data); 
                }else if($val['table_name'] == 'patient_allergies'){
                    $patient_allergies = 'Allergies';
                    array_push($result1['accepted'],$patient_allergies); 
                }else if($val['table_name'] == 'planned_meds'){
                    $planned_meds = 'Medications';
                    array_push($result1['accepted'],$planned_meds); 
                }else if($val['table_name'] == 'surgicalhx'){
                    $surgicalhx = 'Social History';
                    array_push($result1['accepted'],$surgicalhx); 
                }else if($val['table_name'] == 'familyhx'){
                    $familyhx = 'Family History';
                    array_push($result1['accepted'],$familyhx); 
                }else if($val['table_name'] == 'medicalhx'){
                    $medicalhx = 'Medical History';
                    array_push($result1['accepted'],$medicalhx); 
                }else if($val['table_name'] == 'documents'){
                    $documents = 'Documents';
                    array_push($result1['accepted'],$documents); 
                }else if($val['table_name'] == 'insurance_data'){
                    $insurance_data = 'Insurance';
                    array_push($result1['accepted'],$insurance_data); 
                }else if($val['table_name'] == 'vitals'){
                    $vitals = 'Vitals';
                    array_push($result1['accepted'],$vitals); 
                }
            }else if($val['status'] == 2){
                if($val['table_name'] == 'postcalendar_events'){
                    $appointments = 'Appointments';
                    array_push($result1['rejected'],$appointments); 
                }else if($val['table_name'] == 'form_vitals'){
                    $form_vitals = 'Measurements';
                    array_push($result1['rejected'],$form_vitals); 
                }else if($val['table_name'] == 'patient_data'){
                    $patient_data = 'Demographics';
                    array_push($result1['rejected'],$patient_data); 
                }else if($val['table_name'] == 'patient_allergies'){
                    $patient_allergies = 'Allergies';
                    array_push($result1['rejected'],$patient_allergies); 
                }else if($val['table_name'] == 'planned_meds'){
                    $planned_meds = 'Medications';
                    array_push($result1['rejected'],$planned_meds); 
                }else if($val['table_name'] == 'surgicalhx'){
                    $surgicalhx = 'Social History';
                    array_push($result1['rejected'],$surgicalhx); 
                }else if($val['table_name'] == 'familyhx'){
                    $familyhx = 'Family History';
                    array_push($result1['rejected'],$familyhx); 
                }else if($val['table_name'] == 'medicalhx'){
                    $medicalhx = 'Medical History';
                    array_push($result1['rejected'],$medicalhx); 
                }else if($val['table_name'] == 'documents'){
                    $documents = 'Documents';
                    array_push($result1['rejected'],$documents); 
                }else if($val['table_name'] == 'insurance_data'){
                    $insurance_data = 'Insurance';
                    array_push($result1['rejected'],$insurance_data); 
                }else if($val['table_name'] == 'vitals'){
                    $vitals = 'Vitals';
                    array_push($result1['rejected'],$vitals); 
                }
            }else if($val['status'] == 0){
                if($val['table_name'] == 'postcalendar_events'){
                    $appointments = 'Appointments';
                    array_push($result1['pending'],$appointments); 
                }else if($val['table_name'] == 'form_vitals'){
                    $form_vitals = 'Measurements';
                    array_push($result1['pending'],$form_vitals); 
                }else if($val['table_name'] == 'patient_data'){
                    $patient_data = 'Demographics';
                    array_push($result1['pending'],$patient_data); 
                }else if($val['table_name'] == 'patient_allergies'){
                    $patient_allergies = 'Allergies';
                    array_push($result1['pending'],$patient_allergies); 
                }else if($val['table_name'] == 'planned_meds'){
                    $planned_meds = 'Medications';
                    array_push($result1['pending'],$planned_meds); 
                }else if($val['table_name'] == 'surgicalhx'){
                    $surgicalhx = 'Social History';
                    array_push($result1['pending'],$surgicalhx); 
                }else if($val['table_name'] == 'familyhx'){
                    $familyhx = 'Family History';
                    array_push($result1['pending'],$familyhx); 
                }else if($val['table_name'] == 'medicalhx'){
                    $medicalhx = 'Medical History';
                    array_push($result1['pending'],$medicalhx); 
                }else if($val['table_name'] == 'documents'){
                    $documents = 'Documents';
                    array_push($result1['pending'],$documents); 
                }else if($val['table_name'] == 'insurance_data'){
                    $insurance_data = 'Insurance';
                    array_push($result1['pending'],$insurance_data); 
                }else if($val['table_name'] == 'vitals'){
                    $vitals = 'Vitals';
                    array_push($result1['pending'],$vitals); 
                }         
            }            
        }        
        echo json_encode($result1);
    }

}