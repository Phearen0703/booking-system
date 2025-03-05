<?php
$title = "User Page";
$page = "user";

include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");

// Set the number of records per page
$limit = 10;
$page_number = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page_number - 1) * $limit;

// Handle search query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Base query with search filter
$query = "SELECT users.id, users.first_name, users.last_name, users.contact, users.photo, roles.role_name 
          FROM users 
          INNER JOIN roles ON users.role_id = roles.id";

if (!empty($search)) {
    $query .= " WHERE users.first_name LIKE '%$search%' 
                OR users.last_name LIKE '%$search%' 
                OR users.contact LIKE '%$search%' 
                OR roles.role_name LIKE '%$search%'";
}

// Get total records (for pagination)
$total_query = str_replace("SELECT users.id, users.first_name, users.last_name, users.contact, users.photo, roles.role_name", "SELECT COUNT(*) as total", $query);
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Apply limit and offset for pagination
$query .= " LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<div class="d-flex justify-content-between align-items-center">
    <a href="<?php echo $burl . '/admin/users/create.php' ?>" class="btn btn-success">
        <i class="fa-solid fa-plus"></i> Create
    </a>

    <div class="d-flex">
        <!-- Search Form -->
        <form method="GET" class="d-flex me-2">
            <input type="search" name="search" class="form-control me-2" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Export Button -->
        <a href="actions/export.php<?php echo (!empty($search) ? '?search=' . urlencode($search) : ''); ?>" class="btn btn-success">
        <i class="fa-solid fa-file-excel"></i></i> Export to Excel
        </a>
    </div>
</div>


<div class="col-12 d-flex pt-3">
    <div class="card flex-fill">
        <div class="card-header">
            <h5 class="card-title mb-0">User List</h5>
        </div>
        <table class="table table-hover my-0 text-center">
            <thead>
                <tr>
                    <th>N.0</th>
                    <th class="d-none d-xl-table-cell">First Name</th>
                    <th class="d-none d-xl-table-cell">Last Name</th>
                    <th class="d-none d-md-table-cell">Contact</th>
                    <th class="d-none d-md-table-cell">Role</th>
                    <th class="d-none d-md-table-cell">Photo</th>
                    <th class="d-none d-md-table-cell">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = $offset + 1;
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td class="d-none d-xl-table-cell"><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td class="d-none d-xl-table-cell"><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><span class="badge bg-success"><?php echo htmlspecialchars($row['contact']); ?></span></td>
                        <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($row['role_name']); ?></td>
                        <td class="d-none d-md-table-cell">
                            <img src="<?php echo $burl . '/admin/public/img/' . $row['photo']; ?>"
                                class="avatar img-fluid rounded me-1" alt="User Photo" width="50">
                        </td>
                        <td class="d-none d-xl-table-cell">
                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete</a>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-end m-3">
                <?php if ($page_number > 1) { ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="?page=<?php echo ($page_number - 1) . (!empty($search) ? '&search=' . urlencode($search) : ''); ?>">Previous</a>
                    </li>
                <?php } ?>

                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <li class="page-item <?php echo ($i == $page_number) ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="?page=<?php echo $i . (!empty($search) ? '&search=' . urlencode($search) : ''); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($page_number < $total_pages) { ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="?page=<?php echo ($page_number + 1) . (!empty($search) ? '&search=' . urlencode($search) : ''); ?>">Next</a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this user?")) {
        window.location.href = 'actions/delete.php?id=' + id;
    }
}
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php"); ?>