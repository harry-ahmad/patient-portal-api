<?php 

Class Patient_Portal_Changes_model extends MY_Model{

public function get_patient_data($table_name)
{          
    $query = $this->db->where("table_name",$table_name)->where_in('status',array("0","2"))->get("patient_portal_changes")->result();          
    if($query > 0){
        return($query);
    }
    else{
    return false;
    }
}

}