<?php
include 'components/connect.php';

if (!isset($_COOKIE['user_id'])) {
    header('location:login.php');
    exit;
}

$user_id = $_COOKIE['user_id'];

// Fetch property details from the database
if (isset($_POST['property_id'])) {
    $get_id = $_POST['property_id'];
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

// Update database using PDO
if (isset($_POST['property_id'])) {
    $pro_id = $_POST['property_id'];

    if ($_POST['payment_type'] == 'full') {
        $sql = "UPDATE employee_sign SET remaining = 0 WHERE property_id = :property_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':property_id', $pro_id);
        $stmt->execute();
        $sql = "UPDATE users SET wallet = wallet + :amount WHERE id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':amount', $_POST['amount']);
        $stmt->bindParam(':user_id', $seller_id); // Corrected from $seller to $seller_id
        $stmt->execute();
    } else {
        $amount = intval($_POST['amount']);
        $propertyId = $pro_id;

        $sql = "UPDATE employee_sign SET remaining = remaining - :amount WHERE property_id = :property_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':property_id', $propertyId);
        $stmt->execute();
        $sql = "UPDATE users SET wallet = wallet + :amount WHERE id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':amount', $_POST['amount']);
        $stmt->bindParam(':user_id', $seller_id); // Corrected from $seller to $seller_id
        $stmt->execute();
    }
}

// Close the database connection

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

        echo '<p class="empty">Payment done Successfully.</p>';
    //     echo $amount;
    //    echo $_POST['property_id'];
        if($_POST['payment_type']!='full'){
            echo '<p class="empty">Please pay remaining amount as soon as possible.</p>';
        }
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