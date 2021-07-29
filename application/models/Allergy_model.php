<?php 

Class Allergy_model extends MY_Model{


public function getPatient_allergies($pid,$dataId)
{
    $this->db->select('*');
    $this->db->from('patient_allergies');
    $this->db->where('pid',$pid);
    $this->db->where('id',$dataId);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}

public function getAllergy_reactions()
{
    $this->db->select('*');
    $this->db->from('allergy_reactions');
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
public function getAll_allergy_types()
{
    $this->db->select('*');
    $this->db->from('allergy_types');
    $this->db->order_by('name', 'asc');
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}

public function getAll_allergy_severities()
{
    $this->db->select('*');
    $this->db->from('allergy_severities');
    $this->db->order_by('severity_name', 'asc');
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}

//ask from ustad
public function patient_portal_changes($pid,$dataId){
    $sql = "select count(id) as total_changes, (select count(id) from patient_portal_changes where pid = ".$pid." and status = '0' and table_name = 'patient_allergies' and change_type = '1' and update_id = ".$dataID.") as pending_changes from patient_portal_changes where pid = ".$pid."";
    
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
public function getPat_allergies_nullendDate($pid)
{
    $this->db->select('*');
    $this->db->from('patient_allergies');
    $this->db->where('pid', $pid);
    $this->db->where('endDate', null);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
public function getPat_allergies_NotnullendDate($pid)
{
    $this->db->select('*');
    $this->db->from('patient_allergies');
    $this->db->where('pid', $pid);
    $this->db->where('endDate IS NOT NULL', null);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}

public function get_severity_nameByid($id)
{
    $this->db->select('severity_name');
    $this->db->from('allergy_severities');
    $this->db->where('severity_id', $id);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
public function get_patient_allergiesBypid($pid)
{
    $this->db->select('*');
    $this->db->from('patient_allergies');
    $this->db->where('pid', $pid);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}

public function AddRecordsTo_patient_portal_changes($pid,$table_name,$change_type,$jsonData,$request_hxid)
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
    $id='id';
    if($request['table_name'] == 'medicalhx'){
        $id = 'med_id';
    }else if($request['table_name'] == 'psychiatrichx'){
        $id = 'psych_id';
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


