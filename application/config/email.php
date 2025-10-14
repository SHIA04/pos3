<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Configuration - Gmail SMTP Example
|--------------------------------------------------------------------------
|
| Copy this file to your CodeIgniter `application/config/` if it does not
| already exist. This example uses Gmail's SMTP server. For security, use
| an App Password (recommended) rather than your account password.
|
| Notes:
| - If you have 2FA on your Google account, create an App Password and use it
|   instead of your regular password.
| - For local development, consider using MailHog/Mailtrap and change the
|   SMTP host/port accordingly.
| - Keep credentials out of version control; prefer environment variables.
*/

$mailer = getenv('MAILER') ?: 'gmail';

$config = array(
    'protocol' => 'smtp',
    // Choose host/port/user/pass based on MAILER env var: 'gmail' (default), 'mailtrap', 'mailhog'
    'smtp_host' => ($mailer === 'mailtrap') ? 'smtp.mailtrap.io' : (($mailer === 'mailhog') ? '127.0.0.1' : 'ssl://smtp.gmail.com'),
    'smtp_port' => ($mailer === 'mailtrap') ? 2525 : (($mailer === 'mailhog') ? 1025 : 465),
    'smtp_user' => ($mailer === 'mailtrap') ? (getenv('MAILTRAP_USER') ?: 'your_mailtrap_user') : (getenv('SMTP_USER') ?: 'your@gmail.com'),
    'smtp_pass' => ($mailer === 'mailtrap') ? (getenv('MAILTRAP_PASS') ?: 'your_mailtrap_pass') : (getenv('SMTP_PASS') ?: 'your_app_password'),
    'smtp_timeout' => 30,
    'charset' => 'utf-8',
    'mailtype' => 'html',
    'wordwrap' => TRUE,
    'newline' => "\r\n",
    'crlf' => "\r\n"
);

/*
Example environment variables (Windows PowerShell):
# Gmail (use App Password if you have 2FA enabled)
$env:MAILER = 'gmail'
$env:SMTP_USER = 'your@gmail.com'
$env:SMTP_PASS = 'your_app_password'

# Mailtrap (recommended for local dev - create an inbox and copy credentials)
$env:MAILER = 'mailtrap'
$env:MAILTRAP_USER = 'your_mailtrap_user'
$env:MAILTRAP_PASS = 'your_mailtrap_pass'

# MailHog (local SMTP server, no auth; run MailHog and set MAILER=mailhog)
$env:MAILER = 'mailhog'

Or add to your system environment variables or an .env loader.
*/

return $config;
