<?php
session_start();
include './includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = $_POST['review_id'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];

    $stmt = $conn->prepare("UPDATE reviews SET rating=?, feedback=? WHERE id=?");
    $stmt->bind_param("isi", $rating, $feedback, $review_id);
    $stmt->execute();

    // Redirect after POST (PRG)
    header("Location: customer_bookings.php?review_updated=1");
    exit();
}
