<?php
include './includes/db_connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Browse Our Services - Gypsum Portals</title>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background-color: #fff;
      color: #222;
    }

    .hero {
      text-align: center;
      padding: 50px 20px 10px;
    }

    .hero h1 {
      font-size: 26px;
      font-weight: 600;
    }

    .hero p {
      color: #000;
      font-size: 15px;
      margin-top: 5px;
    }

    .search {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: -40px;
      text-align: center;
      font-family: 'Poppins', sans-serif;
      width: 100%;
    }

    .search label {
      font-size: 18px;
      color: #333;
      font-weight: 500;
      margin-bottom: 12px;
      display: block;
    }


    .search-container {
      display: flex;
      width: 100%;
      max-width: 600px;
      background: linear-gradient(145deg, #ffffff, #f0f0f0);
      border-radius: 50px;
      border: 1px solid #1a1818;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      transition: 0.3s ease;
      margin-top: 10px;
    }

    .search-container:hover {
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }

    .search-container input {
      flex: 1;
      border: none;
      padding: 18px 25px;
      font-size: 18px;
      border-radius: 50px 0 0 50px;
      outline: none;
      background: transparent;
      transition: 0.3s ease;
    }

    .search-container input::placeholder {
      color: #aaa;
    }

    .search-container input:focus {
      background-color: #fff;
      box-shadow: inset 0 0 0 2px #272828;
    }

    .search-container button {
      background-color: #272828;
      border: none;
      padding: 0 30px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: 0.3s ease;
      border-radius: 0 50px 50px 0;
    }

    .search-container button i {
      color: #fff;
      font-size: 22px;
    }

    .search-container button:hover {
      background-color: #17a09a;
    }

    @media (max-width: 768px) {
      .search-container {
        width: 95%;
      }
    }

    .contractor-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 40px;
      justify-content: center;
      padding: 40px 60px;
    }

    .contractor-card {
      background: #fff;
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
      transition: 0.3s;
    }

    .contractor-card:hover {
      transform: translateY(-5px);
    }

    .contractor-card img.profile {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 10px;
      border: 2px solid #eee;
    }

    .names {
      display: flex;
      gap: 30px;
      justify-content: center;
      margin-bottom: 20px;
    }

    .contractor-card h3 {
      font-size: 17px;
      font-weight: 600;
      margin: 5px 0;
    }

    .rating {
      color: #ffb400;
      font-size: 15px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 4px;
      margin-bottom: 5px;
    }

    .rating span {
      color: #666;
      font-size: 13px;
    }

    .contractor-card p {
      color: #555;
      font-size: 14px;
      margin-bottom: 15px;
      line-height: 1.4;
    }

    .project-images {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 15px;
      flex-wrap: wrap;
    }

    .project-images img {
      width: 150px;
      height: 120px;
      border-radius: 6px;
      object-fit: cover;
      border: 1px solid #ddd;
    }

    .card-buttons {
      display: flex;
      justify-content: space-between;
    }

    .card-buttons button {
      border: none;
      border-radius: 6px;
      padding: 8px 14px;
      font-size: 14px;
      cursor: pointer;
      transition: 0.2s ease;
    }

    .card-buttons button a {
      color: white;
      text-decoration: none;
    }

    .card-buttons .view {
      background: #000;
      color: #fff;
    }

    .card-buttons .contact {
      background: #f5f5f5;
      color: #333;
    }

    .card-buttons .view:hover {
      background: #333;
    }

    .card-buttons .contact:hover {
      background: #e8e8e8;
    }

    @media (max-width: 768px) {
      .search-container {
        width: 95%;
      }
    }
  </style>
</head>

<body>
  <?php include('./includes/header.php'); ?>
  <div class="container">
    <section class="hero">
      <h1>Find Expert Contractors</h1>
      <p>Connect with verified ceiling specialists in your area</p>
    </section>

    <section class="search">
      <form action="" method="GET" style="width: 100%; display: flex; justify-content: center;">
        <!-- <h4 style="text-align: center;" class="search_service">Search your desired services</h4> -->
        <div class="search-container">
          <input
            type="text"
            name="search_service"
            id="search_service"
            value="<?php if (isset($_GET['search_service'])) {
                      echo $_GET['search_service'];
                    } ?>"
            placeholder="Search services...">
          <button type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </div>
      </form>
    </section>

    <section class="contractor-grid">
      <?php
      $sql = "SELECT cd.*, u.name AS contractor_name 
        FROM contractor_details cd
        JOIN users u ON cd.user_id = u.id";

      if (isset($_GET['search_service']) && !empty($_GET['search_service'])) {
        $filter = mysqli_real_escape_string($conn, $_GET['search_service']);
        $sql .= " WHERE CONCAT(cd.services,' ',cd.description,' ',cd.service_name) LIKE '%$filter%'";
      }

      $sql .= " ORDER BY cd.created_at DESC";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $show_buttons = true;
          include './includes/contractor_card.php';
        }
      } else {
        echo '<p style="text-align:center; color:#777;">No contractors found.</p>';
      }
      ?>
    </section>
</div>
    <?php include('./includes/footer.php'); ?>
</body>

</html>