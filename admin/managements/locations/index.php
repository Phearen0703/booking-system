<?php
$title = "Locations";
$page = "locations";
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");


// Handle search input
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Pagination settings
$limit = 10;
$page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page_number - 1) * $limit;

// Fetch total count
$count_query = "SELECT COUNT(*) AS total FROM locations";
$count_result = mysqli_query($conn, $count_query);
$total_rows = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch all locations with province, district, and city names
$query = "SELECT 
            locations.id AS location_id,
            cities.name AS city_name,
            districts.name AS district_name,
            provinces.name AS province_name
          FROM locations
          JOIN cities ON locations.city_id = cities.id
          JOIN districts ON cities.district_id = districts.id
          JOIN provinces ON districts.province_id = provinces.id";

// Apply search filter
if (!empty($search)) {
    $query .= " WHERE locations.name LIKE '%$search%' 
                OR cities.name LIKE '%$search%' 
                OR districts.name LIKE '%$search%' 
                OR provinces.name LIKE '%$search%'";
}

// Apply pagination
$query .= " LIMIT $offset, $limit";

$result = mysqli_query($conn, $query);
?>

<div class="d-flex justify-content-between align-items-center">
    <a href="<?php echo $burl . '/admin/managements/locations/create.php' ?>" class="btn btn-success">
        <i class="fa-solid fa-plus"></i> Add Location
    </a>

    <form method="GET" class="d-flex">
        <input type="search" name="search" class="form-control me-2" placeholder="Search locations..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Location List</h5>
    </div>
    <table class="table table-hover text-center">
        <thead>
            <tr>
                <th>#</th>
                <th>Province</th>
                <th>District</th>
                <th>City</th>
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
                        <td><?php echo htmlspecialchars($row['province_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['district_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['city_name']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['location_id']; ?>" class="btn btn-primary"><i class="fa-solid fa-pen"></i> Edit</a>
                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['location_id']; ?>)" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="5" class="text-center">No locations found.</td>
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
    if (confirm("Are you sure you want to delete this location?")) {
        window.location.href = "delete_location.php?id=" + id;
    }
}
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php"); ?>
