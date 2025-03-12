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


<div class="container">
<a href="<?php echo $burl . '/admin/users/index.php'; ?>" class="btn btn-outline-danger mb-3">
    <i class="fa-solid fa-arrow-left"></i> Back
</a>
    <form method="POST">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fa-solid fa-gear me-2"></i> Role Management
            </div>
            <div class="row g-0">
                <!-- Left Column: Add Role Form -->
                <div class="col-md-4 border-end p-3">
                    <?php if (isset($message)) echo "<div class='alert alert-info text-center'>$message</div>"; ?>
                    <div class="mb-3">
                        <label class="form-label">Role Name</label>
                        <input type="text" name="role_name" class="form-control" required>
                    </div>
                    <button type="submit" name="add_role" class="btn btn-success w-100">
                        <i class="fa-solid fa-plus"></i> Add Role
                    </button>
                </div>
                
                <!-- Right Column: Role List -->
                <div class="col-md-8 p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Role Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                while ($row = mysqli_fetch_assoc($roles)) { ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($row['role_name']) ?></td>
                                        <td>
                                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editRoleModal<?= $row['id'] ?>">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<?php include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php"); ?>
