<?php
session_start();
include './includes/db_connect.php';

// Only super admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'super_admin') {
    header("Location: login.php");
    exit();
}

// Fetch stats
$total_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='customer'"))['total'];
$total_contractors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='contractor'"))['total'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings"))['total'];

// Bookings per contractor
$bookings_per_contractor = mysqli_query($conn, "
    SELECT c.name, COUNT(b.id) AS total_bookings 
    FROM users c 
    LEFT JOIN bookings b ON c.id = b.contractor_id 
    WHERE c.role='contractor'
    GROUP BY c.id
");

// All bookings
$all_bookings = mysqli_query($conn, "
    SELECT b.*, u.name AS customer_name, u.email AS customer_email, u.phone AS customer_phone,
           c.name AS contractor_name
    FROM bookings b
    JOIN users u ON b.customer_id = u.id
    JOIN users c ON b.contractor_id = c.id
    ORDER BY b.created_at DESC
");

// Reviews
$reviews = mysqli_query($conn, "
    SELECT r.rating, r.comment AS review_text, r.created_at, 
           c.name AS customer_name, 
           ctr.name AS contractor_name 
    FROM reviews r
    JOIN users c ON r.customer_id = c.id
    JOIN users ctr ON r.contractor_id = ctr.id
    ORDER BY r.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Super Admin Dashboard</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* ---------- GENERAL ---------- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fa;
            color: #333;
        }

        a {
            text-decoration: none;
        }

        .container {
            max-width: 1300px;
            margin: auto;
            padding: 30px;
        }

        /* ---------- NAVBAR ---------- */
        .navbar {
            background: linear-gradient(90deg, #4f46e5, #6366f1);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .navbar h2 {
            font-size: 24px;
            font-weight: 700;
        }

        .navbar a {
            padding: 8px 20px;
            background: #ef4444;
            border-radius: 6px;
            font-weight: 500;
            transition: 0.3s;
        }

        .navbar a:hover {
            background: #dc2626;
        }

        /* ---------- HEADER ---------- */
        h1 {
            font-size: 32px;
            margin-bottom: 25px;
            color: #1f2937;
        }

        /* ---------- CARDS ---------- */
        .cards {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .card {
            flex: 1;
            min-width: 220px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.15);
        }

        .card h3 {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .card p {
            font-size: 30px;
            font-weight: 700;
        }

        /* ---------- TABLES ---------- */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
        }

        th {
            background: #4f46e5;
            color: #fff;
            padding: 14px;
            font-weight: 600;
            text-align: left;
        }

        td {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:hover {
            background: #f3f4f6;
            transition: 0.2s;
        }

        /* ---------- STATUS COLORS ---------- */
        .status-pending {
            color: #d97706;
            font-weight: 600;
        }

        .status-confirmed {
            color: #16a34a;
            font-weight: 600;
        }

        .status-rejected {
            color: #dc2626;
            font-weight: 600;
        }

        .status-completed {
            color: #2563eb;
            font-weight: 600;
        }

        /* ---------- SECTION TITLES ---------- */
        h2.section-title {
            font-size: 22px;
            margin: 25px 0 10px;
            color: #1f2937;
            border-left: 4px solid #4f46e5;
            padding-left: 10px;
        }

        /* ---------- REVIEWS ---------- */
        .review-stars {
            color: #fbbf24;
        }

        @media(max-width:768px) {
            .cards {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h2>Super Admin Dashboard</h2>
        <div>
            <strong><?= htmlspecialchars($_SESSION['user_name']); ?></strong>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h1>Overview</h1>

        <!-- Stats Cards -->
        <div class="cards">
            <div class="card">
                <h3>Total Customers</h3>
                <p><?= $total_customers; ?></p>
            </div>
            <div class="card">
                <h3>Total Contractors</h3>
                <p><?= $total_contractors; ?></p>
            </div>
            <div class="card">
                <h3>Total Bookings</h3>
                <p><?= $total_bookings; ?></p>
            </div>
        </div>

        <!-- Contractor Performance -->
        <h2 class="section-title">Contractor Performance</h2>
        <table>
            <thead>
                <tr>
                    <th>Contractor</th>
                    <th>Total Bookings</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($bookings_per_contractor)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= $row['total_bookings']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- All Bookings -->
        <h2 class="section-title">All Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Contractor</th>
                    <th>Service</th>
                    <th>Date & Time</th>
                    <th>Notes</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($all_bookings)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['customer_name']); ?><br><small><?= htmlspecialchars($row['customer_email']); ?></small></td>
                        <td><?= htmlspecialchars($row['contractor_name']); ?></td>
                        <td><?= htmlspecialchars($row['service_name']); ?></td>
                        <td><?= htmlspecialchars($row['booking_date']); ?></td>
                        <td><?= htmlspecialchars($row['description']); ?></td>
                        <td class="status-<?= strtolower($row['status']); ?>"><?= ucfirst(htmlspecialchars($row['status'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Customer Reviews -->
        <h2 class="section-title">Customer Reviews</h2>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Contractor</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($r = mysqli_fetch_assoc($reviews)): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['customer_name']); ?></td>
                        <td><?= htmlspecialchars($r['contractor_name']); ?></td>
                        <td class="review-stars"><?= str_repeat("⭐", (int)$r['rating']); ?></td>
                        <td><?= htmlspecialchars($r['review_text']); ?></td>
                        <td><?= htmlspecialchars($r['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
