<?php 

Class Getzipvals_model extends MY_Model{


    public function getDataFrom_zipcodes($myZipCode){

        $this->db->select('State,stateid,cityid');
        $this->db->from('zipcodes');
        $this->db->where('ZIP',$myZipCode);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return($query->result_array());
        }
        else{
        return false;
        }
    }

    public function getDataFrom_tblcity($myCityID)
    {
        $sql = "SELECT cityid,cityname FROM tblcity where cityid IN (".$myCityID.")";
        
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            return($query->row_array());
        }
        else{
        return false;
        }
    }
    

 
}    