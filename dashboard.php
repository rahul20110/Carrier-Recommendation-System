<?php  

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

      <div class="box">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
         $select_profile->execute([$user_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <h3>welcome!</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update.php" class="btn">update profile</a>
      </div>

      <div class="box">
         <h3>filter search</h3>
         <p>search your dream property</p>
         <a href="search.php" class="btn">search now</a>
      </div>

      <div class="box">
      <?php
        $count_properties = $conn->prepare("SELECT * FROM `property` WHERE user_id = ? AND id NOT IN (SELECT property_id FROM employee_sign)");
        $count_properties->execute([$user_id]);
        $total_properties = $count_properties->rowCount();
      ?>
      <h3><?= $total_properties; ?></h3>
      <p>properties listed</p>
      <a href="my_listings.php" class="btn">view all listings</a>
      </div>

      <div class="box">
      <?php
        $select=$conn->prepare("SELECT * FROM `employee_sign` WHERE user_id = '$user_id'");
        $select->execute();
        $total_requests_received = $select->rowCount();
      ?>
      <h3><?= $total_requests_received; ?></h3>
      <p>Total Bought properties</p>
      <a href="requests.php" class="btn">Bought properties</a>
      </div>

      <div class="box">
      <?php
        $select=$conn->prepare("SELECT * FROM `employee_sign` WHERE seller_id = '$user_id' AND seller_sign IS NULL");
        $select->execute();
        $total_requests_sent = $select->rowCount();
      ?>
      <h3><?= $total_requests_sent; ?></h3>
      <p>Pending Contracts to be signed</p>
      <a href="saved.php" class="btn">Sign Contracts</a>
      </div>
      <div class="box">
      <?php
        $select=$conn->prepare("SELECT * FROM `employee_sign` WHERE seller_id = '$user_id' AND seller_sign IS NOT NULL");
        $select->execute();
        $total_requests_sent = $select->rowCount();
      ?>
      <h3><?= $total_requests_sent; ?></h3>
      <p>Total sold properties</p>
      <a href="saved2.php" class="btn">sold properties</a>
      </div>


      
   </div>

</section>






















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>