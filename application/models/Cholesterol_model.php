<?php 

Class Cholesterol_model extends MY_Model{

public function getDataFrom_patient_cholesterol_data($pid,$request_id)
{
    $this->db->select('*');
    $this->db->from('patient_cholesterol_data');
    $this->db->where('pid',$pid);
    $this->db->where('cholesterol_id',$request_id);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
	
public function getDirectData_patient_cholesterol_data($pid)
{
    $this->db->select('*');
    $this->db->from('patient_cholesterol_data');
    $this->db->where('pid',$pid);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
	
public function addData_patient_cholesterol_data($pid,$ldl,$hdl,$triglycerides,$total_cholesterol,$datetime,$note)
{
    $insert_arrray = array(

        'pid' => $pid,
        'ldl' => $ldl,
        'hdl' => $hdl,
        'triglycerides' => $triglycerides,
        'total_cholesterol' => $total_cholesterol,
        'datetime' => $datetime,
        'note' => $note

    );
    $this->db->insert('patient_cholesterol_data',$insert_arrray);
    if($this->db->affected_rows() > 0){
        return true;
    }
    else{
    return false;
    }
}  


}