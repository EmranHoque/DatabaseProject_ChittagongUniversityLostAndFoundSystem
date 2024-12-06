<?php
require 'vendor/autoload.php'; // Include Composer autoload (or manually include Dompdf files)

use Dompdf\Dompdf;
use Dompdf\Options;

// Enable Dompdf options
$options = new Options();
$options->set('isRemoteEnabled', true); // Allow loading external assets (like charts, images, etc.)

$dompdf = new Dompdf($options);

// Start output buffering to capture the HTML
ob_start();
include 'analytics_page.php'; // Path to the analytics page
$html = ob_get_clean();

// Load the captured HTML
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML to PDF
$dompdf->render();

// Output the PDF to the browser
$dompdf->stream("analytics.pdf", ["Attachment" => false]); // Change to true for forced download
