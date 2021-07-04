<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Login_Ctrl';
$route['login']   		     = 'Login_Ctrl/check_login';
$route['logout']   		     = 'Login_Ctrl/logout_user';

//////----------OTP_Ctrl routes
$route['resendOTP']   		 = 'OTP_Ctrl/send_OTP';
$route['verifyOTP']   		 = 'OTP_Ctrl/verifyOTP';
$route['changePassword']   	 = 'OTP_Ctrl/changePassword';

//////----------Allergy_Ctrl routes
$route['getlist']   		 = 'Allergy_Ctrl/getlist';
$route['save']   		     = 'Allergy_Ctrl/save';

//////----------Appointment_Ctrl routes
$route['calender']   	     = 'Appointment_Ctrl/calendere';
$route['calenderevents']     = 'Appointment_Ctrl/calenderevents';
$route['appoitment_list']    = 'Appointment_Ctrl/appoitment_list';
$route['appoitment_search']  = 'Appointment_Ctrl/appoitment_search';
$route['appoitment_time']    = 'Appointment_Ctrl/appoitment_time';
$route['appoitment_save']    = 'Appointment_Ctrl/appoitment_save';
$route['provider_list']      = 'Appointment_Ctrl/provider_list';
$route['appoitment_hours']      = 'Appointment_Ctrl/appoitment_hours';

//////----------Bloodglucose_Ctrl routes
$route['bloodglucose_list']    = 'Bloodglucose_Ctrl/bloodglucose_list';
$route['bloodglucose_save']    = 'Bloodglucose_Ctrl/bloodglucose_save';

//////----------Bloodpressure_Ctrl routes
$route['bloodpressure_list']    = 'Bloodpressure_Ctrl/bloodpressure_list';
$route['bloodpressure_save']    = 'Bloodpressure_Ctrl/bloodpressure_save';

//////----------Bmi_Ctrl routes
$route['bmi_list']    = 'Bmi_Ctrl/bmi_list';
$route['bmi_save']    = 'Bmi_Ctrl/bmi_save';

//////----------Cholesterol_Ctrl routes
$route['cholesterol_list']    = 'Cholesterol_Ctrl/cholesterol_list';
$route['cholesterol_save']    = 'Cholesterol_Ctrl/cholesterol_save';

//////----------Dashboard_Ctrl routes
$route['dashboard']    = 'Dashboard_Ctrl/dashboard';
$route['filteredList']    = 'Dashboard_Ctrl/filteredList';

//////----------Report_Ctrl routes
$route['generate_report']    = 'Report_Ctrl/generate_report';

//////----------Insurance_Ctrl routes
$route['insurance_list']    = 'Insurance_Ctrl/insurance_list';
$route['insurance_save']    = 'Insurance_Ctrl/insurance_save';

//////----------Family_Ctrl routes
$route['family_list']    = 'Family_Ctrl/family_list';
$route['family_save']    = 'Family_Ctrl/family_save';
//////----------Measurement_Ctrl routes
$route['measurement_list']    = 'Measurement_Ctrl/measurement_list';
$route['measurement_save']    = 'Measurement_Ctrl/measurement_save';

//////----------Notification_Ctrl routes
$route['notification_list']    = 'Notification_Ctrl/notification_list';

//////----------surgical_Ctrl routes
$route['surgical_list']    = 'Surgical_Ctrl/surgical_list';
$route['surgical_search']    = 'Surgical_Ctrl/surgical_search';
$route['surgical_save']    = 'Surgical_Ctrl/surgical_save';
$route['delete_surg']      = 'Surgical_Ctrl/delete_surg';
//////----------patient_Ctrl routes
$route['patient_list']    = 'Patient_Ctrl/patient_list';
$route['patient_save']    = 'Patient_Ctrl/patient_save';
$route['patient_file_upload']    = 'Patient_Ctrl/patient_file_upload';

///////----------Medical_Ctrl routes
$route['deleteDx']        = 'Medical_Ctrl/deleteDx';
$route['deleteRx']        = 'Medical_Ctrl/deleteRx';
$route['editDx']          = 'Medical_Ctrl/editDx';
$route['medical_list']    = 'Medical_Ctrl/medical_list';
$route['medical_search']  = 'Medical_Ctrl/medical_search';
$route['medical_save']    = 'Medical_Ctrl/medical_save';
$route['psyc_list']       = 'Medical_Ctrl/psyc_list';
$route['gyne_list']       = 'Medical_Ctrl/gyne_list';
$route['medical_status']  = 'Medical_Ctrl/medical_status';


///////----------Dashboard_Ctrl routes
$route['dashboard']        = 'Dashboard_Ctrl/dashboard';

///////----------Getzipvals_Ctrl routes
$route['getzipvals_func']        = 'Getzipvals_Ctrl/getzipvals_func';
///////----------Document_Ctrl routes
$route['document_list']        = 'Document_Ctrl/document_list';
$route['save_document']        = 'Document_Ctrl/save_document';
///////----------Options_Ctrl routes
$route['getOptionsLists']        = 'Options_Ctrl/getOptionsLists_func';
$route['getOptions']        = 'Options_Ctrl/getOptions_func';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
