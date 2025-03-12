<?php
$title = "Hotel Management";
$page = "management";

include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");

// Set records per page
$limit = 10;
$page_number = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page_number - 1) * $limit;

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepare the base query
$query = "SELECT 
    hotels.id, 
    hotels.name AS hotel_name, 
    hotels.description, 
    locations.id AS location_id, 
    cities.name AS city_name, 
    districts.name AS district_name, 
    provinces.name AS province_name, 
    hotels.created_at, 
    CONCAT(users.first_name, ' ', users.last_name) AS owner_name, 
    COUNT(hotel_rooms.id) AS total_rooms, 
    IFNULL(AVG(ratings.rating), 0) AS avg_rating,
    GROUP_CONCAT(DISTINCT hotel_rooms.room_type) AS room_types
FROM hotels
LEFT JOIN locations ON hotels.location_id = locations.id
LEFT JOIN cities ON locations.city_id = cities.id
LEFT JOIN districts ON cities.district_id = districts.id
LEFT JOIN provinces ON districts.province_id = provinces.id
LEFT JOIN users ON hotels.owner_id = users.id 
LEFT JOIN hotel_rooms ON hotels.id = hotel_rooms.hotel_id 
LEFT JOIN ratings ON hotels.id = ratings.hotel_id
";

// If search term is provided, update the query to search
if (!empty($search)) {
    $query .= "WHERE (hotels.name LIKE ? 
                 OR cities.name LIKE ? 
                 OR districts.name LIKE ? 
                 OR provinces.name LIKE ? 
                 OR CONCAT(users.first_name, ' ', users.last_name) LIKE ?)";
}

// Add pagination
$query .= " GROUP BY hotels.id 
            ORDER BY hotels.created_at DESC 
            LIMIT ? OFFSET ?";

// Prepare and execute the query
$stmt = $conn->prepare($query);

if (!empty($search)) {
    $search_term = "%" . $search . "%";
    $stmt->bind_param("sssssi", $search_term, $search_term, $search_term, $search_term, $search_term, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die("Query failed: " . mysqli_error($conn)); // Debugging
}

// Pagination query
$total_query = "SELECT COUNT(*) as total 
                FROM hotels
                LEFT JOIN locations ON hotels.location_id = locations.id
                LEFT JOIN users ON hotels.owner_id = users.id";

// If search term is provided, update the total query to include the search
if (!empty($search)) {
    $total_query .= " WHERE hotels.name LIKE ? 
                      OR cities.name LIKE ? 
                      OR districts.name LIKE ? 
                      OR provinces.name LIKE ? 
                      OR CONCAT(users.first_name, ' ', users.last_name) LIKE ?";
}

// Prepare and execute the total count query
$total_stmt = $conn->prepare($total_query);

if (!empty($search)) {
    $total_stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
}

$total_stmt->execute();
$total_result = $total_stmt->get_result();
if (!$total_result) {
    die("Query failed: " . mysqli_error($conn)); // Debugging
}

$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

?>



<div class="d-flex justify-content-between align-items-center">
    <a href="<?php echo $burl . '/admin/managements/create.php' ?>" class="btn btn-success">
        <i class="fa-solid fa-plus"></i> Add Hotel
    </a>

    <form method="GET" class="d-flex">
        <input type="search" name="search" class="form-control me-2" placeholder="Search hotels..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Hotel List</h5>
    </div>
    <table class="table table-hover text-center">
        <thead>
            <tr>
                <th>#</th>
                <th>Hotel Name</th>
                <th>Room Type</th>
                <th>Location</th>
                <th>Status</th>
                <th>Owner</th>
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
                        <td><?php echo htmlspecialchars($row['hotel_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['room_types']); ?></td>
                        <td><?php echo htmlspecialchars($row['city_name'] . ', ' . $row['district_name'] . ', ' . $row['province_name']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $row['total_rooms'] > 0 ? 'success' : 'danger'; ?>">
                                <?php echo $row['total_rooms'] > 0 ? 'Open' : 'Closed'; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($row['owner_name'] ?? 'N/A'); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary"><i class="fa-solid fa-pen"></i> Edit</a>
                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6" class="text-center">No hotels found.</td>
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
    if (confirm("Are you sure you want to delete this hotel?")) {
        window.location.href = 'actions/delete.php?id=' + id;
    }
}
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php"); ?>
