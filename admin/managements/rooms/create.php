<?php
$title = "Add Rooms to Hotel";
$page = "create_room";
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");

// Fetch Hotels
$hotels_query = "SELECT id, name FROM hotels";
$hotels_result = mysqli_query($conn, $hotels_query);

// Fetch Room Types
$room_types_query = "SELECT DISTINCT room_type FROM hotel_rooms";
$room_types_result = mysqli_query($conn, $room_types_query);

// Handle form submission
$success_message = $error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['hotel_id']) && !empty($_POST['room_types']) && !empty($_POST['prices']) && !empty($_POST['availabilities'])) {
        $hotel_id = $_POST['hotel_id'];

        foreach ($_POST['room_types'] as $index => $room_type) {
            $price = $_POST['prices'][$index];
            $availability = $_POST['availabilities'][$index];

            // Insert into hotel_rooms
            $stmt = mysqli_prepare($conn, "INSERT INTO hotel_rooms (hotel_id, room_type, price, availability) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "isdi", $hotel_id, $room_type, $price, $availability);

            if (!mysqli_stmt_execute($stmt)) {
                $error_message = "Error: " . mysqli_error($conn);
                break;
            }
            mysqli_stmt_close($stmt);
        }
        if (!$error_message) {
            $success_message = "Rooms added successfully!";
        }
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="<?php echo $burl . '/admin/managements/rooms/index.php'; ?>" class="btn btn-outline-danger mb-3">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="fw-bold mb-0">Add Rooms to Hotel</h3>
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
                            <label for="hotel_id" class="form-label fw-semibold">Select Hotel</label>
                            <select name="hotel_id" id="hotel_id" class="form-select" required>
                                <option value="">Choose a Hotel</option>
                                <?php while ($hotel = mysqli_fetch_assoc($hotels_result)): ?>
                                    <option value="<?php echo $hotel['id']; ?>">
                                        <?php echo htmlspecialchars($hotel['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <div class="invalid-feedback">Please select a hotel.</div>
                        </div>

                        <div id="room_fields">
                            <div class="room-entry mb-3">
                                <label class="form-label fw-semibold">Room Type</label>
                                <select name="room_types[]" class="form-select" required>
                                    <option value="">Choose Room Type</option>
                                    <?php while ($room = mysqli_fetch_assoc($room_types_result)): ?>
                                        <option value="<?php echo htmlspecialchars($room['room_type']); ?>">
                                            <?php echo htmlspecialchars($room['room_type']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <input type="number" name="prices[]" class="form-control mt-2" placeholder="Enter Price" required>
                                <select name="availabilities[]" class="form-select mt-2" required>
                                    <option value="1">Available</option>
                                    <option value="0">Not Available</option>
                                </select>
                                <button type="button" class="btn btn-danger mt-2 remove-room">Remove</button>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary mt-3" id="add_room">+ Add Another Room</button>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Save Rooms</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("add_room").addEventListener("click", function () {
        var roomFields = document.getElementById("room_fields");
        var newRoomEntry = document.querySelector(".room-entry").cloneNode(true);
        newRoomEntry.querySelectorAll("input, select").forEach(input => input.value = "");
        roomFields.appendChild(newRoomEntry);
    });

    document.getElementById("room_fields").addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-room")) {
            e.target.parentElement.remove();
        }
    });

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
