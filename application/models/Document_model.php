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



}