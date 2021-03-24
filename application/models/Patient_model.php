<?php 

Class Patient_model extends MY_Model{

public function get_patient_json($pid)
{
    $this->db->select('*');
    $this->db->from('patient_data');
    $this->db->where('pid',$pid);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        $r = $query->result_array();
        while ($r)
        {
			$rows[] = $r;
			return str_replace(']', '', str_replace('[', '', json_encode($rows)));
        }   
    }
    else{
    return false;
    }
}
	
public function getDataFrom_patient_documents($pid)
{
    $this->db->select('file_name');
    $this->db->from('patient_documents');
    $this->db->where('category','6');
    $this->db->where('pid',$pid);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}
	
public function update_patient_documents($file_name,$file_ext,$mbSize,$pid)
{
    $this->db->set('file_name',$file_name);
    $this->db->set('file_type',$file_ext);
    $this->db->set('file_size',$mbSize);
    $this->db->where('pid',$pid);
    $this->db->update('patient_documents');
    
    if($this->db->affected_rows() > 0){
        return true;
    }
    else{
    return false;
    }
}
	

public function insert_patient_documents($file_name,$file_ext,$mbSize,$pid)
{
    $insert_arrray = array(

        'file_name'   => $file_name,
        'file_type'   => $file_ext,
        'pid'         => $pid,
        'category'    => '6',
        'file_size'   => $mbSize
    );
    $this->db->insert('patient_documents',$insert_arrray);
    if($this->db->affected_rows() > 0){
        return true;
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