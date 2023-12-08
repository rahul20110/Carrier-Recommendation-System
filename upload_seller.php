<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');

}
if (isset($_POST['get_id'])) {
    $get_id = $_POST['get_id'];

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
include 'components/save_send.php';
// Establish a database connection
$con = mysqli_connect("localhost", "root", "", "mysql");

if (!$con) {
    die("Connection error: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Sanitize and validate user input
    $pro_id=$_POST['get_id'];

    if (isset($_POST['get_id'])) {
        // echo $_POST['get_id'];
        $signature = $_POST['signature'];
        // Generate a unique filename for the uploaded image
        $image_parts = explode(";base64,", $signature);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $file = "upload/" . $user_id . "_" . uniqid() . '.jpg'; // Change file extension to '.jpg'

        // Decode and save the image
        $image_base64 = base64_decode($image_parts[1]);
        file_put_contents($file, $image_base64);

        // Insert the data into the database
        // Get property ID
        $pro_id = $_POST['get_id']; 

        // Prepare SQL  
        $sql = "UPDATE employee_sign set seller_sign=? where property_id=?";

        // Prepare statement
        $stmt = $con->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ss", $file,$pro_id); 

        // Execute  
        $stmt->execute();

// Check result
        if($stmt->affected_rows > 0){
            echo "Signature uploaded successfully."; 
        } else {
            echo "Error uploading signature: " . $con->error;
        }

        // Close statement
        $stmt->close();

    } else {
        echo "No signature data received.";
    }
}

// Close the database connection
mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body></body>
   
<?php include 'components/user_header.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<section>

    <div class="box">
    <?php

        echo '<p class="empty">Congrats for selling property! your e-sign has been submitted successfully .</p>';
        echo '<p class="empty">Thank you for using our platform.</p>';

        ?>
    </div>
</section>


<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

<script>

   let range = document.querySelector("#range");
   range.oninput = () =>{
      document.querySelector('#output').innerHTML = range.value;
   }

</script>
<body>
    
</body>
</html>