<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Family_Ctrl extends MY_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Family_model');
		$this->load->model('Patient_Portal_Changes_model');

    }
    
//////////////////////////------- For Family/list.php --------/////////////////////////////////
public function family_list()
{

    if(isset($_REQUEST['type']) && $_REQUEST['type'] == "get_diag"){
		if($_REQUEST['param'] !== "")
			{
				$icd_code_exp = explode(',', str_replace('ICD10:','',$_REQUEST['param']));
				$diagnosis = array();
				foreach($icd_code_exp as $icd_code)
				{
					$result = $this->Family_model->get_diagnosisDataForFamily($icd_code);
                    while ($r = $result)
							$diagnosis[] = $r['2'];
				}
				$result1 = $this->Patient_Portal_Changes_model->get_patient_data('familyhx');
				print_r($result1,'adasd');			
				array_push($diagnosis, $result1);    
				echo json_encode($diagnosis);
				return false;
		} else {
			return false;
		}

	}
	if(isset($_REQUEST['type']) && $_REQUEST['type'] == "search"){
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
                        if($search_tbl === "diagnosis") {
                            $where          = $_REQUEST["searchKey"]." IN ('".$_REQUEST['searchValue']."')";
                            $select_qry     = "CONCAT(LONG_DESCRIPTION ,' (', DCODE, ')') as name,DCODE as icd, DCODE, LONG_DESCRIPTION, user_defined, id";
                        } else {
                            echo json_encode($array);
                        }
                        
                        $sql = "SELECT $select_qry "
                                . "FROM  $search_tbl WHERE $where ";
						//printr($sql);
                        $result1 = $this->Family_model->dynamic_query($select_qry,$search_tbl,$where);
						//printr($this->db->last_query());
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
                    //echo "<pre>"; print_r($_REQUEST); exit;

                    $and_or         = $_REQUEST["andOr"];
                    
                    //$name           = $_REQUEST["name"];

                    $where          = "";
                    $select_qry     = "*";
                    $like_search    = "";
                    $order_by       = "";
                    $join       	= "";

                    if($search_tbl === "diagnosis")
                    {
                        $status         = $_REQUEST["status"];
                        $select_qry     = "CONCAT(LONG_DESCRIPTION ,' (', DCODE, ')') as name, DCODE as icd, DCODE, LONG_DESCRIPTION, user_defined, id";
                        $like_search    = "(LONG_DESCRIPTION LIKE '%".$search_term."%' OR DCODE LIKE '%".$search_term."%')";
                        $where          = " AND active = '".$status."' ";
                        $order_by       = "ORDER BY CASE WHEN LEFT(TRIM(LONG_DESCRIPTION), LENGTH('".$search_term."')) = '" . $search_term . "' THEN 1 ELSE 2 END";
					}
					
					$total_row      = $this->get_sp_search_list_count($db, $search_tbl, $like_search, $where, $join);

                   
					//printr($sql);
					$result2 = $this->Family_model->dynamic_query2nd($search_tbl,$join,$like_search,$where,$order_by,$page_number,$page_size);
					$result_arr = [];
                    while ($r = $result2){
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
	
    $result = $this->Family_model->getDataFromfamilyhx($this->user_id);
	foreach ($result as $key => $value) {
		if($value['diagnosis'] !== "")
			{
				$icd_code_exp = explode(',', str_replace('ICD10:','',$value['diagnosis']));
				$diagnosis = array();
				foreach($icd_code_exp as $icd_code)
				{
					$result2 = $this->Family_model->get_diagnosisDataForFamily($icd_code);
					array_push($diagnosis, array('code' => $result2['DCODE'], 'desc' => $result2['display_description']));
				}
				$result[$key]['diagnosis_data'] = $diagnosis;
				
		}
	}
	$result1 = $this->Patient_Portal_Changes_model->get_patient_data('familyhx');		
	array_push($result, $result1);    
	echo json_encode($result);	

}
//////////////////////////------- For Family/save.php --------/////////////////////////////////
public function family_save()
{
    $request = get_request_body();
	$request["patientId"] = $this->user_id;
    $output = str_replace(array("\r\n", "\n", "\r"),'',$request);
	$jsonData = json_encode($output);
    $table_name = "familyhx";
	$change_type = $request['editID'];

			///////------- For Adding Records
			
            $result = $this->Family_model->addData_patient_portal_changes($this->user_id,$table_name,$change_type,$jsonData,$request['hx_id']);
			if($result ){
				echo compileResponse(300, "<h1>Your Message has been sent to the clinic.<br/> please wait for them to review and respond.</h1>");
			}else{
				echo compileResponse(500, "Bad Parameters!!!");
			}
			///////------- For Adding Records

}

//////////////////////////------- For family/save.php --------/////////////////////////////////

private function get_sp_search_list_count($db, $search_tbl, $like_search, $where, $join) 
	{   
		
		$result =  $this->Family_model->get_sp_search_list_count($search_tbl,$join,$like_search,$where);
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

	
    //////////////////////////------- For Family/edit.php --------/////////////////////////////////
    public function family_edit()
    {
        $request = get_request_body();	
        $request["patientId"] = $this->user_id;        
        $request["datetime"] = date('Y-m-d h:i A');
        $output = str_replace(array("\r\n", "\n", "\r"),'',$request);
        $jsonData = json_encode($output);
        $table_name = $request['tb_name'];
        $change_type = $request['editID'];    

                ///////------- For Adding Records
                
                $result = $this->Family_model->editData_patient_portal_changes($request["id"],$this->user_id,$table_name,$change_type,$jsonData,$request['hx_id']);
                if($result ){
                    echo compileResponse(300, "Your Message has been updated and sent to the clinic. Please wait for them to review and respond.");
                }else{
                    echo compileResponse(500, "Bad Parameters!!!");
                }
                ///////------- For Adding Records

    }


}    