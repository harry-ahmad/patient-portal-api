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

public function appoitment_list($pid){

    $sql = "SELECT title,event_date,start_time,end_time
    -- concat('{title:\"',e.title,'\",start:\"',e.event_date,'T',e.start_time,'\",end:\"',e.event_date,'T',e.end_time,'\"}') event 
    FROM postcalendar_events e WHERE CAST(e.time as Date) = CURDATE() and patient_id=".$pid;
    
    $query = $this->db->query($sql);
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
    $this->db->where('type',$data['type']);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        return($query->result_array());
    }
    else{
        return 'empty';
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


}    