<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * StaffController
 *
 * Handles staff-facing pages and actions (dashboard, profile, etc.).
 *
 * Notes:
 * - Ensure your routes map staff URLs to this controller, e.g.:
 *     $route['staff/dashboard'] = 'StaffController/dashboard';
 *     $route['staff/profile']   = 'StaffController/profile';
 *     $route['staff/profile/update'] = 'StaffController/update_profile';
 *
 * - Expects session data set by AuthController:
 *     - user_id (int|string)
 *     - role    (string) e.g., 'staff'
 *     - name    (string) optional
 *     - email   (string) optional
 *
 * - Views expected (create as needed):
 *     application/views/staff/dashboard.php
 *     application/views/staff/profile.php
 */
class StaffController extends CI_Controller
{
    /**
     * @var array|null Authenticated user data from session.
     */
    protected $user;

    public function __construct()
    {
        parent::__construct();

        // Load helpers/libraries commonly needed
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);

        // Optionally load a model for staff-specific data operations
        // $this->load->model('Staff_model');

        // Gate: must be logged in
        if (!$this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Please log in to continue.');
            redirect('auth/login');
            return;
        }

        // Basic user context
        $this->user = [
            'user_id' => $this->session->userdata('user_id'),
            'role'    => $this->session->userdata('role'),
            'name'    => $this->session->userdata('name'),
            'email'   => $this->session->userdata('email'),
        ];

        // Gate: must be staff
        if (!$this->isStaff()) {
            // If you want to allow owners/admins to access staff section, adjust this check.
            $this->forbidden('You do not have permission to access the staff area.');
            return;
        }
    }

    /**
     * Default entrypoint -> redirect to dashboard for clarity.
     */
    public function index()
    {
        redirect('staff/dashboard');
    }

    /**
     * Staff Dashboard
     */
    public function dashboard()
    {
        // Load models needed for dashboard metrics
        $this->load->model('OrderModel');
        $this->load->model('MenuModel');

        $userId = $this->user['user_id'];

        // Fetch counts
        $newOrdersCount = $this->OrderModel->count_by_status('New');
        $processingOrdersCount = $this->OrderModel->count_by_status('Processing');
        $myCompletedToday = $this->OrderModel->count_user_completed_today($userId);

        // Low stock items and recent completed orders for this staff
        $lowStockItems = $this->MenuModel->get_low_stock(5);
        $recentCompletedOrders = $this->OrderModel->get_recent_completed_by_user($userId, 5);

        $data = [
            'page_title' => 'Staff Dashboard',
            'user' => $this->user,
            'new_orders_count' => (int)$newOrdersCount,
            'processing_orders_count' => (int)$processingOrdersCount,
            'my_completed_orders_today' => (int)$myCompletedToday,
            'low_stock_items' => $lowStockItems,
            'recent_completed_orders' => $recentCompletedOrders,
        ];

        $this->load->view('staff/dashboard', $data);
    }

    /**
     * Show profile page for the staff member.
     */
    public function profile()
    {
        // Example: fetch profile from model; fallback to session
        // $profile = $this->Staff_model->get_profile($this->user['user_id']);
        $profile = [
            'full_name' => $this->user['name'] ?? '',
            'email'     => $this->user['email'] ?? '',
            'phone'     => '', // populate from DB if available
        ];

        $data = [
            'page_title' => 'My Profile',
            'user'       => $this->user,
            'profile'    => $profile,
        ];

        $this->load->view('staff/profile', $data);
    }

    /**
     * Handle profile updates (POST).
     */
    public function update_profile()
    {
        // Only allow POST
        if (strtoupper($this->input->method()) !== 'POST') {
            redirect('staff/profile');
            return;
        }

        // Validation rules
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[150]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');

        if ($this->form_validation->run() === FALSE) {
            // Re-render profile with validation errors
            $this->profile();
            return;
        }

        // Sanitized input
        $payload = [
            'full_name'  => $this->input->post('full_name', TRUE),
            'email'      => $this->input->post('email', TRUE),
            'phone'      => $this->input->post('phone', TRUE),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Persist changes with your model
        // $updated = $this->Staff_model->update_profile($this->user['user_id'], $payload);

        // For now, emulate success and update session display values
        // if ($updated) { ... } else { ... }
        $this->session->set_userdata([
            'name'  => $payload['full_name'],
            'email' => $payload['email'],
        ]);

        $this->session->set_flashdata('success', 'Profile updated successfully.');
        redirect('staff/profile');
    }

    /**
     * Utility: Determine if the current user is staff.
     *
     * @return bool
     */
    protected function isStaff()
    {
        return isset($this->user['role']) && $this->user['role'] === 'staff';
    }

    /**
     * Utility: Emit a 403 and optionally show a message.
     *
     * @param string $message
     */
    protected function forbidden($message = 'Access denied.')
    {
        // You can customize this to load a view instead.
        $this->output->set_status_header(403);
        show_error($message, 403, 'Forbidden');
    }

    public function menu()
    {
        // Optional: ensure user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return;
        }
        // Load menu items from database and show staff menu view.
        $this->load->model('MenuModel');
        $data = [];
        $data['menu_items'] = $this->MenuModel->get_all();

        if (file_exists(APPPATH.'views/staff/menu/menu.php')) {
            $this->load->view('staff/menu/menu', $data);
        } else {
            show_404();
        }
    }

    public function order()
    {
        // Optional: ensure user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return;
        }

        // Load menu items and orders from database for the staff order page
        $this->load->model('MenuModel');
        $this->load->model('OrderModel');
        $data = [];
        $data['menu_items'] = $this->MenuModel->get_all();
        $data['orders'] = $this->OrderModel->get_all_orders();

        // Load your order view with data
        if (file_exists(APPPATH.'views/staff/orders/orders.php')) {
            $this->load->view('staff/orders/orders', $data);
        } else {
            show_404();
        }
}

}