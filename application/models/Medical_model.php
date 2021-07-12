<?php 

Class Medical_model extends MY_Model{


public function getDataFrom_medicalhx($pid)
{
    $sql = "select *, IF(active = '1','Yes','No') as activeMed from medicalhx where pid=".$pid." order by med_id desc";
    
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
	
public function getDataFromJoin_medicalhx($dxID)
{
    $sql = "select 
    *
  from 
      medicalhx mh
    inner join 
    planned_meds pm  
    on mh.med_id = pm.med_hx_id
  where 
  pm.med_hx_id = ".$dxID;
    
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
public function psyc_list($pid){

    $this->db->select('*');
    $this->db->from('psychiatrichx');
    $this->db->where('pid',$pid);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}

public function gyne_list($pid){

    $this->db->select('*');
    $this->db->from('patient_gyne_hx');
    $this->db->where('pid',$pid);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}

public function medical_status($pid,$request){

    $this->db->select('*');
    $this->db->from($request['tableName']);
    $this->db->where('pid',$pid);
    $this->db->where('active',$request['status']);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
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


}