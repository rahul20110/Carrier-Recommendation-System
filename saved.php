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
   <title>requests</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="listings_01">

   <h1 class="heading">All Properties contracts need to be signed are below </h1>

   <!-- <form action="" method="POST" class="search-form">
      <input type="text" name="search_box" placeholder="search listings..." maxlength="100" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form> -->

   <div class="box-container">

   <?php
      $select=$conn->prepare("SELECT * FROM `employee_sign` WHERE seller_id = '$user_id' AND seller_sign IS NULL");
      $select->execute();
      if($select->rowCount()> 0){
         
         while($row=$select->fetch(PDO::FETCH_ASSOC)){
            $pro=$row['property_id'];
            // echo $pro;
            $select_listings = $conn->prepare("SELECT * FROM `property` WHERE id = '$pro'");
            $select_listings->execute();
            
            $total_images = 0;
            if($select_listings->rowCount() > 0){
               while($fetch_listing = $select_listings->fetch(PDO::FETCH_ASSOC)){

               $listing_id = $fetch_listing['id'];

               $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_user->execute([$fetch_listing['user_id']]);
               $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

               if(!empty($fetch_listing['image_02'])){
                  $image_coutn_02 = 1;
               }else{
                  $image_coutn_02 = 0;
               }
               if(!empty($fetch_listing['image_03'])){
                  $image_coutn_03 = 1;
               }else{
                  $image_coutn_03 = 0;
               }
               if(!empty($fetch_listing['image_04'])){
                  $image_coutn_04 = 1;
               }else{
                  $image_coutn_04 = 0;
               }
               if(!empty($fetch_listing['image_05'])){
                  $image_coutn_05 = 1;
               }else{
                  $image_coutn_05 = 0;
               }

               $total_images = (1 + $image_coutn_02 + $image_coutn_03 + $image_coutn_04 + $image_coutn_05);
         ?>
         <div class="box">
            <div class="thumb">
               <p><i class="far fa-image"></i><span><?= $total_images; ?></span></p>
               <img src="uploaded_files/<?= $fetch_listing['image_01']; ?>" alt="">
            </div>
            <p class="price"><i class="fas fa-indian-rupee-sign"></i><?= $fetch_listing['price']; ?></p>
            <h3 class="name"><?= $fetch_listing['property_name']; ?></h3>
            <p class="location"><i class="fas fa-map-marker-alt"></i><?= $fetch_listing['address']; ?></p>
            <form action="contract3.php" method="POST">
            
             

               <a href="view_property2.php?get_id=<?= $listing_id; ?>" class="btn">view property</a>
               <input type="hidden" name="pro_id" value="<?php echo $pro; ?>">
               <input type="submit" value="Sign Contract"  name="sign" class="btn">
               <!-- <a href="contract2.php?get_id=<?= $pro; ?>" class="btn">Save Contract</a> -->
            </form>
         </div>
         <?php
               }
            }
               }
      }   
      elseif(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
         echo '<p class="empty">no results found!</p>';
      }else{
         echo '<p class="empty">no properties sold !</p>';
      }
   ?>

   </div>

</section>






















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>