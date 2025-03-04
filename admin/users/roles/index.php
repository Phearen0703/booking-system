<?php
$title = "Role Page";
$page = "user";

include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_role'])) {
        $role_name = mysqli_real_escape_string($conn, $_POST['role_name']);
    
        // Check if role already exists
        $check_query = "SELECT * FROM roles WHERE role_name = '$role_name'";
        $result = mysqli_query($conn, $check_query);
    
        if (mysqli_num_rows($result) > 0) {
            $message = "<div class='alert alert-warning'>Role already exists!</div>";
        } else {
            $query = "INSERT INTO roles (role_name) VALUES ('$role_name')";
            if (mysqli_query($conn, $query)) {
                $message = "<div class='alert alert-success'>Role added successfully.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Error adding role: " . mysqli_error($conn) . "</div>";
            }
        }
    }
    

    if (isset($_POST['update_role'])) {
        $role_id = intval($_POST['role_id']);
        $role_name = mysqli_real_escape_string($conn, trim($_POST['role_name']));

        // Check if role name already exists
        $check_query = "SELECT * FROM roles WHERE role_name = '$role_name' AND id != $role_id";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $message = "<div class='alert alert-warning'>Role name already exists!</div>";
        } else {
            $query = "UPDATE roles SET role_name='$role_name' WHERE id=$role_id";
            if (mysqli_query($conn, $query)) {
                echo "<script>
                        alert('Role updated successfully!');
                        window.location.href = window.location.href; // Reload page
                      </script>";
                exit;
            } else {
                $message = "<div class='alert alert-danger'>Error updating role: " . mysqli_error($conn) . "</div>";
            }
        }
    }
}

// Handle Delete Request
if (isset($_GET['delete'])) {
    $role_id = intval($_GET['delete']);
    $query = "DELETE FROM roles WHERE id=$role_id";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Role deleted successfully!');
                window.location.href = 'index.php'; // Redirect to prevent resubmission
              </script>";
        exit;
    } else {
        $message = "<div class='alert alert-danger'>Error deleting role: " . mysqli_error($conn) . "</div>";
    }
}

$roles = mysqli_query($conn, "SELECT * FROM roles ORDER BY id DESC");
?>
<a href="<?php echo $burl . '/admin/users/index.php'; ?>" class="btn btn-danger">
    <i class="fa-solid fa-reply"></i> Back
</a>

<div class="mt-3">
    <form method="POST">
        <div class="card h-100"> <!-- Ensure card takes full height -->
            <div class="card-header fs-4"><i class="fa-solid fa-gear"></i> Role Management</div>
            <div class="row d-flex justify-content-between h-100">
                <div class="col-4 border-end d-flex flex-column"> <!-- Makes column stretch -->
                    <div class="card-body flex-grow-1">
                        <?php if (isset($message)) echo $message; ?>
                        <div class="mb-3">
                            <label class="form-label">Role Name</label>
                            <input type="text" name="role_name" class="form-control" required>
                        </div>
                        <button type="submit" name="add_role" class="btn btn-primary w-100">Add Role</button>
                    </div>
                </div>
                <div class="col-8 d-flex flex-column">
                    <table class="table table-hover my-0 text-center">
                        <thead>
                            <tr>
                                <th>N.o</th>
                                <th class="d-none d-xl-table-cell">Role Name</th>
                                <th class="d-none d-xl-table-cell">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            while ($row = mysqli_fetch_assoc($roles)) { ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td class="d-none d-xl-table-cell"><?= htmlspecialchars($row['role_name']) ?></td>
                                    <td class="d-none d-xl-table-cell">
                                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editRoleModal<?= $row['id'] ?>">Edit</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>


<?php include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php"); ?>
