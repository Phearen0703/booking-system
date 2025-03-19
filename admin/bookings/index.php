<?php
$title = "Booking Page";
$page = "booking";
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");

// Handle search input
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : "";
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : "";

// Pagination settings
$limit = 10;
$page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page_number - 1) * $limit;

// Fetch total count
$count_query = "SELECT COUNT(*) AS total FROM bookings 
                JOIN users ON bookings.user_id = users.id
                JOIN hotels ON bookings.hotel_id = hotels.id
                JOIN hotel_rooms ON bookings.room_id = hotel_rooms.id
                WHERE 1";

if (!empty($check_in) && !empty($check_out)) {
    $count_query .= " AND bookings.check_in >= '$check_in' AND bookings.check_out <= '$check_out'";
}

if (!empty($search)) {
    $count_query .= " AND (users.first_name LIKE '%$search%' OR users.last_name LIKE '%$search%' 
                         OR hotels.name LIKE '%$search%' OR hotel_rooms.room_type LIKE '%$search%')";
}

$count_result = mysqli_query($conn, $count_query);
$total_rows = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch filtered bookings
$query = "SELECT bookings.*, 
       users.first_name, 
       users.last_name, 
       hotels.name AS hotel_name, 
       hotel_rooms.room_type AS room_name
FROM bookings
JOIN users ON bookings.user_id = users.id
JOIN hotels ON bookings.hotel_id = hotels.id
JOIN hotel_rooms ON bookings.room_id = hotel_rooms.id
WHERE 1";

// Apply date filter
if (!empty($check_in) && !empty($check_out)) {
    $query .= " AND bookings.check_in >= '$check_in' AND bookings.check_out <= '$check_out'";
}

// Apply search filter
if (!empty($search)) {
    $query .= " AND (users.first_name LIKE '%$search%' OR users.last_name LIKE '%$search%' 
                     OR hotels.name LIKE '%$search%' OR hotel_rooms.room_type LIKE '%$search%'
                     OR hotel_rooms.room_number LIKE '%$search%')";
}

// Apply pagination
$query .= " LIMIT $offset, $limit";

$result = mysqli_query($conn, $query);
?>

<div class="d-flex justify-content-between align-items-center">
    <form method="GET" class="d-flex">
        <input type="search" name="search" class="form-control me-2" placeholder="Search bookings..." value="<?php echo htmlspecialchars($search); ?>">
        <input type="date" name="check_in" class="form-control me-2" value="<?php echo htmlspecialchars($check_in); ?>">
        <input type="date" name="check_out" class="form-control me-2" value="<?php echo htmlspecialchars($check_out); ?>">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Booking List</h5>
    </div>
    <table class="table table-hover text-center">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Hotel</th>
                <th>Room</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = $offset + 1;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['hotel_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['room_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['check_in']); ?></td>
                        <td><?php echo htmlspecialchars($row['check_out']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-primary">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo htmlspecialchars($row['id']); ?>)" class="btn btn-danger">
                                <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        </td>

                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="8" class="text-center">No bookings found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <nav>
        <ul class="pagination justify-content-end m-3">
            <?php if ($page_number > 1) { ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page_number - 1; ?>">Previous</a></li>
            <?php } ?>
            
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?php echo ($i == $page_number) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php } ?>

            <?php if ($page_number < $total_pages) { ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page_number + 1; ?>">Next</a></li>
            <?php } ?>
        </ul>
    </nav>
</div>

<script>
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this booking?")) {
        window.location.href = "delete_booking.php?id=" + id;
    }
}
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php"); ?>
