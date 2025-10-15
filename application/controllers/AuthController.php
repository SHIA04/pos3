<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form', 'security']);
        $this->load->library(['session', 'form_validation']);
        $this->load->model(['SignupModel', 'LoginModel']);

        // Load RememberModel if exists (for "Remember Me" feature)
        if (file_exists(APPPATH . 'models/RememberModel.php')) {
            $this->load->model('RememberModel');
            // Attempt auto-login via remember cookie if session is not active
            if (!$this->session->userdata('logged_in')) {
                $cookieName = 'rf_pos_remember';
                if (!empty($_COOKIE[$cookieName])) {
                    $token = $_COOKIE[$cookieName];
                    // token stored as raw (not hashed) in cookie; we will hash on DB compare
                    $token_hash = hash('sha256', $token);
                    $rec = $this->RememberModel->find_by_hash($token_hash);
                    if ($rec && isset($rec->signup_id)) {
                        // load user and set session
                        $user = $this->db->get_where('tbl_signup', ['signup_id' => $rec->signup_id])->row();
                        if ($user) {
                            $this->session->set_userdata([
                                'signup_id' => $user->signup_id,
                                'user_id' => $user->signup_id,
                                'username' => $user->username,
                                'role' => $user->role,
                                'logged_in' => TRUE
                            ]);
                        }
                    }
                }
            }
        }
    }

    // Default route: go to signup page
    public function index() {
        redirect('auth/signup');
    }

    // ==============================
    // ✅ SIGNUP
    // ==============================
    public function signup() {
        // If already logged in, send to appropriate dashboard
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role') ?: 'staff';
            $redirectPath = ($role === 'owner' ? 'owner/dashboard' : 'staff/dashboard');
            return redirect($redirectPath);
        }
        $this->load->view('auth/signup');
    }

    public function register()
    {
        // If already logged in, don't allow registration flow
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role') ?: 'staff';
            $redirectPath = ($role === 'owner' ? 'owner/dashboard' : 'staff/dashboard');
            return redirect($redirectPath);
        }
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
            // Secure flow: do NOT email plaintext password. Instead create a one-time token
            // and send a set-password link to the user.
            $this->load->model('PasswordResetModel');
            $this->load->library('email');

            // Find the inserted user's id - assume email is unique
            $user_row = $this->db->where('email', $data['email'])->limit(1)->get('tbl_signup')->row();
            $email_sent = false;
            if ($user_row) {
                $token = $this->PasswordResetModel->create_token($user_row->signup_id, 60*60*24); // 24 hours
                if ($token !== false) {
                    try {
                        // Build set-password URL
                        $encoded = rawurlencode($token);
                        $setUrl = site_url('auth/set_password?token=' . $encoded);

                        // Initialize email using config in application/config/email.php
                        $config = ['mailtype' => 'html', 'charset' => 'utf-8'];
                        $this->email->initialize($config);
                        $from_address = 'no-reply@localhost';
                        $from_name = 'Order Flow POS';
                        $this->email->from($from_address, $from_name);
                        $this->email->to($data['email']);
                        $this->email->subject('Set your Order Flow account password');
                        $message = '<p>Hi ' . html_escape($data['fullname']) . ',</p>';
                        $message .= '<p>Your account has been created. For security, set your password using the link below. This link expires in 24 hours and can be used only once.</p>';
                        $message .= '<p><a href="' . $setUrl . '">Set your password</a></p>';
                        $message .= '<p>If you did not request this, please ignore this email or contact the administrator.</p>';
                        $this->email->message($message);
                        $email_sent = $this->email->send();
                        if (!$email_sent) {
                            // Log for CI. (Temporary local debug write removed.)
                            $dbg = $this->email->print_debugger(['headers']);
                            log_message('error', 'Signup set-password email failed: ' . $dbg);
                        }
                    } catch (Exception $ex) {
                        log_message('error', 'Exception while sending set-password email: ' . $ex->getMessage());
                    }
                } else {
                    log_message('error', 'Failed to create password reset token for signup_id: ' . $user_row->signup_id);
                }
            } else {
                log_message('error', 'Inserted user not found by email: ' . $data['email']);
            }
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'role' => $data['role'], 'message' => 'Account Created', 'email_sent' => $email_sent]);
                return;
            } else {
                $this->session->set_flashdata('success', 'Registration successful! ' . ($email_sent ? 'A confirmation email has been sent.' : 'Unable to send confirmation email.'));
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
        // If already logged in, redirect to dashboard
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role') ?: 'staff';
            $redirectPath = ($role === 'owner' ? 'owner/dashboard' : 'staff/dashboard');
            return redirect($redirectPath);
        }
        $this->load->view('auth/login');
    }

    public function process_login()
    {
        // If the user is already logged in, return them to their dashboard
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role') ?: 'staff';
            $redirectPath = ($role === 'owner' ? 'owner/dashboard' : 'staff/dashboard');
            // If AJAX/fetch, respond with JSON redirect, else simple redirect
            $accept = $this->input->get_request_header('Accept', TRUE);
            $isAjax = $this->input->is_ajax_request() || (is_string($accept) && strpos($accept, 'application/json') !== false);
            if ($isAjax) {
                return $this->output->set_content_type('application/json')->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Already logged in',
                    'redirect' => site_url($redirectPath),
                    'csrf_token_name' => $this->security->get_csrf_token_name(),
                    'csrf_hash' => $this->security->get_csrf_hash()
                ]));
            }
            return redirect($redirectPath);
        }
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

            // Handle "Remember Me" checkbox: create persistent token if requested
            try {
                $remember = $this->input->post('remember_me') ? TRUE : FALSE;
            } catch (Exception $e) {
                $remember = FALSE;
            }

            if (!empty($remember) && isset($this->RememberModel)) {
                // raw token for cookie
                $raw = bin2hex(random_bytes(32));
                $hash = hash('sha256', $raw);
                $expires = date('Y-m-d H:i:s', time() + (30*24*60*60)); // 30 days
                $this->RememberModel->create($user->signup_id, $hash, $expires);
                // set cookie (HttpOnly, Secure if using HTTPS)
                setcookie('rf_pos_remember', $raw, time() + (30*24*60*60), '/', '', isset($_SERVER['HTTPS']), true);
            }

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
        // Clear remember cookie and mark tokens used for this user if model available
        $cookieName = 'rf_pos_remember';
        if (!empty($_COOKIE[$cookieName])) {
            // expire cookie
            setcookie($cookieName, '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
        }
        if (isset($this->RememberModel) && $this->session->userdata('signup_id')) {
            // mark any tokens for this user as used
            $this->db->where('signup_id', $this->session->userdata('signup_id'))->update('remember_tokens', ['used' => 1]);
        }
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        $this->output->set_header('Pragma: no-cache');
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        redirect('auth/login');
    }

    /**
     * Show set password form when user clicks token link
     */
    public function set_password()
    {
        $token = $this->input->get('token', TRUE);
        if (empty($token)) {
            show_error('Invalid token', 400);
        }
        // We will render a simple view with hidden token field
        $data = ['token' => $token];
        $this->load->view('auth/set_password', $data);
    }

    /**
     * Process POST from set_password form
     */
    public function process_set_password()
    {
        if ($this->input->method() !== 'post') {
            show_error('Invalid request method.', 405);
        }

        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        $this->form_validation->set_rules('token', 'Token', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            return redirect('auth/set_password?token=' . rawurlencode($this->input->post('token', TRUE)));
        }

        $token = $this->input->post('token', TRUE);
        $this->load->model('PasswordResetModel');
        $reset = $this->PasswordResetModel->verify_token($token);
        if (!$reset) {
            $this->session->set_flashdata('error', 'Invalid or expired token.');
            return redirect('auth/login');
        }

        // Update user password
        $new_hash = password_hash($this->input->post('password', TRUE), PASSWORD_BCRYPT);
        $ok = $this->db->where('signup_id', $reset->signup_id)->update('tbl_signup', ['password' => $new_hash]);
        if ($ok) {
            // Mark token used
            $this->PasswordResetModel->mark_used($reset->id);
            $this->session->set_flashdata('success', 'Password set successfully. You may now login.');
            return redirect('auth/login');
        } else {
            $this->session->set_flashdata('error', 'Unable to set password. Try again.');
            return redirect('auth/set_password?token=' . rawurlencode($token));
        }
    }
}