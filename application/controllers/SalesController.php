<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('OrderModel');
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
    }

    // Display Sales Dashboard
    public function index() {
        $data['daily_sales'] = $this->OrderModel->get_sales_total('daily');
        $data['weekly_sales'] = $this->OrderModel->get_sales_total('weekly');
        $data['monthly_sales'] = $this->OrderModel->get_sales_total('monthly');

        $data['daily_chart'] = $this->OrderModel->get_chart_data('daily');
        $data['weekly_chart'] = $this->OrderModel->get_chart_data('weekly');
        $data['monthly_chart'] = $this->OrderModel->get_chart_data('monthly');

        $this->load->view('sales/index', $data);
    }


    // Export sales to Excel
    public function export_excel($period = 'daily') {
        $this->load->library('PhpSpreadsheet'); // make sure PhpSpreadsheet is installed
        $orders = $this->OrderModel->get_done_orders($period);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(ucfirst($period) . ' Sales');

        // Headers
        $sheet->setCellValue('A1', 'Order ID');
        $sheet->setCellValue('B1', 'Staff ID');
        $sheet->setCellValue('C1', 'Customer Name');
        $sheet->setCellValue('D1', 'Order Items');
        $sheet->setCellValue('E1', 'Total Amount');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Order Date');

        $row = 2;
        foreach ($orders as $order) {
            $sheet->setCellValue('A'.$row, $order->order_id);
            $sheet->setCellValue('B'.$row, $order->staff_id);
            $sheet->setCellValue('C'.$row, $order->customer_name);
            $sheet->setCellValue('D'.$row, $order->order_items);
            $sheet->setCellValue('E'.$row, $order->total_amount);
            $sheet->setCellValue('F'.$row, $order->status);
            $sheet->setCellValue('G'.$row, $order->order_date);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = $period . '_sales.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer->save("php://output");
        exit;
    }
}
