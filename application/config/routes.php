<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
*/

$route['default_controller'] = 'authcontroller'; // default goes to signup
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// ==============================
// Auth Routes
// ==============================
$route['auth'] = 'authcontroller';              // default redirect to signup
$route['auth/signup'] = 'authcontroller/signup';
$route['auth/register'] = 'authcontroller/register';
$route['auth/login'] = 'authcontroller/login';
$route['auth/process_login'] = 'authcontroller/process_login';
$route['auth/logout'] = 'authcontroller/logout';

// ==============================
// Dashboards (after login)
// ==============================
$route['owner/dashboard'] = 'ownercontroller/dashboard'; // owner dashboard
$route['staff/dashboard'] = 'staffcontroller/dashboard'; // staff dashboard


// ==============================
// Optional: other pages
// ==============================
// $route['dashboard'] = 'dashboardcontroller/index';
// $route['profile'] = 'profilecontroller/index';
