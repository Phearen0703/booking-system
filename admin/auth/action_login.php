<?php 

    include($_SERVER['DOCUMENT_ROOT']."/booking-system/config.php");

    if(isset($_POST['username']) && isset($_POST['password'])){

        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = $conn ->query("SELECT id, username, password FROM users WHERE username = '$username' AND password = '$password'");

        $user = $user -> fetch_object();

        if($user){
        
            $_SESSION['message']=[
                'status' => 'success',
                'sms' => 'Login Seccussfully',
            ];

            $_SESSION['login'] = true;
            $_SESSION['auth'] = $user->id;
            header('Location:' . $burl . '/admin/index.php');
            exit();
        }
       
    }
    $_SESSION['message']=[
        'status' => 'error',
        'sms' => 'Wrong username or password',
    ];
    header('Location:' . $burl . '/admin/auth/login.php');
?>