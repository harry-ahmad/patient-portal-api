<?php 

Class Bloodglucose_model extends MY_Model{

public function getDataFrom_form_vitals($pid,$request_id)
{
    $sql = "SELECT *, vital_id as id FROM form_vitals where pid = ".$pid." and id=".$request_id;
    
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
	
public function getDataFrom_vitals_readings($pid,$request_id)
{
    $this->db->select('*');
    $this->db->from('vitals_readings');
    $this->db->where('pid',$pid);
    $this->db->where('vital_type','blood_glucose');
    $this->db->where('id',$request_id);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
	
public function getDirectData_vitals_readings($pid)
{
    $this->db->select('*');
    $this->db->from('vitals_readings');
    $this->db->where('vital_type','blood_glucose');
    $this->db->where('pid',$pid);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
	
public function getDirectData_form_vitals($pid)
{
    $this->db->select('*');
    $this->db->from('form_vitals');
    $this->db->where('pid',$pid);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
	
public function addData_patient_portal_changes($pid,$table_name,$change_type,$jsonData,$request_hxid)
{
    $insert_arrray = array(

        'pid'              => $pid,
        'table_name'       => $table_name,
        'change_type'      => $change_type,
        'changes'          => $jsonData,
        'update_id'        => $request_hxid,
        'status'           => '0',
        'approved_deny_by' => '0',
        'comment'          => ''

    );
    $this->db->insert('patient_portal_changes',$insert_arrray);
    if($this->db->affected_rows() > 0){
        return true;
    }
    else{
    return false;
    }


}    


}