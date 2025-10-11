<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SettingsController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form', 'security']);
        $this->load->library(['session', 'form_validation']);
        $this->load->model('SettingsModel');

        // ✅ Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    // Load the settings page
    public function index() {
        $admin_id = $this->session->userdata('admin_id');
        $data['admin'] = $this->SettingsModel->get_admin($admin_id);
        $this->load->view('settings', $data);
    }

    // Process form submission
    public function update() {
        if ($this->input->method() !== 'post') {
            show_error('Invalid request method', 405);
        }

        $admin_id = $this->session->userdata('admin_id');
        $username = xss_clean($this->input->post('username', TRUE));
        $password = $this->input->post('password', TRUE);
        $confirm_password = $this->input->post('confirm_password', TRUE);

        // ✅ Backend validation
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[4]');
        if (!empty($password)) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'matches[password]');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('settings');
        }

        // ✅ Prepare data
        $update_data = ['username' => $username];
        if (!empty($password)) {
            $update_data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        // ✅ Update DB
        if ($this->SettingsModel->update_admin($admin_id, $update_data)) {
            $this->session->set_flashdata('success', 'Account updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'No changes made or update failed.');
        }

        redirect('settings');
    }
}
