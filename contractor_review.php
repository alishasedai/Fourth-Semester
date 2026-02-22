<?php
session_start();
include './includes/db_connect.php';

// Allow only logged-in contractors
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['user_role'] !== 'contractor') {
    die("Access denied.");
}

$contractor_id = $_SESSION['user_id'];

// Fetch contractor service name (optional)
$contractor_sql = "SELECT service_name FROM contractor_details WHERE user_id = ?";
$contractor_stmt = $conn->prepare($contractor_sql);
$contractor_stmt->bind_param("i", $contractor_id);
$contractor_stmt->execute();
$contractor_result = $contractor_stmt->get_result();
$contractor = $contractor_result->fetch_assoc();

// Fetch reviews for this contractor
$review_sql = "
    SELECT r.rating, r.feedback, r.created_at, u.name AS customer_name
    FROM reviews r
    JOIN users u ON r.customer_id = u.id
    WHERE r.contractor_id = ?
    ORDER BY r.id DESC
";
$review_stmt = $conn->prepare($review_sql);
$review_stmt->bind_param("i", $contractor_id);
$review_stmt->execute();
$reviews = $review_stmt->get_result();

// Calculate average rating
$avg_sql = "SELECT AVG(rating) as average_rating, COUNT(*) as total_reviews FROM reviews WHERE contractor_id = ?";
$avg_stmt = $conn->prepare($avg_sql);
$avg_stmt->bind_param("i", $contractor_id);
$avg_stmt->execute();
$avg_result = $avg_stmt->get_result()->fetch_assoc();

$average_rating = round($avg_result['average_rating'], 1);
$total_reviews = $avg_result['total_reviews'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Reviews</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 30px auto;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
        }

        h1 {
            margin-bottom: 10px;
        }

        .summary {
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 5px #ccc;
        }

        .review {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 0 5px #ccc;
        }

        .stars {
            color: #f4c150;
            font-size: 18px;
        }

        .date {
            color: gray;
            font-size: 13px;
        }

        .no-reviews {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <h1>Reviews for <?= htmlspecialchars($contractor['service_name'] ?? 'Your Services'); ?></h1>

    <div class="summary">
        <strong>Average Rating:</strong>
        <?= $total_reviews > 0 ? $average_rating . " ⭐" : "No ratings yet"; ?>
        <br>
        <strong>Total Reviews:</strong> <?= $total_reviews; ?>
    </div>

    <?php if ($reviews->num_rows > 0): ?>

        <?php while ($row = $reviews->fetch_assoc()): ?>
            <div class="review">
                <strong><?= htmlspecialchars($row['customer_name']); ?></strong>
                <div class="stars">
                    <?= str_repeat("⭐", $row['rating']); ?>
                </div>
                <?php if (!empty($row['feedback'])): ?>
                    <p><?= htmlspecialchars($row['feedback']); ?></p>
                <?php endif; ?>
                <div class="date">
                    <?= htmlspecialchars($row['created_at'] ?? ''); ?>
                </div>
            </div>
        <?php endwhile; ?>

    <?php else: ?>
        <div class="no-reviews">
            You have not received any reviews yet.
        </div>
    <?php endif; ?>

</body>

</html>