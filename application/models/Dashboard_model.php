<?php 

Class Dashboard_model extends MY_Model{

public function dynamic_query_execution($qry)
{
    $sql = $qry;
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }

}



}