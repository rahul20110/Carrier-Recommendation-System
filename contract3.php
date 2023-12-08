<?php
require 'c:\\xampp\\htdocs\\vendor\\autoload.php';
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
    echo 'user id '. $user_id .'';
    $query = "SELECT * FROM users WHERE id = :get_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':get_id', $user_id);
    $stmt->execute();

    if ($users = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Retrieve property details
        $seller=$users['name'];
        $email_id_seller=$users['email'];
    }

} else {
    $user_id = '';
    header('location:login.php');
}

if (isset($_POST['sign'])) {
    $get_id = $_POST['pro_id'];
   

    // Fetch property details from the database
    $query = "SELECT * FROM employee_sign WHERE property_id = :get_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':get_id', $get_id);
    $stmt->execute();
    if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $user_id=$user['user_id'];
        $seller_id= $user['seller_id'];
        $query = "SELECT * FROM property WHERE id = :get_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':get_id', $get_id);
        $stmt->execute();
        if ($property = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Retrieve property details
            $propertyName = $property['property_name'];
            // $seller_id=$property['user_id'];
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
            $stmt->bindParam(':get_id', $user_id);
            $stmt->execute();

            if ($users = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Retrieve property details
                $buyer=$users['name'];
                $email_id=$users['email'];
            }
            


        }
    }
    else{
        $get_id = '';
    header('location:home.php');
    }
} else {
    // $get_id = '';
    // header('location:home.php');
}

// Create a new PDF document
$pdf = new TCPDF();
$pdf->SetAutoPageBreak(true, 10);
// Create a new PDF document with a margin border
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetAutoPageBreak(true, 10);  // Add a larger bottom margin
$pdf->SetMargins(20, 10, 10);
$pdf->AddPage();

// Add your company logo at the top right corner

$logoPath = 'logo.jpg'; // Replace with your logo path
$pdf->Image($logoPath, 181, 9, 20);
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
// Assuming the original dimensions of the image are $originalWidth and $originalHeight
$imagePath = "uploaded_files\\$image"; // Replace with your property image path
list($originalWidth, $originalHeight) = getimagesize($imagePath);

// Calculate the new dimensions while maintaining the aspect ratio
$maxWidth = 100;
$maxHeight = 100;
if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
    $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
    $newWidth = $originalWidth * $ratio;
    $newHeight = $originalHeight * $ratio;
} else {
    $newWidth = $originalWidth;
    $newHeight = $originalHeight;
}

// Create a new PDF page
// $pdf->AddPage();

// // Set the image with the calculated dimensions
$pdf->Image($imagePath, 20, 155, $newWidth, $newHeight);



$pdf->AddPage();
$logoPath = 'logo.jpg'; // Replace with your logo path
$pdf->Image($logoPath, 181, 9, 20);
$htmlContent = "
    <h1>Terms and Conditions</h1>
    
    <h2>1. Platform Services:</h2>
    <p>The website provides a platform for property listings, communication, and transaction facilitation between buyers and sellers.</p>

    <h2>2. Listing Information:</h2>
    <p>Sellers are responsible for providing accurate, complete, and up-to-date information about their properties, including property details, pricing, and images.</p>

    <h2>3. Buyer's Obligations:</h2>
    <p>Buyers must conduct due diligence on properties of interest, verify property details, and comply with any rules and guidelines set by the website.</p>

    <h2>4. Fees and Commissions:</h2>
    <p>The website may charge fees or commissions for its services, the details of which are agreed upon by both buyers and sellers and are outlined in this Agreement.</p>

    <h2>5. Privacy and Data:</h2>
    <p>The website respects user privacy and handles data in compliance with applicable privacy laws and its privacy policy.</p>

    <h2>6. Dispute Resolution:</h2>
    <p>Disputes between buyers and sellers will be resolved through mediation or arbitration, as outlined in the dispute resolution section of this Agreement.</p>

    <h2>7. Cancellation and Refunds:</h2>
    <p>The terms for property purchase cancellations, refunds, or returns, if applicable, should be clearly stated, including any conditions and procedures involved.</p>

    <h2>8. Legal Compliance:</h2>
    <p>Both buyers and sellers must adhere to all relevant laws and regulations governing real estate transactions within their jurisdiction, and this Agreement does not exempt them from these responsibilities.</p>

    <h2>9. Liability:</h2>
    <p>The website is not responsible for property-related issues, and both buyers and sellers are accountable for fulfilling their respective obligations as per the Agreement.</p>

    <h2>10. Termination:</h2>
    <p>The website reserves the right to terminate user accounts or services for violations of the Agreement's terms and conditions, as specified in the termination section.</p>

    <h2>11. Governing Law:</h2>
    <p>This Agreement is governed by the laws of the specified jurisdiction, providing the legal framework for any disputes or issues arising from it.</p>

    <h2>12. Acceptance:</h2>
    <p>By using the website's services, both the buyer and seller acknowledge that they have read, understood, and agreed to be bound by the terms and conditions of this Agreement, committing to fulfill their respective obligations as outlined herein.</p>
";



$pdf->writeHTMLCell(0, 0, '', '', $htmlContent, 0, 1, 0, true, '', true);

// Signature lines
$pdf->Ln(10);
    $query = "SELECT * FROM employee_sign WHERE property_id = :get_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':get_id', $_POST['pro_id']);
    $stmt->execute();

    if ($property = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $user_sign=$property['user_sign'];
        $seller_sign=$property['seller_sign'];
    }
    
        
$logoPath = 'logo.jpg'; // Replace with your logo path
$pdf->Image($user_sign, 30, 55, 80);
$htmlContent = "
    <p><br><br><br><br></p>
";
$purchaseDate = date('Y-m-d');
$pdf->writeHTMLCell(0, 0, '', '', $htmlContent, 0, 1, 0, true, '', true);
$pdf->MultiCell(0, 5, "Buyer's Signature: ______________________________ Date: ". $purchaseDate, 0, 'L', 0, 1, '', '', true);
$htmlContent = "
    <p><br><br><br><br><br><br><br><br><br></p>
";
// $purchaseDate = date('Y-m-d');
$pdf->writeHTMLCell(0, 0, '', '', $htmlContent, 0, 1, 0, true, '', true);
if(isset($seller_sign)){
    $pdf->Image($seller_sign, 35, 100, 60);
$pdf->MultiCell(0, 5, "Seller's Signature: ______________________________ Date: ". $purchaseDate, 0, 'L', 0, 1, '', '', true);

}
else{
    $pdf->SetTextColor(0, 0, 255);
$pdf->SetLink('sign_seller.php?get_id=' . $get_id, 0, 'sign_seller.php');
$pdf->Cell(0, 10, "Please sign the contract, click here", 0, 1, 'L', 0, 'sign_seller.php?get_id=' . $get_id);
$pdf->SetTextColor(0, 0, 0); // Reset text color
}

// Output the PDF to the local directory
$pdfDirectory = 'c:\\xampp\\htdocs\\real\\';

if (!file_exists($pdfDirectory)) {
    mkdir($pdfDirectory, 0777, true);
}

$pdfFilePath = $pdfDirectory . 'purchase_agreement_.pdf';
ob_end_clean();
$pdf->Output($pdfFilePath, 'I');
echo 'PDF Saved';
?>
