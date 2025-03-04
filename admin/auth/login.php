<?php
    $title = "Login";
?>

<?php include($_SERVER['DOCUMENT_ROOT']."/booking-system/admin/layouts/header.php");

    if($_SESSION['login']==true){
        header('Location:' .$burl .'/admin/index.php');
    }
?>

<form action="<?php echo $burl . "/admin/auth/action_login.php" ?>" method="post">
    <div class="container-fluid bg-info-subtle bg-gradient-subtle">
        <div class="row min-vh-100 d-flex justify-content-center align-items-center">
            <div class="col-3">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-0 text-center text-danger">Login</h2>
                        </div>
                        <div class="card-body">
                        <?php include($_SERVER['DOCUMENT_ROOT']."/booking-system/admin/layouts/sms.php");?>
                            <div class="mb-3">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <button class="btn btn-primary float-end"><i class="fa-solid fa-right-to-bracket"></i>
                                Login</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<?php   include($_SERVER['DOCUMENT_ROOT']."/booking-system/admin/layouts/footer.php");?>