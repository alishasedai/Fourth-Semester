<?php
include './includes/db_connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - Gypsum Services</title>
  <link rel="stylesheet" href="./css/style.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: #f9fafb;
      color: #222;
    }

    .about-hero {
      background: linear-gradient(135deg, #111, #333);
      color: white;
      text-align: center;
      padding: 80px 20px;
    }

    .about-hero h1 {
      font-size: 36px;
      margin-bottom: 15px;
    }

    .about-hero p {
      font-size: 18px;
      opacity: 0.9;
      max-width: 700px;
      margin: auto;
    }

    .about-container {
      max-width: 1100px;
      margin: 60px auto;
      padding: 0 20px;
    }

    .section {
      margin-bottom: 70px;
    }

    .section h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 26px;
      position: relative;
    }

    .section h2::after {
      content: '';
      width: 60px;
      height: 3px;
      background: #000;
      display: block;
      margin: 10px auto 0;
      border-radius: 3px;
    }

    .section p {
      text-align: center;
      max-width: 800px;
      margin: 0 auto;
      color: #555;
      font-size: 16px;
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
      margin-top: 40px;
    }

    .card {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
      transition: 0.3s ease;
      text-align: center;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
    }

    .card h3 {
      margin-bottom: 15px;
      font-size: 18px;
    }

    .card p {
      font-size: 14px;
      color: #666;
    }

    .features-list {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }

    .feature-item {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      border: 1px solid #eee;
      transition: 0.3s;
    }

    .feature-item:hover {
      background: #000;
      color: #fff;
    }

    .feature-item:hover p {
      color: #ddd;
    }

    .feature-item h4 {
      margin-bottom: 10px;
    }
  </style>
</head>

<body>

  <?php include('./includes/header.php'); ?>
<div class="container">
  <!-- Hero Section -->
  <section class="about-hero">
    <h1>Building Better Ceilings, Building Better Connections</h1>
    <p>
      Gypsum Services connects customers with trusted and verified ceiling contractors
      to deliver modern, reliable, and high-quality gypsum solutions.
    </p>
  </section>

  <div class="about-container">

    <!-- Mission Section -->
    <div class="section">
      <h2>Our Mission</h2>
      <p>
        Our mission is to simplify the process of finding skilled gypsum contractors
        by providing a secure and transparent platform where quality meets trust.
      </p>
    </div>

    <!-- Services Cards -->
    <div class="section">
      <h2>What We Offer</h2>

      <div class="card-grid">
        <div class="card">
          <h3>Gypsum False Ceiling</h3>
          <p>Modern and stylish ceiling designs for homes and offices.</p>
        </div>

        <div class="card">
          <h3>POP Ceiling Designs</h3>
          <p>Creative plaster designs that enhance interior aesthetics.</p>
        </div>

        <div class="card">
          <h3>Ceiling Repair</h3>
          <p>Fix cracks, damages, and structural ceiling issues efficiently.</p>
        </div>

        <div class="card">
          <h3>Partition Work</h3>
          <p>Professional gypsum partition solutions for offices and homes.</p>
        </div>

        <div class="card">
          <h3>Cove Lighting</h3>
          <p>Elegant cove lighting solutions for a modern and luxurious look.</p>
        </div>

        <div class="card">
          <h3>Soundproof Ceiling</h3>
          <p>Acoustic ceiling solutions for noise reduction and comfort.</p>
        </div>

      </div>
    </div>

    <!-- Why Choose Us -->
    <div class="section">
      <h2>Why Choose Us</h2>

      <div class="features-list">
        <div class="feature-item">
          <h4>Verified Contractors</h4>
          <p>Only trusted professionals with verified profiles.</p>
        </div>

        <div class="feature-item">
          <h4>Transparent Reviews</h4>
          <p>Honest ratings from real customers.</p>
        </div>

        <div class="feature-item">
          <h4>Easy Booking</h4>
          <p>Simple and fast contractor booking process.</p>
        </div>

        <div class="feature-item">
          <h4>Secure Platform</h4>
          <p>Your data and transactions are safe with us.</p>
        </div>
      </div>
    </div>

    <!-- Vision -->
    <div class="section">
      <h2>Our Vision</h2>
      <p>
        To become the most trusted digital platform for gypsum and ceiling services,
        empowering both customers and contractors through innovation and reliability.
      </p>
    </div>

  </div>
  </div>
  <?php include('./includes/footer.php'); ?>

</body>

</html>