<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/mysql_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/fpdf.php";
start_session();

$spacing = 30;

$invoice_guid = $_GET["guid"];
$invoice = get_invoice_data($invoice_guid);
if (!$invoice) goto_page("/");
$product = get_product_data($invoice["product_guid"]);
$user = get_user_data($invoice["user_guid"]);
$user_address = get_user_address($invoice["user_guid"]);

$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'bu', 12);
$pdf->Cell($spacing,10, "Invoice");
$pdf->Ln(6);
$pdf->SetFont('Arial', 'b', 12);
$pdf->Cell($spacing,10, "Invoice ID:");
$pdf->Cell($spacing,10, $invoice["guid"]);
$pdf->Ln(6);
$pdf->Cell($spacing,10, "Issue date:");
$pdf->Cell($spacing,10, $invoice["purchase_date"]);

$pdf->Ln(18);

$pdf->SetFont('Arial', 'bu', 12);
$pdf->Cell($spacing,10, "Bill to");
$pdf->Ln(6);
$pdf->SetFont('Arial', 'b', 12);
$pdf->Cell($spacing,10, "Name:");
$pdf->Cell($spacing,10, $user["first_name"]." ".$user["last_name"]);
$pdf->Ln(6);
$pdf->Cell($spacing,10, "Street:");
$pdf->Cell($spacing,10, !empty($user_address["street"]) ? $user_address["street"] : "-");
$pdf->Ln(6);
$pdf->Cell($spacing,10, "City:");
$pdf->Cell($spacing,10, (!empty($user_address["city"]) ? $user_address["city"] : "-").", ".(!empty($user_address["postal_code"]) ? $user_address["postal_code"] : "-"));
$pdf->Ln(6);
$pdf->Cell($spacing,10, "Country:");
$pdf->Cell($spacing,10, !empty($user_address["country"]) ? $user_address["country"] : "-");

$pdf->Ln(18);

$pdf->SetFont('Arial', 'bu', 12);
$pdf->Cell($spacing,10, "Purchase details");
$pdf->Ln(6);
$pdf->SetFont('Arial', 'b', 12);
$pdf->Cell($spacing,10, "Product:");
$pdf->Cell($spacing,10, $product["name"]);
$pdf->Ln(6);
$pdf->Cell($spacing,10, "Price:");
$pdf->Cell($spacing,10, "$ ".$product["price"]);
$pdf->Ln(6);
$pdf->Cell($spacing,10, "Quantity:");
$pdf->Cell($spacing,10, "1");

$pdf->Ln(18);
$pdf->SetFont('Arial', 'b', 12);
$pdf->Cell($spacing,10, "Customer note:");
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($spacing,10, !empty($invoice["note"]) ? $invoice["note"] : "-");

$pdf->Ln(24);

$pdf->SetFont('Arial', 'bi', 12);
$pdf->Cell($spacing,10, "Thank you for your purchase!");
$pdf->Ln(6);
$pdf->Cell($spacing,10, "We are grateful for your trust in our product. It means the world to us!");
$pdf->Ln(6);
$pdf->Cell($spacing,10, "We hope you enjoy it, and if you have any questions or need assistance, we're here to help!");
$pdf->Ln(12);
$pdf->Cell($spacing,10, "Your Routerploit team");
$pdf->Output();
?>