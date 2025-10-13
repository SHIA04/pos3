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

    public function register()
    {
        if ($this->input->method() !== 'post') {
            show_error('Invalid request method.', 405);
        }

        // Ensure model is loaded
        $this->load->model('SignupModel');

        // ============================
        // ✅ FORM VALIDATION RULES
        // ============================
        $this->form_validation->set_rules('fullname', 'Full Name', 'required|regex_match[/^[a-zA-Z\s]+$/]');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[4]|max_length[20]');
        $this->form_validation->set_rules('age', 'Age', 'required|integer|greater_than[0]|less_than[120]');
        $this->form_validation->set_rules('sex', 'Sex', 'required');
        $this->form_validation->set_rules('birthday', 'Birthday', 'required');

        // Include admin here
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[owner,staff,admin]');

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
        if ($this->SignupModel->username_exists($username)) {
            $custom_errors[] = 'Username already exists';
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
            'fullname'     => xss_clean($this->input->post('fullname', TRUE)),
            'username'     => xss_clean($username),
            'age'          => (int)$this->input->post('age', TRUE),
            'sex'          => xss_clean($this->input->post('sex', TRUE)),
            'birthday'     => xss_clean($this->input->post('birthday', TRUE)),
            'role'         => xss_clean($this->input->post('role', TRUE)),
            'phone_number' => xss_clean($this->input->post('phone_number', TRUE)),
            'email'        => xss_clean($email),
            // Store only the main password hash
            'password'     => password_hash($this->input->post('password', TRUE), PASSWORD_BCRYPT),
            // Do NOT store confirm_password
            'created_at'   => date('Y-m-d H:i:s')
        ];

        // ============================
        // ✅ PROFILE IMAGE UPLOAD
        // ============================
        if (!empty($_FILES['profile_image']['name'])) {
            $config['upload_path']   = './uploads/profile_images/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['encrypt_name']  = TRUE;
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

    public function process_login()
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            show_error('Method Not Allowed', 405);
        }

        // Allow letters, numbers, underscores and dashes in username
        $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_dash');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');

        // Detect AJAX/fetch requests
        $accept = $this->input->get_request_header('Accept', TRUE);
        $isAjax = $this->input->is_ajax_request() || (is_string($accept) && strpos($accept, 'application/json') !== false);

        if ($this->form_validation->run() === FALSE) {
            if ($isAjax) {
                // Return validation errors as JSON array and include a fresh CSRF token
                $errors = array_values($this->form_validation->error_array());
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status'  => 'error',
                        'message' => !empty($errors) ? $errors : [strip_tags(validation_errors())],
                        'csrf_token_name' => $this->security->get_csrf_token_name(),
                        'csrf_hash' => $this->security->get_csrf_hash()
                    ]));
            } else {
                $this->session->set_flashdata('error', validation_errors());
                return redirect('auth/login');
            }
        }

        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        // Validate using LoginModel against tbl_signup
        $user = $this->LoginModel->validate_user($username, $password);

        // Optional debug: if the AJAX request sends _debug_csrf=1, return the received POST/Cookie values
        if ($isAjax && $this->input->post('_debug_csrf') == '1') {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'debug',
                    'post_csrf' => isset($_POST[$this->security->get_csrf_token_name()]) ? $_POST[$this->security->get_csrf_token_name()] : null,
                    'cookie_csrf' => isset($_COOKIE[$this->security->get_csrf_cookie_name()]) ? $_COOKIE[$this->security->get_csrf_cookie_name()] : null,
                    'csrf_token_name' => $this->security->get_csrf_token_name(),
                    'csrf_hash' => $this->security->get_csrf_hash()
                ]));
        }

        if ($user) {
            // Role: 'owner' | 'staff' | 'admin'
            $role = isset($user->role) ? $user->role : null;

            // Regenerate session ID to prevent fixation
            if (method_exists($this->session, 'sess_regenerate')) {
                $this->session->sess_regenerate(TRUE);
            }

            // Store correct identifiers in session for tbl_signup
            $this->session->set_userdata([
                'signup_id' => $user->signup_id,
                'user_id'   => $user->signup_id,
                'username'  => $user->username,
                'role'      => $role,
                'logged_in' => TRUE
            ]);

            // Since your view folder is views/dashboard, route everyone to /dashboard controller
            $redirectPath = ($role === 'owner' ? 'owner/dashboard' : 'staff/dashboard');

            if ($isAjax) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status'   => 'success',
                        'message'  => 'Login successful!',
                        'role'     => $role,
                        'redirect' => site_url($redirectPath),
                        'csrf_token_name' => $this->security->get_csrf_token_name(),
                        'csrf_hash' => $this->security->get_csrf_hash()
                    ]));
            } else {
                $this->session->set_flashdata('success', 'Login successful!');
                return redirect($redirectPath);
            }
        } else {
            if ($isAjax) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status'  => 'error',
                        'message' => 'Invalid username or password.',
                        'csrf_token_name' => $this->security->get_csrf_token_name(),
                        'csrf_hash' => $this->security->get_csrf_hash()
                    ]));
            } else {
                $this->session->set_flashdata('error', 'Invalid username or password.');
                return redirect('auth/login');
            }
        }
    }

    // ==============================
    // ✅ LOGOUT
    // ==============================
    public function logout() {
        // Unset correct keys based on tbl_signup
        $this->session->unset_userdata(['signup_id', 'username', 'role', 'logged_in']);
        $this->session->sess_destroy();
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        $this->output->set_header('Pragma: no-cache');
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        redirect('auth/login');
    }
}