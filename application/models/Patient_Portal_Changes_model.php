<?php 

Class Patient_Portal_Changes_model extends MY_Model{

public function get_patient_data($where)
{    
    $this->db->select('*');
    $this->db->from('patient_portal_changes');
    $this->db->where('table_name',$where);
    $query = $this->db->get();        
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}

}