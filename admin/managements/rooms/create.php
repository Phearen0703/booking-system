<?php
$title = "Add Rooms to Hotel";
$page = "create_room";
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");

// Fetch Hotels
$hotels_query = "SELECT id, name FROM hotels";
$hotels_result = mysqli_query($conn, $hotels_query);

// Fetch Room Types for the dropdown (from rooms table)
$room_types_query = "SELECT DISTINCT room_name FROM rooms";
$room_types_result = mysqli_query($conn, $room_types_query);

// Handle form submission for adding rooms to a hotel
$success_message = $error_message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['hotel_id']) && !empty($_POST['room_types']) && !empty($_POST['prices']) && !empty($_POST['availabilities'])) {
        $hotel_id = $_POST['hotel_id'];
        foreach ($_POST['room_types'] as $index => $room_type) {
            $price = $_POST['prices'][$index];
            $availability = $_POST['availabilities'][$index];
            // Insert into hotel_rooms table
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
                                <div class="input-group">
                                    <select name="room_types[]" class="form-select" required>
                                        <option value="">Choose Room Type</option>
                                        <?php while ($room = mysqli_fetch_assoc($room_types_result)): ?>
                                            <option value="<?php echo htmlspecialchars($room['room_name']); ?>">
                                                <?php echo htmlspecialchars($room['room_name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manageRoomsModal">Manage Rooms</button>
                                </div>
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

<!-- Manage Rooms Modal -->
<div class="modal fade" id="manageRoomsModal" tabindex="-1" aria-labelledby="manageRoomsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageRoomsLabel">Manage Rooms</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Room List Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Room Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="roomList">
                        <!-- Room data will be inserted here via AJAX -->
                    </tbody>
                </table>
                <!-- Add/Edit Room Form inside Modal -->
                <form id="addRoomForm" class="needs-validation" novalidate>
                    <input type="hidden" id="room_id" name="room_id">
                    <div class="mb-3">
                        <label for="room_name" class="form-label">Room Name</label>
                        <input type="text" id="room_name" name="room_name" class="form-control" required>
                        <div class="invalid-feedback">
                            Please enter a room name.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php"); ?>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Add new room entry in the main form dynamically
    document.getElementById("add_room").addEventListener("click", function () {
        var roomFields = document.getElementById("room_fields");
        var newRoomEntry = document.querySelector(".room-entry").cloneNode(true);
        newRoomEntry.querySelectorAll("input, select").forEach(input => input.value = "");
        roomFields.appendChild(newRoomEntry);
    });

    // Remove room entry from the main form
    document.getElementById("room_fields").addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-room")) {
            if (document.querySelectorAll(".room-entry").length > 1) {
                e.target.parentElement.remove();
            } else {
                alert("At least one room entry is required.");
            }
        }
    });

    // Bootstrap form validation for any form with .needs-validation
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

    // Function to load room data via AJAX and update the modal table
    function loadRooms() {
        fetch("http://localhost/booking-system/admin/managements/rooms/manage_rooms/rooms.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "action=fetch"
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok: " + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            let roomList = document.getElementById("roomList");
            roomList.innerHTML = "";
            if (data && data.length > 0) {
                data.forEach(room => {
                    roomList.innerHTML += `
                        <tr>
                            <td>${room.id}</td>
                            <td>${room.room_name}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-room" data-id="${room.id}" data-room="${room.room_name}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-room" data-id="${room.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                roomList.innerHTML = "<tr><td colspan='3'>No rooms found.</td></tr>";
            }

            // Attach event listeners for Edit buttons
            document.querySelectorAll(".edit-room").forEach(btn => {
                btn.addEventListener("click", function () {
                    document.getElementById("room_id").value = this.dataset.id;
                    document.getElementById("room_name").value = this.dataset.room;
                });
            });

            // Attach event listeners for Delete buttons
            document.querySelectorAll(".delete-room").forEach(btn => {
                btn.addEventListener("click", function () {
                    if (confirm("Are you sure you want to delete this room?")) {
                        deleteRoom(this.dataset.id);
                    }
                });
            });
        })
        .catch(error => console.error("Error loading rooms:", error));
    }

    // Load rooms when the modal is shown (Bootstrap 5 event)
    var manageRoomsModal = document.getElementById('manageRoomsModal');
    manageRoomsModal.addEventListener('shown.bs.modal', loadRooms);

    // Handle Add/Update Room form submission inside the modal
    document.getElementById("addRoomForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let roomId = document.getElementById("room_id").value; // Hidden field for update
        let roomName = document.getElementById("room_name").value; // Input for room name

        let formData = new URLSearchParams();
        formData.append("room_name", roomName);
        if (roomId) {
            formData.append("room_id", roomId);
        }

        fetch("http://localhost/booking-system/admin/managements/rooms/manage_rooms/edit.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: formData.toString()
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok: " + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.success);
                loadRooms(); // Reload room list to reflect changes
                document.getElementById("addRoomForm").reset(); // Clear modal form
                // Close modal using Bootstrap 5's modal instance
                var modalInstance = bootstrap.Modal.getInstance(document.getElementById('manageRoomsModal'));
                modalInstance.hide();
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.error("Error saving room:", error));
    });

    // Delete Room function
    function deleteRoom(id) {
        fetch("http://localhost/booking-system/admin/managements/rooms/manage_rooms/rooms.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "action=delete&id=" + id
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok: " + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                loadRooms(); // Reload room list after deletion
            } else {
                alert("Error deleting room: " + data.error);
            }
        })
        .catch(error => console.error("Error deleting room:", error));
    }
});
</script>
