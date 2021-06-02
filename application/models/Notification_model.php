<?php 

Class Notification_model extends MY_Model{


    public function getDataFrom_portal_changes($pid)
    {
        $this->db->select('table_name,status');
        $this->db->from('patient_portal_changes');
        $this->db->where('pid',$pid);        
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return($query->result_array());
        }
        else{
        return false;
    }

    }
}