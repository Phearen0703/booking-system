<?php
$title = "Create Hotel";
$page = "create";

include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");


// Handle form submission
$success_message = $error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['hotel_name']) && !empty($_POST['description']) && !empty($_POST['location_id'])) {
        $hotel_name = trim($_POST['hotel_name']);
        $description = trim($_POST['description']);
        $location_id = $_POST['location_id'];
        $owner_id = $_SESSION['auth'];
        $created_at = date("Y-m-d H:i:s");

        $photo = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            // Validate image upload
            $photo_tmp = $_FILES['photo']['tmp_name'];
            $photo_name = $_FILES['photo']['name'];
            $photo_ext = pathinfo($photo_name, PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array(strtolower($photo_ext), $allowed_ext)) {
                $photo_new_name = time() . '_' . $photo_name;
                $photo_upload_path = $_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/public/img/hotel/" . $photo_new_name;
                
                // Move the uploaded file to the server directory
                if (move_uploaded_file($photo_tmp, $photo_upload_path)) {
                    $photo = $photo_new_name; // Store the file name for database
                } else {
                    $message = "Failed to upload photo.";
                }
            } else {
                $message = "Invalid file type for photo. Allowed types: jpg, jpeg, png, gif.";
            }
        } else {
            $message = "Please upload a photo.";
        }

        $stmt = mysqli_prepare($conn, "INSERT INTO hotels (name, description, location_id, owner_id, created_at, image) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssisss", $hotel_name, $description, $location_id, $owner_id, $created_at, $photo);

        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Hotel created successfully!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_message = "Please fill in all required fields.";
    }
}

// Fetch locations
$locations_query = "SELECT locations.id, cities.name AS city_name, districts.name AS district_name, provinces.name AS province_name
                    FROM locations
                    JOIN cities ON locations.city_id = cities.id
                    JOIN districts ON cities.district_id = districts.id
                    JOIN provinces ON districts.province_id = provinces.id";
$locations_result = mysqli_query($conn, $locations_query);

// Fetch all users
$users_query = "SELECT id, first_name, last_name FROM users";
$users_result = mysqli_query($conn, $users_query);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="<?php echo $burl . '/admin/managements/index.php'; ?>" class="btn btn-outline-danger mb-3">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="fw-bold mb-0">Create a New Hotel</h3>
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

                    <form action="create.php" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="hotel_name" class="form-label fw-semibold">Hotel Name</label>
                            <input type="text" id="hotel_name" name="hotel_name" class="form-control" required>
                            <div class="invalid-feedback">Please enter a hotel name.</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Hotel Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4"
                                required></textarea>
                            <div class="invalid-feedback">Please enter a description.</div>
                        </div>

                        <div class="mb-3">
                            <label for="location_id" class="form-label fw-semibold">Location</label>
                            <select name="location_id" id="location_id" class="form-select" required>
                                <option value="">Select Location</option>
                                <?php while ($location = mysqli_fetch_assoc($locations_result)): ?>
                                    <option value="<?php echo $location['id']; ?>">
                                        <?php echo htmlspecialchars($location['city_name'] . ', ' . $location['district_name'] . ', ' . $location['province_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <div class="invalid-feedback">Please select a location.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" accept="image/*" name="photo" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Create Hotel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Bootstrap Form Validation
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

<!-- SELECT 
    locations.id AS location_id,
    cities.name AS city_name,
    districts.name AS district_name,
    provinces.name AS province_name
FROM locations
JOIN cities ON locations.city_id = cities.id
JOIN districts ON cities.district_id = districts.id
JOIN provinces ON districts.province_id = provinces.id; -->