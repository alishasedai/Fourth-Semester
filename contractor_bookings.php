<?php
session_start();
include './includes/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'contractor') {
    header("Location: login.php");
    exit();
}

$contractor_id = $_SESSION['user_id'];

$sql = "SELECT b.*, u.name AS customer_name, u.email AS customer_email, u.phone AS customer_phone
        FROM bookings b
        JOIN users u ON b.customer_id = u.id
        WHERE b.contractor_id = ?
        ORDER BY b.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $contractor_id);
$stmt->execute();
$result = $stmt->get_result();

$stats_sql = "
SELECT 
    COUNT(r.id) AS total_reviews,
    AVG(r.rating) AS avg_rating
FROM reviews r
JOIN bookings b ON r.booking_id = b.id
WHERE b.contractor_id = ?
";

$stats_stmt = $conn->prepare($stats_sql);
$stats_stmt->bind_param("i", $contractor_id);
$stats_stmt->execute();
$stats = $stats_stmt->get_result()->fetch_assoc();

$total_reviews = $stats['total_reviews'] ?? 0;
$avg_rating = $stats['avg_rating'] ? round($stats['avg_rating'], 1) : 0;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Contractor Dashboard</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
        }

        /* Navbar */
        .navbar {
            background: #161717;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        /* Container */
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Rating Summary Card */
        .stats-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .stars {
            font-size: 22px;
            color: #ffc107;
        }

        /* Booking Card */
        .booking-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            transition: 0.3s;
        }

        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Status Badges */
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.confirmed {
            background: #cce5ff;
            color: #004085;
        }

        .status.completed {
            background: #d4edda;
            color: #155724;
        }

        .status.rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .booking-card p {
            margin: 6px 0;
            font-size: 14px;
            color: #555;
        }

        /* Review Section */
        .review-section {
            margin-top: 15px;
            padding: 15px;
            background: #f9fafc;
            border-left: 4px solid #007bff;
            border-radius: 8px;
        }

        /* Form */
        select {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 10px;
            margin-right: 10px;
        }

        button {
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            transition: 0.3s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.15);
        }

        .no-bookings {
            text-align: center;
            color: #777;
            margin-top: 40px;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h2><?= htmlspecialchars($_SESSION['user_name']); ?> — Contractor Dashboard</h2>
        <div>
            <a href="contractor_dashboard.php">Dashboard</a>
            <!-- <a href="logout.php">Logout</a> -->
        </div>
    </div>

    <div class="container">

        <div class="stats-card">
            <h3>⭐ Review Summary</h3>
            <div class="stars">
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo $i <= round($avg_rating) ? "★" : "☆";
                }
                ?>
            </div>
            <p><strong><?= $avg_rating ?></strong> average rating (<?= $total_reviews ?> reviews)</p>
        </div>

        <h2>📑 My Bookings</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>

                <div class="booking-card">

                    <div class="header">
                        <h3><?= htmlspecialchars($row['service_name']); ?></h3>
                        <span class="status <?= strtolower($row['status']); ?>">
                            <?= ucfirst($row['status']); ?>
                        </span>
                    </div>

                    <p><strong>Date:</strong> <?= htmlspecialchars($row['booking_date']); ?></p>
                    <p><strong>Customer:</strong> <?= htmlspecialchars($row['customer_name']); ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($row['customer_email']); ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($row['customer_phone']); ?></p>
                    <p><strong>Notes:</strong> <?= htmlspecialchars($row['description']); ?></p>

                    <?php
                    $rev_stmt = $conn->prepare("
    SELECT rating, feedback 
    FROM reviews 
    WHERE booking_id = ?
    LIMIT 1
");
                    $rev_stmt->bind_param("i", $row['id']);
                    $rev_stmt->execute();
                    $review = $rev_stmt->get_result()->fetch_assoc();
                    ?>

                    <?php if ($review): ?>
                        <div class="review-section">
                            <strong>Customer Review:</strong><br>
                            <div class="stars">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $review['rating'] ? "★" : "☆";
                                }
                                ?>
                            </div>
                            <p><?= htmlspecialchars($review['feedback']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($row['status'] === 'pending' || $row['status'] === 'confirmed'): ?>
                        <form method="POST" action="update_status.php">
                            <input type="hidden" name="booking_id" value="<?= $row['id']; ?>">

                            <select name="new_status">
                                <?php if ($row['status'] === 'pending'): ?>
                                    <option value="confirmed">Confirm</option>
                                    <option value="rejected">Reject</option>
                                <?php elseif ($row['status'] === 'confirmed'): ?>
                                    <option value="completed">Mark as Completed</option>
                                <?php endif; ?>
                            </select>

                            <button type="submit">Update Status</button>
                        </form>
                    <?php endif; ?>

                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-bookings">No bookings yet.</p>
        <?php endif; ?>

    </div>
</body>

</html>