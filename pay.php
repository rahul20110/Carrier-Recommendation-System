<?php  

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');

}
if (isset($_POST['buy_press'])) {
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
</html>


<section class="property-form">
    


<form action="payment_gateway.php" method="POST" enctype="multipart/form-data">
    
    
    <div class="box">
        <p>Payment Type <span>*</span></p>
        <select name="payment_type" required class="input">
            <option value="partial">Partial</option>
            <option value="full">Full</option>
        </select>
    </div>
    <div class="box">
        <p>Amount <span>*</span></p>
        <input type="number" name="total" required min="0" max="9999999999" maxlength="10" placeholder="enter total price" class="input">
    </div>
    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
    <input type="hidden" name="get_id" value="<?php echo $get_id; ?>">
    <input type="submit" value="Pay Amount" class="btn" name="pay">
    <script>
document.addEventListener("DOMContentLoaded", function () {
    const paymentTypeSelect = document.querySelector('select[name="payment_type"]');
    const totalInput = document.querySelector('input[name="total"]');
    const propertyPrice = <?php echo json_encode($price); ?>;

    paymentTypeSelect.addEventListener("change", function () {
        if (paymentTypeSelect.value === "full") {
            totalInput.value = propertyPrice;
            totalInput.setAttribute("readonly", true);
        } else {
            totalInput.value = "";
            totalInput.removeAttribute("readonly");
        }
    });
});
</script>

</form>





</section>



<!DOCTYPE html>
<html lang="en">
<head>
    
</head>
<body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

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
</body>
</html>





