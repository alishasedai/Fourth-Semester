<?php
include './includes/db_connect.php';
session_start();

// Fetch latest 8 contractors for homepage
$query = "SELECT cd.*, u.name AS contractor_name 
          FROM contractor_details cd
          JOIN users u ON cd.user_id = u.id
          ORDER BY cd.created_at DESC 
          LIMIT 8";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gypsum Ceiling Services</title>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
   

    .contractor-card button a {
      color: white;
      text-decoration: none;
    }

    i .icons {
      font-size: 30px;
      color: #161717;
    }
  </style>
</head>

<body>
  <?php include('./includes/header.php'); ?>
  <div class="container">
    <!-- Hero Section -->
    <section class="hero">
      <h1>Transform your spaces with exquisite ceiling designs</h1>
      <p>Connect with verified contractors for professional gypsum and false ceiling services. Quality craftsmanship, transparent pricing, and reliable service guaranteed.</p>
      <div class="hero-buttons">
        <button><a href="services.php">Browse Services </a></button>
        <!-- <button>Find Contractors</button> -->
      </div>
    </section>

    <!-- Why Choose Section -->
    <section class="choose">
      <h2>Why Choose Our Platform?</h2>
      <p>We make finding and booking ceiling services simple, transparent, and reliable</p>
      <div class="choose-boxes">
        <div class="choose-item">
          <h4>Verified Contractors</h4>
          <i style="font-size: 24px;" class="fa-solid fa-circle-check"></i>

          <p style="margin-top: 10px;">All contractors are thoroughly vetted and verified for quality and reliability</p>

        </div>
        <div class="choose-item">
          <h4>Easy Booking</h4>
          <i style="font-size: 24px;" class="fa-solid fa-calendar-check icons"></i>
          <p style="margin-top: 10px;">Book services online with transparent pricing and flexible scheduling</p>
        </div>
        <div class="choose-item">
          <h4>24/7 Support</h4>
          <i style="font-size: 24px;" class="fa-solid fa-hourglass-half icons"></i>
          <p style="margin-top: 10px;">Get help anytime with our dedicated customer support team</p>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section class="services">
      <h2>Our Services</h2>
      <p>Professional ceiling solutions for every space and budget</p>

      <div class="service-grid">
        <?php
        $servicesQuery = "SELECT DISTINCT service_name FROM contractor_details";
        $servicesResult = mysqli_query($conn, $servicesQuery);
        if (mysqli_num_rows($servicesResult) > 0) {
          while ($serviceRow = mysqli_fetch_assoc($servicesResult)) {
            $serviceName = htmlspecialchars($serviceRow['service_name']);
        ?>
            <?php
            // For each service
            $serviceName = htmlspecialchars($serviceRow['service_name']);

            // Get one contractor's work photo for this service
            $photoQuery = "SELECT work_photos FROM contractor_details 
               WHERE service_name = '" . mysqli_real_escape_string($conn, $serviceName) . "' 
               AND work_photos != '' 
               LIMIT 1";
            $photoResult = mysqli_query($conn, $photoQuery);
            $photo = "https://via.placeholder.com/300x230"; // default placeholder

            if (mysqli_num_rows($photoResult) > 0) {
              $photoRow = mysqli_fetch_assoc($photoResult);
              $photos = explode(',', $photoRow['work_photos']);
              if (!empty(trim($photos[0]))) {
                $photo = 'uploads/' . trim($photos[0]); // first work photo
              }
            }
            ?>

            <div class="card">
              <img src="<?= $photo ?>" alt="<?= $serviceName ?>">
              <div class="card-content">
                <h4><?= $serviceName ?></h4>
                <p>High-quality service for <?= $serviceName ?> by verified professionals.</p>
                <a href="services.php?service=<?= urlencode($serviceName) ?>">
                  <button>View Contractors</button>
                </a>
              </div>
            </div>

        <?php
          }
        } else {
          echo "<p>No services available right now.</p>";
        }
        ?>
      </div>
    </section>

    <!-- Latest Contractors Section -->
    <section class="customers">
      <h2>Top Rated Contractors</h2>
      <p>Work with experienced professionals who deliver exceptional results</p>
      <div class="contractor-grid">
        <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $serviceName = htmlspecialchars($row['service_name']);
            $photo = !empty($row['profile_photo']) ? "uploads/" . $row['profile_photo'] : "https://via.placeholder.com/150";
            $experience = htmlspecialchars($row['experience']);
            $description = htmlspecialchars(substr($row['description'], 0, 80)) . "...";
            $id = $row['user_id'];

            // Calculate average rating dynamically
            // $ratingQuery = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE contractor_id = {$id}";
            // $ratingResult = mysqli_query($conn, $ratingQuery);
            // $ratingData = mysqli_fetch_assoc($ratingResult);
            // Calculate average rating dynamically (CORRECT way)
            $id = (int)$row['user_id'];

            $ratingQuery = "
  SELECT 
    COUNT(r.id) AS total_reviews,
    AVG(r.rating) AS avg_rating
  FROM reviews r
  JOIN bookings b ON r.booking_id = b.id
  WHERE b.contractor_id = $id
";

            $ratingResult = mysqli_query($conn, $ratingQuery);

            if ($ratingResult) {
              $ratingData = mysqli_fetch_assoc($ratingResult);
              $avgRating = $ratingData['avg_rating'] ? round($ratingData['avg_rating'], 1) : 0;
              $totalReviews = $ratingData['total_reviews'] ?? 0;
            } else {
              // fallback to prevent crash
              $avgRating = 0;
              $totalReviews = 0;
            }

        ?>
            <div class="contractor-card">
              <div class="image-box">
                <img src="<?= $photo ?>" alt="<?= $serviceName ?>">
              </div>

              <div class="contractor-info">
                <h3><?= $serviceName ?></h3>
                <p><?= $description ?></p>

                <div class="rating">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?= $i <= round($avgRating) ? '⭐' : '☆' ?>
                  <?php endfor; ?>
                  <span><?= $avgRating ?> (<?= $totalReviews ?> reviews)</span>
                </div>

                <p class="exp"><strong>Experience:</strong> <?= $experience ?> years</p>

                <button class="view-btn" onclick="window.location.href='contractor_profile.php?id=<?= $id ?>'">
                  View Profile
                </button>
              </div>
            </div>
        <?php
          }
        } else {
          echo "<p>No contractors available right now.</p>";
        }
        ?>
      </div>
    </section>
  </div>

  <?php include('./includes/footer.php'); ?>
</body>

</html>