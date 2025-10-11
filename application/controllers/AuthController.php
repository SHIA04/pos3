<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form', 'security']);
        $this->load->library(['session', 'form_validation']);
        $this->load->model(['SignupModel', 'LoginModel']);
    }

    // Default route: go to signup page
    public function index() {
        redirect('auth/signup');
    }

    // ==============================
    // ✅ SIGNUP
    // ==============================
    public function signup() {
        $this->load->view('auth/signup');
    }

    public function register() {
        if ($this->input->method() !== 'post') {
            show_error('Invalid request method.', 405);
        }

        // ============================
        // ✅ FORM VALIDATION RULES
        // ============================
        $this->form_validation->set_rules('fullname', 'Full Name', 'required|regex_match[/^[a-zA-Z\s]+$/]');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[4]|max_length[20]');
        $this->form_validation->set_rules('age', 'Age', 'required|integer|greater_than[0]|less_than[120]');
        $this->form_validation->set_rules('sex', 'Sex', 'required');
        $this->form_validation->set_rules('birthday', 'Birthday', 'required');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[owner,staff]');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|exact_length[11]|numeric');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        // Run validation
        if ($this->form_validation->run() === FALSE) {
            $errors = explode("\n", strip_tags(validation_errors())); // array of errors
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => $errors]);
                return;
            } else {
                $this->session->set_flashdata('error', implode('<br>', $errors));
                redirect('auth/signup');
            }
        }

        // ============================
        // ✅ CUSTOM CHECKS
        // ============================
        $custom_errors = [];
        $email = $this->input->post('email', TRUE);
        $username = $this->input->post('username', TRUE);

        if ($this->SignupModel->email_exists($email)) {
            $custom_errors[] = 'Email already exists';
        }

        if (!empty($custom_errors)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => $custom_errors]);
                return;
            } else {
                $this->session->set_flashdata('error', implode('<br>', $custom_errors));
                redirect('auth/signup');
            }
        }

        // ============================
        // ✅ SANITIZE INPUTS
        // ============================
        $data = [
            'fullname' => xss_clean($this->input->post('fullname', TRUE)),
            'username' => xss_clean($username),
            'age' => (int)$this->input->post('age', TRUE),
            'sex' => xss_clean($this->input->post('sex', TRUE)),
            'birthday' => xss_clean($this->input->post('birthday', TRUE)),
            'role' => xss_clean($this->input->post('role', TRUE)),
            'phone_number' => xss_clean($this->input->post('phone_number', TRUE)),
            'email' => xss_clean($email),
            'password' => password_hash($this->input->post('password', TRUE), PASSWORD_BCRYPT),
            'confirm_password' => password_hash($this->input->post('confirm_password', TRUE), PASSWORD_BCRYPT),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // ============================
        // ✅ PROFILE IMAGE UPLOAD
        // ============================
        if (!empty($_FILES['profile_image']['name'])) {
            $config['upload_path'] = './uploads/profile_images/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('profile_image')) {
                $uploadData = $this->upload->data();
                $data['profile_image'] = $uploadData['file_name'];
            } else {
                $error_msg = strip_tags($this->upload->display_errors());
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'error', 'message' => [$error_msg]]);
                    return;
                } else {
                    $this->session->set_flashdata('error', $error_msg);
                    redirect('auth/signup');
                }
            }
        }

        // ============================
        // ✅ SAVE TO DATABASE
        // ============================
        if ($this->SignupModel->insert_user($data)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'role' => $data['role'], 'message' => 'Account Created']);
                return;
            } else {
                $this->session->set_flashdata('success', 'Registration successful!');
                redirect('auth/login');
            }
        } else {
            $error_msg = 'Failed to create account. Try again.';
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => [$error_msg]]);
                return;
            } else {
                $this->session->set_flashdata('error', $error_msg);
                redirect('auth/signup');
            }
        }
    }

    // ==============================
    // ✅ LOGIN
    // ==============================
    public function login() {
        $this->load->view('auth/login');
    }

    public function process_login() {
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('auth/login');
        }

        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        $user = $this->LoginModel->validate_user($username, $password);

        if ($user) {
            $this->session->set_userdata([
                'admin_id' => $user->admin_id,
                'username' => $user->username,
                'logged_in' => TRUE
            ]);
            $this->session->set_flashdata('success', 'Login successful!');
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Invalid username or password.');
            redirect('auth/login');
        }
    }

    // ==============================
    // ✅ LOGOUT
    // ==============================
    public function logout() {
        $this->session->unset_userdata(['admin_id', 'username', 'logged_in']);
        $this->session->sess_destroy();
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        $this->output->set_header('Pragma: no-cache');
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        redirect('auth/login');
    }
}
