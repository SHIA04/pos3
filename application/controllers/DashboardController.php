<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url']);
        $this->load->library(['session']);

        // ✅ Block access if not logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // ✅ Prevent caching (stops going back after logout)
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    public function index() {
        $data['username'] = $this->session->userdata('username');
        $this->load->view('dashboard', $data);
    }
}
