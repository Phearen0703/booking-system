<?php
$title = "Create Hotel";
$page = "create";

include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");

// Database connection
$conn = mysqli_connect("localhost", "root", "", "booking_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $hotel_name = mysqli_real_escape_string($conn, $_POST['hotel_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location_id = $_POST['location_id']; // Assuming location ID is selected
    $owner_id = $_POST['owner_id']; // Assuming owner ID is selected
    $created_at = date("Y-m-d H:i:s"); // Current date and time

    // Insert the new hotel data into the `hotels` table
    $insert_query = "INSERT INTO hotels (name, description, location_id, owner_id, created_at) 
                     VALUES ('$hotel_name', '$description', '$location_id', '$owner_id', '$created_at')";

    if (mysqli_query($conn, $insert_query)) {
        $success_message = "Hotel created successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Fetch the locations for the select options
$locations_query = "SELECT * FROM locations";
$locations_result = mysqli_query($conn, $locations_query);

// Fetch all users (without role filter, modify if needed based on your schema)
$users_query = "SELECT * FROM users"; // Removed WHERE clause for role
$users_result = mysqli_query($conn, $users_query);
?>

<div class="container">
    <h2>Create a New Hotel</h2>

    <?php
    if (isset($success_message)) {
        echo "<div class='alert alert-success'>$success_message</div>";
    }
    if (isset($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    }
    ?>

    <form action="create.php" method="POST">
        <div class="form-group">
            <label for="hotel_name">Hotel Name:</label>
            <input type="text" id="hotel_name" name="hotel_name" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="description">Hotel Description:</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="location_id">Location:</label>
            <select name="location_id" id="location_id" class="form-control" required>
                <option value="">Select Location</option>
                <?php
                while ($location = mysqli_fetch_assoc($locations_result)) {
                    echo "<option value='" . $location['id'] . "'>" . $location['city'] . ", " . $location['district'] . ", " . $location['province'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="owner_id">Owner:</label>
            <select name="owner_id" id="owner_id" class="form-control" required>
                <option value="">Select Owner</option>
                <?php
                while ($user = mysqli_fetch_assoc($users_result)) {
                    echo "<option value='" . $user['id'] . "'>" . $user['first_name'] . " " . $user['last_name'] . "</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create Hotel</button>
    </form>

    <!-- Back Button -->
    <a href="index.php" class="btn btn-secondary mt-3">Back</a>
</div>

<?php
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php");
?>
