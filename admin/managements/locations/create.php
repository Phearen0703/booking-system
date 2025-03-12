<?php
$title = "Add Location";
$page = "add_location";
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");

$success_message = $error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $province_name = trim($_POST['province']);
    $district_name = trim($_POST['district']);
    $city_name = trim($_POST['city']);
    
    // ✅ Insert Province if it doesn't exist
    $province_query = "INSERT INTO provinces (name) SELECT ? FROM dual 
                       WHERE NOT EXISTS (SELECT 1 FROM provinces WHERE name = ?)";
    $stmt = mysqli_prepare($conn, $province_query);
    mysqli_stmt_bind_param($stmt, "ss", $province_name, $province_name);
    mysqli_stmt_execute($stmt);
    $province_id = mysqli_insert_id($conn) ?: mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM provinces WHERE name='$province_name'"))['id'];

    // ✅ Insert District if it doesn't exist
    $district_query = "INSERT INTO districts (name, province_id) SELECT ?, ? FROM dual 
                       WHERE NOT EXISTS (SELECT 1 FROM districts WHERE name = ? AND province_id = ?)";
    $stmt = mysqli_prepare($conn, $district_query);
    mysqli_stmt_bind_param($stmt, "sisi", $district_name, $province_id, $district_name, $province_id);
    mysqli_stmt_execute($stmt);
    $district_id = mysqli_insert_id($conn) ?: mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM districts WHERE name='$district_name' AND province_id=$province_id"))['id'];

    // ✅ Insert City if it doesn't exist
    $city_query = "INSERT INTO cities (name, district_id) SELECT ?, ? FROM dual 
                   WHERE NOT EXISTS (SELECT 1 FROM cities WHERE name = ? AND district_id = ?)";
    $stmt = mysqli_prepare($conn, $city_query);
    mysqli_stmt_bind_param($stmt, "sisi", $city_name, $district_id, $city_name, $district_id);
    mysqli_stmt_execute($stmt);
    $city_id = mysqli_insert_id($conn) ?: mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM cities WHERE name='$city_name' AND district_id=$district_id"))['id'];

    if ($province_id && $district_id && $city_id) {
        $success_message = "Location added successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}
?>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="<?php echo $burl . '/admin/managements/locations/index.php'; ?>" class="btn btn-outline-danger mb-3">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="fw-bold mb-0">Add New Location</h3>
                </div>
                <div class="card-body">
                    <?php if ($success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $success_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php elseif ($error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="create.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="province" class="form-label fw-semibold">Province</label>
                            <input type="text" id="province" name="province" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="district" class="form-label fw-semibold">District</label>
                            <input type="text" id="district" name="district" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label fw-semibold">City</label>
                            <input type="text" id="city" name="city" class="form-control" required>
                        </div>


                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Add Location</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<?php
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php");
?>
