<?php 

Class Login_model extends CI_Model{
	
	public function login($username,$password){
		
		$this->db->select('*');
		$this->db->where('portal_username',$username,['portal_password' => $password]);
		$query = $this->db->get('patient_data');
		if($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return false;
		}
	}
	
	
}