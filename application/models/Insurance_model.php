<?php 

Class Insurance_model extends MY_Model{

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
        return($query->result_array());
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
        return($query->result_array());
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
        return($query->result_array());
    }
    else{
    return false;
    }
}
	




}