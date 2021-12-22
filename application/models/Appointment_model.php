<?php 

Class Appointment_model extends MY_Model{

public function get_postcalendar_events($cur_date,$pid){

    $this->db->select('eid, event_date,start_time,end_time, catid, title');
    $this->db->from('postcalendar_events');
    $this->db->where('event_date',$cur_date);
    $this->db->where('patient_id',$pid);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return false;
    }
}


public function AddRecordsTo_patient_portal_changes($pid,$table_name,$change_type,$jsonData,$request_hxid)
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

public function appoitment_list($pid,$provider_id, $date){

    // $sql = "SELECT *
    // -- concat('{title:\"',e.title,'\",start:\"',e.event_date,'T',e.start_time,'\",end:\"',e.event_date,'T',e.end_time,'\"}') event 
    // FROM postcalendar_events WHERE event_date = CURRENT_DATE and patient_id=".$pid."";
    // -- FROM postcalendar_events e WHERE CAST(e.time as Date) = CURDATE() and patient_id=".$pid;
    // $sql = "SELECT provider_id FROM `postcalendar_events` WHERE patient_id = ".$pid." order by 'desc' limit 1";
    $where = "";
    if($provider_id !== ""){
        $where = "and provider_id = ".$provider_id;
    }
    
    $sql = "SELECT title,start_time,end_time,provider_id,event_date FROM `postcalendar_events` WHERE event_date = '".$date."' and patient_id = ".$pid." ".$where;
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
        return [];
    }
}

public function booked_appoitment_list($pid,$provider_id, $date){

    // $sql = "SELECT *
    // -- concat('{title:\"',e.title,'\",start:\"',e.event_date,'T',e.start_time,'\",end:\"',e.event_date,'T',e.end_time,'\"}') event 
    // FROM postcalendar_events WHERE event_date = CURRENT_DATE and patient_id=".$pid."";
    // -- FROM postcalendar_events e WHERE CAST(e.time as Date) = CURDATE() and patient_id=".$pid;
    // $sql = "SELECT provider_id FROM `postcalendar_events` WHERE patient_id = ".$pid." order by 'desc' limit 1";
    $where = "";
    if($provider_id !== ""){
        $where = "and provider_id = ".$provider_id;
    }
    
    $sql = "SELECT patient_id,title,start_time,end_time,provider_id,event_date FROM `postcalendar_events` WHERE event_date = '".$date."' and patient_id != ".$pid." ".$where;
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
        return [];
    }
}

public function get_last_provider($pid){

    
    $sql = "SELECT provider_id FROM `postcalendar_events` WHERE patient_id = ".$pid." order by 'eid desc' limit 1";    
    
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return($query->row_array());
    }
    else{
        return [];
    }
}

public function pending_list($pid,$provider_id, $date){
    $this->db->select('*');
    $this->db->from('patient_portal_changes');
    $this->db->where("table_name","postcalendar_events");
    $this->db->where("pid",$pid);
    // $this->db->where('date_format(date_time,"%Y-%m-%d")', $date);
    $this->db->where('JSON_EXTRACT(`changes`, "$.start_time") LIKE "%'.$date.'%"');    
    if($provider_id !== ""){        
        $this->db->where('(JSON_EXTRACT(`changes`, "$.provider_id")  = "'.$provider_id.'"');
        $this->db->or_where('JSON_EXTRACT(`changes`, "$.provider_id")  = '.$provider_id.')');
    }
    $this->db->where_in("status",array("0","2"));
    // $sql = $this->db->where("table_name","postcalendar_events")->where("date_time",'CURDATE()', false)->where_in('status',array("0","2"))->get("patient_portal_changes")->result();    
    $query = $this->db->get();
    // echo $this->db->last_query();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return [];
    }
}

public function booked_pending_list($pid,$provider_id, $date){
    $this->db->select('*');
    $this->db->from('patient_portal_changes');
    $this->db->where("table_name","postcalendar_events");
    $this->db->where("pid !=",$pid);
    // $this->db->where('date_format(date_time,"%Y-%m-%d")', $date);
    $this->db->where('JSON_EXTRACT(`changes`, "$.start_time") LIKE "%'.$date.'%"');    
    if($provider_id !== ""){        
        $this->db->where('(JSON_EXTRACT(`changes`, "$.provider_id")  = "'.$provider_id.'"');
        $this->db->or_where('JSON_EXTRACT(`changes`, "$.provider_id")  = '.$provider_id.')');
    }
    $this->db->where_in("status",array("0","2"));
    // $sql = $this->db->where("table_name","postcalendar_events")->where("date_time",'CURDATE()', false)->where_in('status',array("0","2"))->get("patient_portal_changes")->result();    
    $query = $this->db->get();
    // echo $this->db->last_query();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return [];
    }
}

public function appoitment_time($data){     
    $this->db->select('*');
    $this->db->from('calendar_data_setting');
    $this->db->where('provider_id',$data['provider_id']);
    $this->db->where('day',$data['day']);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
        return false;
    }
}

public function available_time($providerId, $date){
    $this->db->select('start_time,end_time');
    $this->db->from('postcalendar_events');
    $this->db->where('provider_id',$providerId);
    $this->db->where('event_date',$date);    
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
    return array();
    }
}

public function provider_list(){
    $this->db->select('provider_id, title, first_name, last_name');
    $this->db->from('providers');
    $this->db->where('user_id >',0);   
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
        return array();
    }
}

public function appoitment_hours(){

}


}    