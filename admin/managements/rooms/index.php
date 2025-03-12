<?php
$title = "Room Management";
$page = "rooms";
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");

$limit = 10;
$page_number = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page_number - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';

$query = "SELECT hotel_rooms.*, hotels.name
        FROM hotel_rooms
        LEFT JOIN hotels ON hotel_rooms.hotel_id = hotels.id";

if (!empty($search)) {
    $query .= " WHERE hotels.name LIKE '%$search%' 
                OR cities.name LIKE '%$search%' 
";
}
$query .= " GROUP BY hotels.id ORDER BY hotels.created_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
$total_query = "SELECT COUNT(*) AS total FROM hotels";
$total_result = mysqli_query($conn, $total_query);
$total_records = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_records / $limit);
?>

<div class="d-flex justify-content-between align-items-center">
    <a href="<?php echo $burl . '/admin/managements/rooms/create.php' ?>" class="btn btn-success">
        <i class="fa-solid fa-plus"></i> Add Room
    </a>
    <form method="GET" class="d-flex">
        <input type="search" name="search" class="form-control me-2" placeholder="Search rooms..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<div class="card mt-3">
    <div class="card-header"><h5 class="card-title mb-0">Room List</h5></div>
    <table class="table table-hover text-center">
        <thead>
            <tr><th>#</th><th>Room Name</th><th>Hotel Name</th><th>Availability</th><th>Price</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php $no = $offset + 1; if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['availability']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>

                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                            <button class="btn btn-danger" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php } 
            } else { echo '<tr><td colspan="6" class="text-center">No hotels found.</td></tr>'; } ?>
        </tbody>
    </table>

    <nav>
        <ul class="pagination justify-content-end m-3">
            <?php if ($page_number > 1) { ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page_number - 1; ?>">Previous</a></li>
            <?php } for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?php echo ($i == $page_number) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php } if ($page_number < $total_pages) { ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page_number + 1; ?>">Next</a></li>
            <?php } ?>
        </ul>
    </nav>
</div>

<script>
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this hotel?")) {
        window.location.href = 'actions/delete.php?id=' + id;
    }
}
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php"); ?>
