<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Configuration - Gmail SMTP Example
|--------------------------------------------------------------------------
|
| This configuration allows CodeIgniter 3 to send emails using Gmail SMTP.
| Make sure to use a Gmail App Password (not your actual Gmail password).
|
| For local testing, you can also use Mailtrap or MailHog.
| Keep credentials private and do not push this file to public repos.
|
*/

$config['protocol']    = 'smtp';
$config['smtp_host']   = 'smtp.gmail.com';
$config['smtp_port']   = 587;
$config['smtp_user']   = 'jaytagolimotreyes@gmail.com';  // your Gmail
$config['smtp_pass']   = 'knhidkynvkkcfofv';             // your App Password
$config['smtp_crypto'] = 'tls';
$config['mailtype']    = 'html';
$config['charset']     = 'utf-8';
$config['newline']     = "\r\n";
$config['crlf']        = "\r\n";  // ensures proper line breaks
$config['wordwrap']    = TRUE;
$config['priority']    = 1;

/*
|--------------------------------------------------------------------------
| Optional: Load via environment variables
|--------------------------------------------------------------------------
| You can replace the hardcoded credentials above with:
| getenv('SMTP_USER') and getenv('SMTP_PASS') for better security.
|
| Example:
| $config['smtp_user'] = getenv('SMTP_USER');
| $config['smtp_pass'] = getenv('SMTP_PASS');
|
*/

