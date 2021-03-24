<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surgical_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Surgical_model');

    }
    
//////////////////////////------- For Surgical/list.php --------/////////////////////////////////
public function surgical_list()
{

    if(isset($_REQUEST['stype']) && $_REQUEST['stype'] == "search"){
        if($_REQUEST['param'] !== "")
            {
                $search_tbl = $_REQUEST["searchTable"];
                $page_size = 10;
                $page_number = 1; 
                if(isset($_REQUEST["pageSize"])){
                    $page_size      = $_REQUEST["pageSize"];
                    $page_number    = ($_REQUEST["pageNumber"] - 1) * $page_size;
                }
                if(isset($_REQUEST["searchKey"]) && isset($_REQUEST['searchValue']))
                    {
                        $array = array();
                        $expSearchVal = explode(',',$_REQUEST['searchValue']);
                        
                        if(count($expSearchVal) > 1){
                            $searchStr = '';
                            foreach($expSearchVal as $searchVal){
                                $searchStr .= "'".$searchVal."',";
                            }
                            if(!empty($searchStr)){
                                $searchStr = substr($searchStr,0,(strlen($searchStr)-1));
                            }
                            $where          = $_REQUEST["searchKey"]." IN (".$searchStr.")";
                        }
                        else{
                            $where          = $_REQUEST["searchKey"]." IN ('".$_REQUEST['searchValue']."')";
                        }
                        if($search_tbl === "procedures") {
                            //$select_qry     = "proc_id, proc_code, proc_name , CONCAT(proc_name ,' (', proc_code, ')') AS  display_description, category, charges, user_defined";
                            $select_qry     = "proc_id, proc_code, proc_name , proc_name AS  display_description, category, charges, user_defined";
                        } else {
                            //echo json_encode($array);
                        }
                        
                        $sql = "SELECT $select_qry "
                                . "FROM  $search_tbl WHERE $where ";
                       
                        $result1 = $this->Surgical_model->dynamic_query($select_qry,$search_tbl,$where);
                        $result_arr = [];
                        while ($r = $result1){
                            $result_arr[] = $r;
                        }
                        $final_data = array("values" => array("gridResult" => array(
                            "list" => $result_arr)));                
                        //echo "<pre>"; print_r($final_data); exit;                            
                        echo json_encode($final_data);
                    }
                    
                    $q_word         = $_REQUEST["q_word"];
                    $search_term    = $search_term = $this->collect_search_term($q_word);
                    $and_or         = $_REQUEST["andOr"];
                    $where          = "";
                    $select_qry     = "*";
                    $like_search    = "";
                    $order_by       = "";
                    $join       	= "";

                    if($search_tbl === "procedures")
                    {
                        $type = '';
                        if(!empty($search_post["type"])){
                            $type = $search_post["type"];
                        }
                        $select_qry = "proc_id, proc_code, proc_name , CONCAT(proc_name ,' (', proc_code, ')') AS  display_description, category, charges, user_defined";
                        $like_search = "proc_name LIKE '%$search_term%' OR proc_code LIKE '%$search_term%'";
                        //$where = "AND is_active = 1";
                        if(!empty($type)){
                                $where =" And category='$type'";
                        }
                        $order_by = "ORDER BY proc_name ASC";
                    }
                    
                    $total_row      = $this->get_sp_search_list_count($db, $search_tbl, $like_search, $where, $join);

                    $sql = "SELECT $select_qry "
                            . "FROM  $search_tbl $join WHERE $like_search $where "
                            . "$order_by "
                            . "LIMIT $page_number, $page_size";
                    $result2 = $this->Family_model->dynamic_query2nd($search_tbl,$join,$like_search,$where,$order_by,$page_number,$page_size);
					
                   
                    $result_arr = [];
                    while ($r = mysqli_fetch_assoc($result2)){
                        $result_arr[] = $r;
                    }
                        
            //$result_arr = $query->result_array();
            
            $total_page = $total_row / $page_size;
                    
            $final_data = array("values" => array("gridResult" => array(
                "pageSize" => $page_size, 
                "pageNumber" => $page_number,
                "totalRow" =>  $total_row, 
                "totalPage" => ceil($total_page),
                "list" => $result_arr)));
                //echo "<pre>"; print_r($final_data); exit;                            
                echo json_encode($final_data);
                return false;
        } else {
            return false;
        }
    }
    
    $result = $this->Surgical_model->getDataFromsurgicalhx($this->userid);
    
    $rows = array();
    while ($r = $result){
        // $myDateTimeExplode = explode(' ',$r['surg_date']);
        // $r['mydatetime'] = $myDateTimeExplode[0].'T00:00:00.000Z';
        // $r['surg_date'] = myDateOnly($r['surg_date']);
        $rows[] = $r;
    }
    echo json_encode($rows);

}
//////////////////////////------- For Surgical/save.php --------/////////////////////////////////
public function surgical_save()
{
    $request = get_request_body();
	$request["patientId"] = $this->userid;
    $output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = "surgicalhx";
	$change_type = $request['editID'];

			///////------- For Adding Records
			
            $result = $this->Surgical_model->addData_patient_portal_changes($this->userid,$table_name,$change_type,$jsonData,$request['hx_id']);
			if($result ){
				echo compileResponse(300, "<h1>Your Message has been sent to the clinic.<br/> please wait for them to review and respond.</h1>");
			}else{
				echo compileResponse(500, "Bad Parameters!!!");
			}
			///////------- For Adding Records

}

//////////////////////////------- For Surgical/save.php --------/////////////////////////////////

//////////////////////////------- For Surgical/save.php --------/////////////////////////////////
public function delete_surg()
{
    $request = get_request_body();
    $ids = $request['ids'];
    $ids = rtrim($ids, ',');
    $result = $db->executeSQL("delete from lists where id in (" . $ids . ")");
    $result = $this->Surgical_model->deleteFromlists($ids);
   
    echo $this->compileResponse(200, "Records has been removed!");


}
//////////////////////////------- For Surgical/save.php --------/////////////////////////////////

private function get_sp_search_list_count($db, $search_tbl, $like_search, $where, $join) 
	{   
		
		$result =  $this->Surgical_model->get_sp_search_list_count($search_tbl,$join,$like_search,$where);
		$r = $result;
		return $r['totalCount'];
	}

	private function collect_search_term($q_word)
	{
		$search_term = "";
		for($i=0; $i < count($q_word); $i++) 
		{
			if($i === 0)
			{
				$search_term .= $q_word[$i];
			}
			elseif($i > 0)
			{
				$search_term .= ' '.$q_word[$i];
			}                    
		}
		return $search_term;                     
	}

 private function compileResponse($code, $msg)
		{
			if ($code == 200)
				$msg = '{"code":"' . $code . '","message":"Updated Successfully!"}';
			else
				$msg = '{"code":"' . $code . '","message":"' . $msg . '"}';
			return $msg;
		}

}    