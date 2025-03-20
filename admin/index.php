<?php
$title = "Dashboard";
$page = "dashboard";
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");

// Fetch date range from form inputs
$from_date = $_GET['from_date'] ?? null;
$to_date = $_GET['to_date'] ?? null;

// Customers query
$total_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role_id = 3"))['total'] ?? 0;

// Users query
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role_id = 2"))['total'] ?? 0;

// Hotels query
$total_hotels = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM hotels"))['total'] ?? 0;

// Rooms query
$total_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM hotel_rooms"))['total'] ?? 0;

// Payments query with date filter
$payment_query = "SELECT SUM(amount) AS total FROM payments";
if ($from_date && $to_date) {
    $payment_query .= " WHERE paid_at BETWEEN '$from_date' AND '$to_date'";
}
$total_payments = mysqli_fetch_assoc(mysqli_query($conn, $payment_query))['total'] ?? 0;

// Room Types query
$total_room_types = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT room_type) AS total FROM hotel_rooms"))['total'] ?? 0;

// Bookings query with date filter
$booking_query = "SELECT COUNT(*) AS total FROM bookings";
if ($from_date && $to_date) {
    $booking_query .= " WHERE created_at BETWEEN '$from_date' AND '$to_date'";
}
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, $booking_query))['total'] ?? 0;
?>

<div class="container mt-5">
    <h2 class="text-center fw-bold mb-4"><i class="fas fa-chart-line"></i> Dashboard Overview</h2>
    
    <!-- Date Filter Form -->
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <label for="from_date">From Date</label>
                <input type="date" id="from_date" name="from_date" class="form-control" value="<?= $_GET['from_date'] ?? '' ?>">
            </div>
            <div class="col-lg-3 col-md-6">
                <label for="to_date">To Date</label>
                <input type="date" id="to_date" name="to_date" class="form-control" value="<?= $_GET['to_date'] ?? '' ?>">
            </div>
            <div class="col-lg-2 col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <!-- Dashboard Content -->
    <div class="row g-4">
        <!-- Customers -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-primary mb-2"></i>
                    <h5 class="fw-bold">Customers</h5>
                    <h3 class="text-dark"><?= number_format($total_customers) ?></h3>
                </div>
            </div>
        </div>

        <!-- Users -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body text-center">
                    <i class="fas fa-user fa-3x text-success mb-2"></i>
                    <h5 class="fw-bold">Users</h5>
                    <h3 class="text-dark"><?= number_format($total_users) ?></h3>
                </div>
            </div>
        </div>

        <!-- Hotels -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body text-center">
                    <i class="fas fa-hotel fa-3x text-danger mb-2"></i>
                    <h5 class="fw-bold">Hotels</h5>
                    <h3 class="text-dark"><?= number_format($total_hotels) ?></h3>
                </div>
            </div>
        </div>

        <!-- Rooms -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body text-center">
                    <i class="fas fa-bed fa-3x text-warning mb-2"></i>
                    <h5 class="fw-bold">Rooms</h5>
                    <h3 class="text-dark"><?= number_format($total_rooms) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Payments -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body text-center">
                    <i class="fas fa-credit-card fa-3x text-primary mb-2"></i>
                    <h5 class="fw-bold">Total Payments</h5>
                    <h3 class="text-dark"><?= number_format($total_payments, 2) ?></h3>
                </div>
            </div>
        </div>

        <!-- Bookings -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body text-center">
                    <i class="fas fa-book fa-3x text-success mb-2"></i>
                    <h5 class="fw-bold">Bookings</h5>
                    <h3 class="text-dark"><?= number_format($total_bookings) ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php"); ?>
