<?php
require 'c:\\xampp\\htdocs\\vendor\\autoload.php';
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
    $query = "SELECT * FROM users WHERE id = :get_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':get_id', $user_id);
    $stmt->execute();

    if ($users = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Retrieve property details
        $buyer=$users['name'];
        $email_id=$users['email'];
    }

} else {
    $user_id = '';
    header('location:login.php');
}

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];

    // Fetch property details from the database
    $query = "SELECT * FROM property WHERE id = :get_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':get_id', $get_id);
    $stmt->execute();

    if ($property = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Retrieve property details
        $propertyName = $property['property_name'];
        $seller_id=$property['user_id'];
        $address = $property['address'];
        $price = $property['price'];
        $propertyType = $property['type'];
        $offer = $property['offer'];
        $status = $property['status'];
        $furnished = $property['furnished'];
        $bhk = $property['bhk'];
        $image= $property['image_01'];
        $query = "SELECT * FROM users WHERE id = :get_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':get_id', $seller_id);
        $stmt->execute();

        if ($users = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Retrieve property details
            $seller=$users['name'];
            $email_id_seller=$users['email'];
        }


    }
    else{
        $get_id = '';
    header('location:home.php');
    }
} else {
    $get_id = '';
    header('location:home.php');
}

// Create a new PDF document
$pdf = new TCPDF();
$pdf->SetAutoPageBreak(true, 10);
// Create a new PDF document with a margin border
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetAutoPageBreak(true, 20);  // Add a larger bottom margin
$pdf->SetMargins(20, 20, 20);
$pdf->AddPage();

// Add your company logo at the top right corner

$logoPath = 'logo.jpg'; // Replace with your logo path
$pdf->Image($logoPath, 180, 10, 20);
// Add a page
// $pdf->AddPage();

// Set font
$pdf->SetFont('times', '', 10);

// Metadata
$pdf->SetTitle('Purchase Agreement');
$pdf->SetAuthor('Your Company Name');
$pdf->SetSubject('Property Purchase Agreement');

// Set Margins
$pdf->SetMargins(20, 20, 20);

$htmlContent = "
    <h1>Purchase Agreement</h1>
    <h2>This Purchase Agreement is a legally binding contract between the parties named below:</h2>
    <p><strong>Buyer:</strong> {$buyer}</p>
    <p><strong>Seller:</strong> {$seller}</p>
    <h2>Property Details:</h2>
    <p><strong>Property:</strong> {$propertyName}</p>
    <p><strong>Address:</strong> {$address}</p>
    <p><strong>Price:</strong> {$price}</p>
    <p><strong>Type:</strong> {$propertyType}</p>
    <p><strong>Offer:</strong> {$offer}</p>
    <p><strong>Status:</strong> {$status}</p>
    <p><strong>Furnished:</strong> {$furnished}</p>
    <p><strong>BHK:</strong> {$bhk}</p>
    <h2>Below is the photo:</h2>
";

$pdf->writeHTMLCell(0, 0, '', '', $htmlContent, 0, 1, 0, true, '', true);
$imagePath = "uploaded_files\\$image"; // Replace with your property image path
$pdf->Image($imagePath, 20, 160, 120);


$pdf->AddPage();
$htmlContent = "
    <h2>Terms and conditions:</h2>
    <p>This Agreement is governed by the laws of [State], and both parties agree to the terms and conditions stated herein.</p>
    <p>Buyer and Seller acknowledge that they have read and understood the terms of this Agreement and agree to be bound by them.</p>
";


$pdf->writeHTMLCell(0, 0, '', '', $htmlContent, 0, 1, 0, true, '', true);

// Signature lines
$pdf->Ln(10);
$pdf->MultiCell(0, 5, "Buyer's Signature: ______________________________ Date: ____________", 0, 'L', 0, 1, '', '', true);

$pdf->SetTextColor(0, 0, 255); // Set the text color to blue
$pdf->SetLink('sign.php?get_id=' . $_GET['get_id'], 0, 'sign.php');
$pdf->Cell(0, 10, "If you want to sign the contract, click here", 0, 1, 'L', 0, 'sign.php?get_id=' . $_GET['get_id']);
$pdf->SetTextColor(0, 0, 0); // Reset text color

// Output the PDF to the local directory
$pdfDirectory = 'c:\\xampp\\htdocs\\real\\';

if (!file_exists($pdfDirectory)) {
    mkdir($pdfDirectory, 0777, true);
}

$pdfFilePath = $pdfDirectory . 'purchase_agreement_.pdf';
$pdf->Output($pdfFilePath, 'I');
echo 'PDF Saved';
?>
