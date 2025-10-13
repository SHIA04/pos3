<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
*/

$route['default_controller'] = 'AuthController/signup'; // default goes to signup
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| ==============================
| Auth Routes
| ==============================
*/
$route['auth']                = 'AuthController';
$route['auth/signup']         = 'AuthController/signup';
$route['auth/register']       = 'AuthController/register';
$route['auth/login']          = 'AuthController/login';
$route['auth/process_login']  = 'AuthController/process_login';
$route['auth/logout']         = 'AuthController/logout';

/*
| ==============================
| Dashboards (after login)
| ==============================
| Map dashboard routes to the correct controller class name.
*/
$route['dashboard']        = 'DashboardController/index';
$route['owner/dashboard']  = 'DashboardController/index';
$route['owner/menu']  = 'DashboardController/menu';
$route['owner/menu/add'] = 'DashboardController/add_menu_item';
$route['owner/menu/get'] = 'DashboardController/get_menu_item';
$route['owner/menu/update'] = 'DashboardController/update_menu_item';
$route['owner/menu/delete'] = 'DashboardController/delete_menu_item';
$route['owner/orders']  = 'DashboardController/orders';
$route['owner/inventory']  = 'DashboardController/inventory';
$route['owner/inventory/restock'] = 'DashboardController/restock';
$route['owner/inventory/get'] = 'DashboardController/get_inventory_item';
$route['owner/inventory/update'] = 'DashboardController/update_inventory_item';
$route['owner/sales']  = 'DashboardController/sales';
$route['owner/settings']  = 'DashboardController/settings'; 



$route['staff/dashboard']  = 'StaffController/dashboard';
$route['staff/menu']  = 'StaffController/menu';
$route['staff/order']  = 'StaffController/order';
/*
| ==============================
| Optional: other pages
| ==============================
*/
// $route['profile'] = 'ProfileController/index';

/*
| ==============================
| Migrations
| ==============================
*/
$route['migrate'] = 'migrate/index';
