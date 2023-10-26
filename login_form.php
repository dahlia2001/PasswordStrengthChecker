<?php

@include 'config.php';

session_start();

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);
   $user_type = $_POST['user_type'];
   
   $captcha = $_POST['captcha'];
   $generated_captcha = $_SESSION['captcha'];

   if($captcha == $generated_captcha){
   
      $select = " SELECT * FROM user_form WHERE email = '$email' && password = '$pass' ";

      $result = mysqli_query($conn, $select);

      if(mysqli_num_rows($result) > 0){

         $row = mysqli_fetch_array($result);

         if($row['user_type'] == 'admin'){

            $_SESSION['admin_name'] = $row['name'];
            header('location:admin_page.php');

         }elseif($row['user_type'] == 'user'){

            $_SESSION['user_name'] = $row['name'];
            header('location:pass_strength.html');

         }

      }else{
         $error[] = 'incorrect email or password!';
      }

   }else{
      $error[] = 'CAPTCHA verification failed. Please try again.';
   }

};

// Generate a random CAPTCHA string
$captcha_string = substr(md5(mt_rand()), 0, 7);

// Store the generated CAPTCHA string in the session
$_SESSION['captcha'] = $captcha_string;

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login form</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <!-- jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery On-Screen Keyboard plugin -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/virtual-keyboard/dist/css/jquery.keyboard.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/virtual-keyboard/dist/js/jquery.keyboard.min.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<!-- Add refresh function to reload captcha every minute -->
<script>
  function refreshCaptcha() {
    location.reload();
  }
  setInterval(refreshCaptcha, 60000);
</script>

</head>
<body>
   
<div class="form-container">

   <form action="" method="post">
      <h3>login now</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="email" name="email" required placeholder="enter your email" class="use-keyboard-input">
   
      <input type="password" name="password" required placeholder="enter your password" class="use-keyboard-input">
      
      <!-- Display the generated CAPTCHA string in a label -->
      <label for="captcha">Enter the following CAPTCHA code:</label>
      <br>  <label id="captcha-label" style="background-color: #f0f0f0; padding: 0 10px;"><?php echo isset($_SESSION['captcha']) ? $_SESSION['captcha'] : ''; ?></label>
<input type="text" name="captcha" required placeholder="enter captcha" class="use-keyboard-input">


      <input type="submit" name="submit" value="login now" class="form-btn">
      <p>don't have an account? <a href="register_form.php">register now</a></p>
      <script src="keyboard.js"></script>
   </form>
   <script>
   <script>
   setInterval(function() {
      $.ajax({
         url: 'captcha.php',
         type: 'GET',
         success: function(data) {
            $('#captcha-label').text(data);
         }
      });
   }, 60000); 
</script>


</script>
</div>
</body>
</html>
