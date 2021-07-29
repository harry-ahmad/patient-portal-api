<?php 

Class Measurement_model extends MY_Model{

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
public function getAllDataFrom_form_vitals($pid)
{
    $sql = "SELECT *, vital_id as id FROM form_vitals where pid = ".$pid."";
    
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
    $sql = "SELECT '' AS id, vital_id, encounter AS encounter_id, pid, '' AS vital_type, form_vitals.date, weight, height, BMI, bps, bpd, bp_side, bp_site, bp_position, blood_glucose, menstruation_date, delivery_date, pulse, respiration, temperature, temp_method, oxygen_saturation, head_circ, waist_circ FROM form_vitals WHERE pid = $pid
    UNION 
    SELECT id, vital_id, encounter_id, '' AS pid, vital_type , vitals_readings.date, '' AS weight, '' AS height, '' AS BMI, bps, bpd, bp_side, bp_site, bp_position, blood_glucose, '' AS menstruation_date, '' AS delivery_date, pulse, respiration, temperature, temp_method, '' AS oxygen_saturation, '' AS head_circ, '' AS waist_circ FROM vitals_readings WHERE pid = $pid ORDER BY vital_id desc, id asc";
    
    $query = $this->db->query($sql);
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


public function editData_patient_portal_changes($id,$pid,$table_name,$change_type,$jsonData,$request_hxid)
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
    $this->db->where('id', $id);
    $this->db->update('patient_portal_changes', $insert_arrray);    
    if($this->db->affected_rows() > 0){
        return true;
    }
    else{
    return false;
    }
}

public function delete($request){
    $id='vital_id';
    if($request['table_name'] == 'patient_portal_changes'){
        $id = 'id';
    }
    $this->db->where($id, $request['id']);
    $this->db->delete($request['table_name']);
    if($this->db->affected_rows() > 0){
        return true;
    }
    else{
        return false;
    }
}


}