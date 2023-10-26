<?php
session_start();

// Generate a new CAPTCHA string and store it in the session
$captcha_string = substr(md5(mt_rand()), 0, 7);
$_SESSION['captcha'] = $captcha_string;

// Return the CAPTCHA string as the response
echo $captcha_string;
?>




