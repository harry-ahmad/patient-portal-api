<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_pdf {
	function __construct()
	{
		$CI = & get_instance();
		log_message('Debug', 'mPDF class is loaded.');
	}
	
	function load($param=NULL)
	{
		//echo phpversion();
		//include_once APPPATH.'/third_party/mpdf/mpdf.php';
		 
		/*if ($params == NULL)
		{
			$param = '"en-GB-x","A4","","",10,10,10,10,6,3';          
		}*/
		 
		//return new mPDF();
		
		//require_once __DIR__ . '/vendor/autoload.php';
		
		if(phpversion() >= 7)	{		
			include_once APPPATH.'/third_party/mpdf_7/vendor/autoload.php';
			$mpdfConfig = array(
                'mode' => 'utf-8', 
                'format' => 'A4',
                'margin_header' => 1,     // 30mm not pixel
                'margin_footer' => 10,     // 10mm
                'orientation' => 'P'    
            );
			return new \Mpdf\Mpdf($mpdfConfig);
		}else{
			include_once APPPATH.'/third_party/mpdf/mpdf.php';
		}
	}
}