<?php
session_start();
include './includes/db_connect.php';

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// Restrict access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    die("Unauthorized");
}

$message = "";
if (isset($_GET['review_submitted'])) $message = "Review submitted successfully!";
if (isset($_GET['review_updated'])) $message = "Review updated successfully!";
if (isset($_GET['review_deleted'])) $message = "Review deleted successfully!";

$customer_id = $_SESSION['user_id'];

$sql = "
SELECT b.*, u.name AS contractor_name, u.email AS contractor_email, u.phone AS contractor_phone
FROM bookings b
JOIN users u ON b.contractor_id = u.id
WHERE b.customer_id = ?
ORDER BY b.created_at DESC
";

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
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .booking-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: 0.3s ease;
        }

        .booking-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .status.completed {
            background: #d4edda;
            color: #155724;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .info p {
            margin: 6px 0;
            font-size: 14px;
            color: #555;
        }

        .message.success {
            padding: 12px;
            background: #d4edda;
            color: #155724;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .review-section {
            margin-top: 15px;
        }

        .review-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-top: 10px;
        }

        .review-card h4 {
            margin-bottom: 15px;
        }

        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-start;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 28px;
            color: #ddd;
            cursor: pointer;
            transition: 0.3s;
        }

        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
        }

        .star-rating input:checked~label {
            color: #ffc107;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            resize: vertical;
            margin-top: 6px;
            margin-bottom: 15px;
        }

        textarea:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
        }

        button {
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        button:hover {
            opacity: 0.9;
        }

        .stars-display {
            font-size: 20px;
            color: #ffc107;
            margin-bottom: 10px;
        }

        .no-bookings {
            text-align: center;
            color: #666;
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <div class="container">

        <h2>My Bookings</h2>

        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="booking-card">

                    <div class="booking-header">
                        <h3><?= htmlspecialchars($row['service_name']); ?></h3>
                        <span class="status <?= strtolower($row['status']); ?>">
                            <?= ucfirst($row['status']); ?>
                        </span>
                    </div>

                    <div class="info">
                        <p><strong>Date:</strong> <?= htmlspecialchars($row['booking_date']); ?></p>
                        <p><strong>Contractor:</strong> <?= htmlspecialchars($row['contractor_name']); ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($row['contractor_email']); ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($row['contractor_phone']); ?></p>
                        <p><strong>Notes:</strong> <?= htmlspecialchars($row['description']); ?></p>
                    </div>

                    <?php if ($row['status'] === 'completed'): ?>
                        <?php
                        $rev_stmt = $conn->prepare("
                        SELECT id, rating, feedback 
                        FROM reviews 
                        WHERE booking_id = ? AND customer_id = ?
                        LIMIT 1
                    ");
                        $rev_stmt->bind_param("ii", $row['id'], $customer_id);
                        $rev_stmt->execute();
                        $review = $rev_stmt->get_result()->fetch_assoc();
                        ?>

                        <div class="review-section">

                            <?php if (!$review): ?>
                                <!-- Submit Review Form -->
                                <div class="review-card">
                                    <h4>Leave Your Review</h4>
                                    <form method="POST" action="submit_review.php">
                                        <input type="hidden" name="booking_id" value="<?= $row['id']; ?>">
                                        <input type="hidden" name="contractor_id" value="<?= $row['contractor_id']; ?>">

                                        <label>Rate Your Experience:</label>
                                        <div class="star-rating">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="rating" value="<?= $i; ?>" id="star<?= $row['id'] . '_' . $i; ?>" required>
                                                <label for="star<?= $row['id'] . '_' . $i; ?>">★</label>
                                            <?php endfor; ?>
                                        </div>

                                        <label>Your Feedback:</label>
                                        <textarea name="feedback" rows="4" placeholder="Share your experience..."></textarea>

                                        <button type="submit" name="submit_review" class="btn-primary">Submit Review</button>
                                    </form>
                                </div>

                            <?php else: ?>
                                <!-- Edit/Delete Review -->
                                <div class="review-card">
                                    <h4>Your Review</h4>
                                    <div class="stars-display">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $review['rating'] ? "★" : "☆";
                                        }
                                        ?>
                                    </div>
                                    <p><?= htmlspecialchars($review['feedback']); ?></p>

                                    <button class="btn-secondary"
                                        onclick="document.getElementById('edit<?= $row['id']; ?>').style.display='block'">Edit</button>

                                    <form method="POST" action="delete_review.php" style="display:inline;">
                                        <input type="hidden" name="review_id" value="<?= $review['id']; ?>">
                                        <button type="submit" class="btn-danger"
                                            onclick="return confirm('Delete this review?')">Delete</button>
                                    </form>

                                    <div id="edit<?= $row['id']; ?>" style="display:none; margin-top:15px;">
                                        <form method="POST" action="edit_review.php">
                                            <input type="hidden" name="review_id" value="<?= $review['id']; ?>">

                                            <label>Update Rating:</label>
                                            <div class="star-rating">
                                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                                    <input type="radio" name="rating" value="<?= $i; ?>"
                                                        id="editstar<?= $row['id'] . '_' . $i; ?>"
                                                        <?= $review['rating'] == $i ? 'checked' : ''; ?> required>
                                                    <label for="editstar<?= $row['id'] . '_' . $i; ?>">★</label>
                                                <?php endfor; ?>
                                            </div>

                                            <label>Update Feedback:</label>
                                            <textarea name="feedback" rows="4"><?= htmlspecialchars($review['feedback']); ?></textarea>

                                            <button type="submit" class="btn-primary">Update Review</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="review-card">Review available after completion.</div>
                    <?php endif; ?>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-bookings">No bookings yet.</p>
        <?php endif; ?>
    </div>

    <!-- 🔥 Fix for Back Button -->
    <script>
        window.addEventListener("load", function() {
            // Remove query params from URL without reloading
            if (window.location.search.includes("review_")) {
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        // Force reload if page is loaded from cache (Back button fix)
        window.addEventListener("pageshow", function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>