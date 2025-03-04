<?php
     include($_SERVER['DOCUMENT_ROOT']."/booking-system/config.php");


     $_SESSION['message']=[
          'status' => 'warning',
          'sms' => 'Logout Seccussfully',
      ];

     $_SESSION['login'] = false;
     $_SESSION['auth'] = 0;

     header('Location:' . $burl .'/admin/auth/login.php');
?>