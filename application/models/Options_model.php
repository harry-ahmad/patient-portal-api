<?php 

Class Options_model extends MY_Model{


    public function geOptions($GET){
        if($GET["myData"] == "states"){
            $this->db->select('stateabbr,statename');
            $this->db->from('tblstate');
            $this->db->where('countryid',1);
            $query = $this->db->get();
		}else{
            $this->db->select('value,title');
            $this->db->from('patient_lookups');
            $this->db->where('lookup_type',$GET["myData"]);
            $query = $this->db->get();
        }            
        if($query->num_rows() > 0){
            return($query->result_array());
        }
        else{
        return false;
        }
    }

    public function getOptionList($lookupID) {
        $this->db->select('title as text, option_id as value');
            $this->db->from('list_options');
            $this->db->where('list_id',$lookupID);
            $result = $this->db->get();
            // echo $this->db->last_query();
        return $result->result_array();
    }

    public function getDataFrom_tblcity($myCityID)
    {
        $sql = "SELECT cityid,cityname FROM tblcity where cityid IN (".$myCityID.")";
        
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            return($query->result_array());
        }
        else{
        return false;
        }
    }
    

 
}    