<?php 

Class Insurance_model extends MY_Model{

public function insurance_data_all($pid)
{
    $this->db->select('*');
    $this->db->from('insurance_data');
    $this->db->where('pid',$pid);
    $this->db->where('sort_order','1');
    $this->db->or_where('sort_order','2');
    $this->db->or_where('sort_order','3');
    $this->db->where('encounter_id',0);
    $this->db->where('mystatus','1');
    $this->db->order_by("id", "asc");
    // $this->db->limit(1);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}

public function insurance_data_one($pid)
{
    $this->db->select('*');
    $this->db->from('insurance_data');
    $this->db->where('pid',$pid);
    $this->db->where('sort_order','1');
    $this->db->where('encounter_id',0);
    $this->db->where('mystatus','1');
    $this->db->order_by("id", "asc");
    $this->db->limit(1);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->row_array());
    }
    else{
    return false;
    }
}
	
public function insurance_data_two($pid)
{
    $this->db->select('*');
    $this->db->from('insurance_data');
    $this->db->where('pid',$pid);
    $this->db->where('sort_order','2');
    $this->db->where('encounter_id',0);
    $this->db->where('mystatus','1');
    $this->db->order_by("id", "asc");
    $this->db->limit(1);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->row_array());
    }
    else{
    return false;
    }
}
	
public function insurance_data_three($pid)
{
    $this->db->select('*');
    $this->db->from('insurance_data');
    $this->db->where('pid',$pid);
    $this->db->where('sort_order','3');
    $this->db->where('encounter_id',0);
    $this->db->where('mystatus','1');
    $this->db->order_by("id", "asc");
    $this->db->limit(1);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->row_array());
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