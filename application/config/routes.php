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

//$route['admin'] = 'welcome/welcome';

## LOGIN / PROFILE
//$route['admin'] = 'login/login/index';
$route['log-in'] = 'login/login/index';
$route['log-out'] = 'login/login/logout';
$route['profile'] = 'login/login/profile';
$route['profile/edit'] = 'login/login/profile_edit';
$route['profile/change-password'] = 'login/login/change_password';
$route['forgot-password'] = 'login/login/forgot_password';
$route['reset-password/(:any)'] = 'login/login/reset_password/$1';
$route['capcha/(:any)'] = 'login/login/capcha/$1';
$route['phpinfo'] = 'login/login/phpinfo';
$route['validate-password'] = 'login/login/validate_password';
$route['email-available'] = 'login/login/email_available';

## DASHBOARD
$route['dashboard'] = 'dashboard/dashboard/index';
$route['dashboard/search'] = 'dashboard/dashboard/search';


## USERS 
$route['users'] = 'users/users/index';
$route['user/add'] = 'users/users/add';
$route['user/search'] = 'users/users/search';
$route['user/edit/(:any)'] = 'users/users/edit/$1';
$route['users/delete'] = 'users/users/delete';
$route['user/delete/(:any)'] = 'users/users/delete/$1';
$route['users/active-inactive'] = 'users/users/active_inactive';
$route['user/view/(:any)'] = 'users/users/view/$1';
$route['users/export/(:any)'] = 'users/users/export/$1';
$route['users/reset-password/(:any)'] = 'users/users/reset_password/$1';
$route['users/validate/verify_email'] = 'users/users/verify_email';

## PROJECTS 
$route['projects'] = 'projects/Projects/index';
$route['projects/search'] = 'projects/Projects/search';
$route['projects/view/(:any)'] = 'projects/Projects/view/$1';
$route['projects/export/(:any)'] = 'projects/Projects/export/$1';


## Payment Type
$route['NDA'] = 'nda/Nda/index';


require_once(getcwd()."/application/config/routes_api.php");

$route['default_controller'] = 'login/login/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;