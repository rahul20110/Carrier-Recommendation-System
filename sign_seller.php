<?php  

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');

}
if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
    
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


<section class="form-container">
    
<head>
    
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->


    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script type="text/javascript" src="asset/js/jquery.signature.min.js"></script>
    <link rel="stylesheet" type="text/css" href="asset/css/jquery.signature.css">

    <style>
    .kbw-signature {
        border: var(--border);
        /* padding: 1rem; */
        color: var(--black);
        margin: 1rem 0;
        width: 100%;
        /* width: 100%; Set the signature width to 100% of its container */
        max-width: 500px; /* Set a maximum width if needed */
        height: 200px;
    }

    #sig canvas {
        /* border: var(--border); */
        /* padding: 1.4rem; */
        /* color: var(--black); */
        /* margin: 1rem 0;
        width: 100%; */
        width: auto!important;
        height: auto;
    }
    </style>


</head>

<body class="bg-light">

    <div class="container p-4">

        <div class="row">
            <div class="col-md-5 border p-3  bg-white">
            <form method="POST" action="upload_seller.php">
                <h3>Signature Pad</h3>
                <div class="col-md-12">
                    <input type="tel" name="name" required maxlength="50" placeholder="enter your name" class="box" aria-required="true">
                </div>
                <div class="col-md-12">
                    <p>Sign below: <span></span></p>
                    <br />
                    <div id="sig"></div>
                    <br />

                    <textarea id="signature64" name="signature" style="display: none"></textarea>
                    <div class="col-12">
                        <button class="btn btn-sm btn-warning" id="clear">&#x232B;Clear Signature</button>
                    </div>
                </div>

                <input type="hidden" name="get_id" value="<?php echo $get_id; ?>">
                <input type="submit" value="Submit" name="submit" class="btn">
            </form>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        var sig = $('#sig').signature({
            syncField: '#signature64',
            syncFormat: 'JPEG'
        });
        $('#clear').click(function(e) {
            e.preventDefault();
            sig.signature('clear');
            $("#signature64").val('');
        });
    </script>


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





