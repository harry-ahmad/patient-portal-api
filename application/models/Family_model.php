<?php 

Class Family_model extends MY_Model{

public function get_diagnosisDataForFamily($icd_code)
{
    $sql = "SELECT DCODE, LONG_DESCRIPTION , CONCAT(LONG_DESCRIPTION ,' (', DCODE, ')') AS  display_description FROM diagnosis where DCODE = '".$icd_code."'";
    
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->row_array());
    }
    else{
    return false;
    }
}
	
public function dynamic_query($select_qry,$search_tbl,$where)
{
    $sql = "SELECT $select_qry "
    . "FROM  $search_tbl WHERE $where ";
    
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
	
public function dynamic_query2nd($search_tbl,$join,$like_search,$where,$order_by,$page_number,$page_size)
{
    $sql = "SELECT $select_qry "
    . "FROM  $search_tbl $join WHERE $like_search $where "
    . "$order_by "
    . "LIMIT $page_number, $page_size";
    
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
	
public function get_sp_search_list_count($search_tbl,$join,$like_search,$where)
{
    $sql = "SELECT COUNT(*) AS totalCount FROM  $search_tbl $join WHERE $like_search $where";
    
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
	
public function getDataFromfamilyhx($pid)
{
    $this->db->select('*');
    $this->db->from('familyhx');
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