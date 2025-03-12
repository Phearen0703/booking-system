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

        $stmt = mysqli_prepare($conn, "INSERT INTO hotels (name, description, location_id, owner_id, created_at) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssiss", $hotel_name, $description, $location_id, $owner_id, $created_at);

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

                    <form action="create.php" method="POST" class="needs-validation" novalidate>
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