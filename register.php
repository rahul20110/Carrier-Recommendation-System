<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){
   // $success = "hello";
   // echo '<script>swal("'.$success.'", "" ,"success");</script>';
   // $success_msg[] = 'register here!';

   $id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING); 
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING); 
   $c_pass = sha1($_POST['c_pass']);
   $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);   




   $select_users = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_users->execute([$email]);

   if($select_users->rowCount() > 0){
      $warning_msg[] = 'email already taken!';
   }else{
      if($pass != $c_pass){
         $warning_msg[] = 'Password not matched!';
      }
      else
      {

            // _________________
            // DOING KYC HERE...
            // _________________

      
      
         $email = $_POST["email"];
         $password = $_POST["pass"];

         // Define the API endpoint URL
         $apiUrl = "https://192.168.3.39:5000/kyc";

         // Define the JSON data to be sent in the request body
         $data = json_encode(array(
            "email" => $email,
            "password" => $password
         ));

         // Set up cURL
         $ch = curl_init($apiUrl);

         // Disable SSL certificate verification
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

         // Set cURL options
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
         ));

         // Execute the cURL request
         $response = curl_exec($ch);

         // Check for errors
         if ($response === false) {
            die('cURL error: ' . curl_error($ch));
         }

         // Close the cURL session
         curl_close($ch);

         // Decode the JSON response
         $responseData = json_decode($response, true);

         // Check the response

        
         if ($responseData["status"] === "success") {

            // OLD CODE FROM HERE TILL INSERTED NEW USER 

            $insert_user = $conn->prepare("INSERT INTO `users`(id, name, number, email, password) VALUES(?,?,?,?,?)");
            $insert_user->execute([$id, $name, $number, $email, $c_pass]);
            
            if($insert_user){
               $verify_users = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
               $verify_users->execute([$email, $pass]);
               $row = $verify_users->fetch(PDO::FETCH_ASSOC);
            
               if($verify_users->rowCount() > 0){
                  setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
                  
               }else{
                  $error_msg[] = 'something went wrong!';
               }
            }
            // INSERTED NEW USER

            // echo "Login successful: " . $responseData["message"];
            // ENTER LOGIN SUCESS MSG
            // After successful registration
            // $_SESSION['success_msg'] = 'Registration successful';

            $success_msg[] = "USER REGISTERED SUCCESSFULLY";
            header('location:home.php');

         } else {
            // echo "Error: " . $responseData["message"];
            // ENTER INVALID KYC MESSAGE HERE
            $error_msg[] = $responseData["message"];
         }
      


   // -----------------------------
   //  K Y C    E N D S    H E R E 
   // -----------------------------



         

      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- register section starts  -->

<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>create an account!</h3>
      <input type="tel" name="name" required maxlength="50" placeholder="enter your name" class="box">
      <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box">
      <input type="number" name="number" required min="0" max="9999999999" maxlength="10" placeholder="enter your number" class="box">
      <input type="password" name="pass" required maxlength="20" placeholder="enter your password" class="box">
      <input type="password" name="c_pass" required maxlength="20" placeholder="confirm your password" class="box">
      <div class="box">
         <p>Upload ID Proof (pdf)<span>*</span></p>
         <input type="file" name="id_proof"  accept=".pdf" required>
      </div>

      <p>already have an account? <a href="login.php">login now</a></p>
      <input type="submit" value="register now" name="submit" class="btn">
   </form>

</section>

<!-- register section ends -->










<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>