<?php
require 'vendor/autoload.php'; // Load Composer autoload

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    // Create new spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Add some data
    $sheet->setCellValue('A1', 'Hello');
    $sheet->setCellValue('B1', 'World!');
    $sheet->setCellValue('A2', 'Date:');
    $sheet->setCellValue('B2', date('Y-m-d H:i:s'));

    // Save spreadsheet to file
    $writer = new Xlsx($spreadsheet);
    $writer->save('test_output.xlsx');

    echo "Spreadsheet created successfully! Check 'test_output.xlsx' in your project folder.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
