<?php
 (isset($_REQUEST['email']))  {
  
  //Email information
  $admin_email = "someone@example.com";
  $email = $_REQUEST['email'];
  $subject = "You Have A Pckage";
  $comment = "You have package(s) waiting for you. Packages are available for pick up during normal Desk Operational Hours. PLEASE NOTE! MavCard's are required to check out all packages!";
  
  //send email
  mail($admin_email, "$subject", $comment, "From:" . $email);
 
  }
?>