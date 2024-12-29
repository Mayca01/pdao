<?php
ob_start(); // Start output buffering

include realpath(__DIR__ . '/../app/layout/admin-header.php');
require('../fpdf.php');

// Create a new PDF document
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'PWD Information', 0, 1, 'C');

// Add header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, 'Name', 1);
$pdf->Cell(30, 10, 'Age', 1);
$pdf->Cell(50, 10, 'Disability', 1);
$pdf->Ln();

// Add data
$pdf->SetFont('Arial', '', 12);
$fetchUsers = $userFacade->fetchUsers();
foreach ($fetchUsers as $users) {
    $pdf->Cell(100, 10, $users['first_name'] . ' ' . $users['last_name'], 1);
    $pdf->Cell(30, 10, $users['age'], 1);
    $pdf->Cell(50, 10, $users['disability'], 1);
    $pdf->Ln();
}

// Clean the output buffer
ob_end_clean(); 

// Output PDF to browser
$pdf->Output('I', 'pwd_information.pdf');
?>
