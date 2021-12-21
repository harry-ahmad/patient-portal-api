<?php 

Class Setting_model extends MY_Model {

    public function get_system_setting($type, $status, $location_id = '', $api_name = ''){
        $this->db->select("*");
		$this->db->from("api_config");
		$this->db->where("api_type = '".$type."' ");
		$this->db->where("is_active = '".$status."' ");

        if($api_name != "") // 'API NAME 
			$this->db->where("api_name = '".$api_name."' ");

        $res = $this->db->get();
		return $res;
    }

    ///---- Save SMS / Fax Log Function
	public function sms_fax_log($data){
		$this->db->insert('sms_log',$data);	
		if($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;	
		}
	}


}
?>