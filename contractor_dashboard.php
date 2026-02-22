<?php
session_start();
include './includes/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'contractor') {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM contractor_details WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$contractor = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Contractor Dashboard</title>

  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f9;
    }

    /* NAVBAR */
    .navbar {
      background: #161717;
       padding: 15px 50px;
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
      transition: 0.3s;
    }

    .navbar a:hover {
      opacity: 0.8;
    }

    /* MAIN CONTAINER */
    .container {
      max-width: 1000px;
      margin: 50px auto;
      padding: 0 20px;
    }

    /* PROFILE CARD */
    .profile-card {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      text-align: center;
      margin-bottom: 30px;
      transition: 0.3s;
    }

    .profile-card:hover {
      transform: translateY(-5px);
    }

    .profile-card img {
      width: 130px;
      height: 130px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #2a5298;
      margin-bottom: 15px;
    }

    .profile-card h2 {
      margin: 10px 0;
      color: #333;
    }

    /* DETAILS CARD */
    .details-card {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
      margin-bottom: 25px;
    }

    .details-card p {
      margin: 8px 0;
      color: #555;
    }

    /* SERVICES TAGS */
    .services-card {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
      margin-bottom: 25px;
    }

    .services-card h3 {
      margin-bottom: 15px;
    }

    .service-tag {
      display: inline-block;
      background: #e3f2fd;
      color: #0d47a1;
      padding: 8px 14px;
      border-radius: 20px;
      margin: 5px;
      font-size: 14px;
    }

    /* WORK GALLERY */
    .work-card {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
    }

    .gallery {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 15px;
    }

    .gallery img {
      width: 220px;
      height: 160px;
      object-fit: cover;
      border-radius: 10px;
      transition: 0.3s;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .gallery img:hover {
      transform: scale(1.05);
    }

    /* BUTTON */
    .btn {
      /* background: #161717; */
      color: white;
      padding: 8px 16px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
      transition: 0.3s;
    }

    .btn:hover {
      opacity: 0.85;
    }

    .no-data {
      text-align: center;
      background: white;
      padding: 50px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
    }
  </style>
</head>

<body>

  <?php
  // Check if contractor already added details
  $check_sql = "SELECT id FROM contractor_details WHERE user_id = '$user_id'";
  $check_result = mysqli_query($conn, $check_sql);
  $has_service = mysqli_num_rows($check_result) > 0;
  ?>

  <div class="navbar">
    <div><strong>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?></strong></div>
    <div>

      <?php if (!$has_service): ?>
        <!-- Show only if service NOT added -->
        <a href="contractor_add_service.php">Your Services</a>
      <?php endif; ?>

      <?php if ($has_service): ?>
        <!-- Show only if service ALREADY added -->
        <a href="edit_service.php" class="btn">Edit Details</a>
      <?php endif; ?>

      <a href="contractor_review.php">Reviews</a>
      <a href="contractor_bookings.php">My Bookings</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>
  <div class="container">

    <?php if ($contractor): ?>

      <div class="profile-card">
        <img src="uploads/<?= htmlspecialchars($contractor['profile_photo']); ?>" alt="Profile Photo">
        <h2><?= htmlspecialchars($contractor['service_name']); ?></h2>
      </div>

      <div class="details-card">
        <h3>Professional Details</h3>
        <p><strong>Experience:</strong> <?= htmlspecialchars($contractor['experience']); ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($contractor['phone']); ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($contractor['address']); ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($contractor['description']); ?></p>
      </div>

      <div class="services-card">
        <h3>Services Offered</h3>
        <?php
        $services = explode(',', $contractor['services']);
        foreach ($services as $service) {
          echo '<span class="service-tag">' . htmlspecialchars(trim($service)) . '</span>';
        }
        ?>
      </div>

      <div class="work-card">
        <h3>Sample Work</h3>
        <div class="gallery">
          <?php
          if (!empty($contractor['work_photos'])) {
            $photos = explode(',', $contractor['work_photos']);
            foreach ($photos as $photo) {
              $photo = trim($photo);
              if ($photo !== '') {
                $photoUrl = 'uploads/' . rawurlencode(basename($photo));
                echo '<img src="' . htmlspecialchars($photoUrl) . '" alt="Work Photo">';
              }
            }
          } else {
            echo '<p style="color:#777;">No work photos available yet.</p>';
          }
          ?>
        </div>
      </div>

    <?php else: ?>

      <div class="no-data">
        <h3>You haven’t added your service details yet.</h3>
        <br>
        <a href="contractor_add_service.php" class="btn">Add Your Service</a>
      </div>

    <?php endif; ?>

  </div>

</body>

</html>