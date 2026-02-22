<?php
session_start();
include './includes/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    die("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = intval($_POST['review_id']);

    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $review_id, $_SESSION['user_id']);
    $stmt->execute();

    header("Location: customer_bookings.php?review_deleted=1");
    exit();
}
