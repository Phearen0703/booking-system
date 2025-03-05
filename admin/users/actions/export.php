<?php
// Prevent output before headers are sent
ob_start();

include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");

// Handle search query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Define query
$query = "SELECT users.id, users.first_name, users.last_name, users.contact, users.photo, roles.role_name 
          FROM users 
          INNER JOIN roles ON users.role_id = roles.id";

if (!empty($search)) {
    $query .= " WHERE users.first_name LIKE ? 
                OR users.last_name LIKE ? 
                OR users.contact LIKE ? 
                OR roles.role_name LIKE ?";
}

// Prepare and execute statement
$stmt = $conn->prepare($query);
if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bind_param('ssss', $searchParam, $searchParam, $searchParam, $searchParam);
}
$stmt->execute();
$result = $stmt->get_result();

// Set headers for Excel export
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=users_report.xls");

// Output column names
echo "ID\tFirst Name\tLast Name\tContact\tRole\tPhoto\n";

// Output data
while ($row = $result->fetch_assoc()) {
    echo "{$row['id']}\t{$row['first_name']}\t{$row['last_name']}\t{$row['contact']}\t{$row['role_name']}\t{$row['photo']}\n";
}

ob_end_flush();
exit;
?>