<?php 
Class Document_model extends MY_Model{


public function getDataFrom_document($pid)
{
    $sql = "SELECT *, d.id as doc_id FROM documents d inner join doc_categories dc on dc.id=d.category where pid = ".$pid;
        
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}

public function getDataByTimeFilter($pid,$post)
{    
	$from = $to = "";
	if($post['date'] == 'day'){
		$from = date('Y-m-d H:i:s');
		$to = date('Y-m-d H:i:s');
	}else if($post['date'] == 'week'){
		$from = date('Y-m-d H:i:s', strtotime("-7 days"));
		$to = date('Y-m-d H:i:s');
	}else if($post['date'] == 'month'){
		$from = date('Y-m-d H:i:s', strtotime("-1 months"));
		$to = date('Y-m-d H:i:s');
	}else if($post['date'] == 'year'){
		$from = date('Y-m-d H:i:s', strtotime("-1 years"));
		$to = date('Y-m-d H:i:s');
	}
    $sql = "SELECT *, d.id as doc_id FROM documents d inner join doc_categories dc on dc.id=d.category where pid = ".$pid." and upload_date between '".$from."' AND '".$to."'";
        
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}



}