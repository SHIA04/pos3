<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
*/

$active_group  = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'      => '',
    // If you have connection issues on some stacks, try '127.0.0.1' instead of 'localhost'
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'pos3',           // <- your database name
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    // Use utf8mb4 for full Unicode (emoji-safe)
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt'  => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE,
    // If MySQL runs on a non-default port, you can append it to hostname, e.g. '127.0.0.1:3307'
);