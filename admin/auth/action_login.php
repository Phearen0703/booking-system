<?php 

    include($_SERVER['DOCUMENT_ROOT']."/booking-system/config.php");

    if(isset($_POST['user_name']) && isset($_POST['password'])){

        $username = $_POST['user_name'];
        $password = $_POST['password'];

        $user = $conn ->query("SELECT id, user_name, password FROM users WHERE user_name = '$username' AND password = '$password'");

        $user = $user -> fetch_object();

        if($user){
        
            $message = "Login Seccussfully";

            $_SESSION['login'] = true;
            $_SESSION['auth'] = $user->id;
            header('Location:' . $burl . '/admin/index.php');
            exit();
        }
       
    }
        $message = "Login Failed";
    header('Location:' . $burl . '/admin/auth/login.php');
?>