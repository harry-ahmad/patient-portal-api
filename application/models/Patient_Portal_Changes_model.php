<?php 

Class Patient_Portal_Changes_model extends MY_Model{

public function get_patient_data($where)
{    
    // $this->db->select('*');
    // $this->db->from('patient_portal_changes');
    // $this->db->where('table_name',$where);
    // $this->db->or_where('status',0);
    // $this->db->or_where('status',1);
    // $query = $this->db->get();  
    $query = $this->db->where("table_name",$where)->where_in('status',array("0","2"))->get("patient_portal_changes")->result();          
    if($query > 0){
        return($query);
    }
    else{
    return false;
    }
}

}