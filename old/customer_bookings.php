<?php
session_start();
include './includes/db_connect.php';

// Only allow logged‑in customers
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['user_id'];

// Fetch all bookings for this customer
$sql = "SELECT b.*, u.name AS contractor_name, u.email AS contractor_email, u.phone AS contractor_phone
        FROM bookings b
        JOIN users u ON b.contractor_id = u.id
        WHERE b.customer_id = ?
        ORDER BY b.booking_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
        }

        th {
            background: #eee;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .status-pending {
            background: #ff9800;
        }

        .status-approved {
            background: #4CAF50;
        }

        .status-completed {
            background: #2196F3;
        }

        .status-rejected {
            background: #f44336;
        }

        .review-form input,
        .review-form select {
            padding: 5px;
            margin: 5px 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <h2>Ingnore leave_review.php page</h2>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?> — Your Bookings</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Contractor</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Contractor Contact</th>
                <th>Review</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
                <?php $status = strtolower($row['status']); ?>
                <tr>
                    <td><?= htmlspecialchars($row['contractor_name']); ?></td>
                    <td><?= date("d M Y, H:i", strtotime($row['booking_date'])); ?></td>
                    <td>
                        <span class="status-badge status-<?= $status; ?>">
                            <?= ucfirst($status); ?>
                        </span>
                    </td>
                    <td>
                        Email: <?= htmlspecialchars($row['contractor_email']); ?><br>
                        Phone: <?= htmlspecialchars($row['contractor_phone']); ?>
                    </td>
                    <td>
                        <?php if ($status === "completed"): ?>

                            <?php
                            // Check if this booking already has a review
                            $check_sql = "SELECT rating, comment FROM reviews WHERE booking_id = ? AND customer_id = ?";
                            $check_stmt = $conn->prepare($check_sql);

                            if ($check_stmt) {
                                $check_stmt->bind_param("ii", $row['id'], $customer_id);
                                $check_stmt->execute();
                                $check_result = $check_stmt->get_result();
                                $review = $check_result->fetch_assoc();
                            } else {
                                $review = null;
                            }
                            ?>

                            <?php if ($review): ?>
                                <strong>⭐ <?= htmlspecialchars($review['rating']); ?>/5</strong><br>
                                <?= htmlspecialchars($review['comment']); ?>
                            <?php else: ?>
                                <!-- Review form -->
                                <form method="POST" action="submit_review.php">
                                    <!-- Hidden fields to pass booking & contractor IDs -->
                                    <input type="hidden" name="booking_id" value="<?= $row['id']; ?>">
                                    <input type="hidden" name="contractor_id" value="<?= $row['contractor_id']; ?>">

                                    <!-- Rating selector -->
                                    <label>Rating:</label>
                                    <select name="rating" required>
                                        <option value="">Select Rating</option><br>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <option value="<?= $i; ?>"><?= $i; ?> ⭐</option>
                                        <?php endfor; ?>
                                    </select><br>

                                    <!-- Feedback textarea -->
                                    <label>Feedback (optional):</label><br>
                                    <textarea name="feedback" rows="4" placeholder="Write your feedback here..."></textarea><br>

                                    <!-- Submit button -->
                                    <button type="submit" name="submit_review">Submit Review</button>
                                </form>
                            <?php endif; ?>

                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No bookings yet.</p>
    <?php endif; ?>

</body>

</html>