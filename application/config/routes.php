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

//////----------Allergy_Ctrl routes
$route['getlist']   		 = 'Allergy_Ctrl/getlist';
$route['save']   		     = 'Allergy_Ctrl/save';

//////----------Appointment_Ctrl routes
$route['calender']   	     = 'Appointment_Ctrl/calendere';
$route['calenderevents']     = 'Appointment_Ctrl/calenderevents';
$route['appoitment_list']    = 'Appointment_Ctrl/appoitment_list';

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

//////----------Insurance_Ctrl routes
$route['insurance_list']    = 'Insurance_Ctrl/insurance_list';

//////----------Family_Ctrl routes
$route['family_list']    = 'Family_Ctrl/family_list';
$route['family_save']    = 'Family_Ctrl/family_save';
//////----------surgical_Ctrl routes
$route['surgical_list']    = 'Surgical_Ctrl/surgical_list';
$route['surgical_save']    = 'Surgical_Ctrl/surgical_save';
$route['delete_surg']      = 'Surgical_Ctrl/delete_surg';
//////----------patient_Ctrl routes
$route['patient_list']    = 'Patient_Ctrl/patient_list';
$route['patient_save']    = 'Patient_Ctrl/patient_save';
$route['patient_file_upload']    = 'Patient_Ctrl/patient_file_upload';




$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
