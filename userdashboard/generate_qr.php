<?php
require_once '../phpqrcode/qrlib.php';

// Set the path for QR codes - using absolute path for reliability
$path = __DIR__ . 'qrcodes/';

// Create directory if it doesn't exist
if (!file_exists($path)) {
    if (!mkdir($path, 0777, true)) {
        die('Failed to create QR code directory');
    }
}

// Get the data from URL
$data = $_GET['data'] ?? 'No data provided';

// Generate filename
$filename = 'cert_' . md5($data) . '.png';
$file = $path . $filename;

// Generate QR code if it doesn't exist
if (!file_exists($file)) {
    QRcode::png($data, $file, QR_ECLEVEL_H, 5);
}

// Verify the file exists
if (!file_exists($file)) {
    // Create error image
    $im = imagecreatetruecolor(200, 50);
    $bg = imagecolorallocate($im, 255, 255, 255);
    $textcolor = imagecolorallocate($im, 255, 0, 0);
    imagefilledrectangle($im, 0, 0, 200, 50, $bg);
    imagestring($im, 5, 10, 10, 'QR Generation Failed', $textcolor);
    header('Content-Type: image/png');
    imagepng($im);
    imagedestroy($im);
    exit();
}

// Output the QR code
header('Content-Type: image/png');
readfile($file);
exit();
