<?php
session_start();
include './includes/db_connect.php';

// Only allow logged-in customers
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['user_id'];
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

if ($booking_id <= 0) {
    die("Invalid booking specified.");
}

// Check that this booking belongs to this customer AND is completed
$sql = "
SELECT b.*, u.name AS contractor_name, b.contractor_id
FROM bookings b
JOIN users u ON b.contractor_id = u.id
WHERE b.id = ? AND b.customer_id = ? AND b.status = 'completed'
LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $booking_id, $customer_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    die("You can only leave a review after completing this booking.");
}

// Check if there is already a review for this booking
$checkSql = "SELECT id FROM reviews WHERE booking_id = ? LIMIT 1";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("i", $booking_id);
$checkStmt->execute();
$already = $checkStmt->get_result()->fetch_assoc();

if ($already) {
    die("You have already submitted a review for this booking.");
}

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : "";

    if ($rating < 1 || $rating > 5) {
        $error = "Please select a rating between 1 and 5.";
    } else {
        $insertSql = "
        INSERT INTO reviews (booking_id, customer_id, contractor_id, rating, feedback)
        VALUES (?, ?, ?, ?, ?)
        ";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param(
            "iiiis",
            $booking_id,
            $customer_id,
            $booking['contractor_id'],
            $rating,
            $feedback

        );

        if ($insertStmt->execute()) {
            $success = "Thank you! Your review has been submitted.";
        } else {
            $error = "Error submitting review. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Leave Review for <?= htmlspecialchars($booking['contractor_name']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px auto;
            max-width: 500px;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        select,
        textarea {
            margin-bottom: 10px;
            padding: 8px;
        }

        button {
            padding: 10px;
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>

    <h2>Leave a Review for <?= htmlspecialchars($booking['contractor_name']); ?></h2>

    <?php if ($success): ?>
        <div class="message success"><?= htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
        <form method="POST" action="leave_review.php?booking_id=<?= $booking_id; ?>">
            <label>Rating (1–5):</label>
            <select name="rating" required>
                <option value="">Select Rating</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i; ?>"><?= $i; ?> ⭐</option>
                <?php endfor; ?>
            </select>

            <label>Feedback (optional):</label>
            <textarea name="feedback" rows="4"></textarea>

            <button type="submit">Submit Review</button>
        </form>
    <?php endif; ?>

</body>

</html>