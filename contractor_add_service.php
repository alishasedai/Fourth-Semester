<?php
session_start();
include './includes/db_connect.php';

// Restrict access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'contractor') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ---------------------------------------------------
   ✅ CHECK IF CONTRACTOR ALREADY ADDED SERVICE
---------------------------------------------------- */
$check_sql = "SELECT id FROM contractor_details WHERE user_id = '$user_id'";
$check_result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    // Already added → redirect to edit page
    header("Location: edit_service.php");
    exit();
}

/* ---------------------------------------------------
   ✅ HANDLE FORM SUBMISSION
---------------------------------------------------- */
if (isset($_POST['submit'])) {

    $service_name = $_POST['service_name'];
    $experience = $_POST['experience'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $description = $_POST['description'];
    $services = $_POST['services'];

    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Upload profile photo
    $profile_photo = "";
    if (!empty($_FILES['profile_photo']['name'])) {
        $profile_name = time() . "_" . basename($_FILES['profile_photo']['name']);
        $target_profile = $target_dir . $profile_name;
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_profile);
        $profile_photo = $profile_name;
    }

    // Upload multiple work photos
    $uploaded_work_photos = [];
    if (!empty($_FILES['work_photos']['name'][0])) {
        foreach ($_FILES['work_photos']['tmp_name'] as $key => $tmp_name) {
            $file_name = time() . "_" . basename($_FILES['work_photos']['name'][$key]);
            $target_file = $target_dir . $file_name;
            if (move_uploaded_file($tmp_name, $target_file)) {
                $uploaded_work_photos[] = $file_name;
            }
        }
    }

    $work_photos_str = implode(",", $uploaded_work_photos);

    $sql = "INSERT INTO contractor_details 
        (user_id, service_name, experience, phone, address, description, services, profile_photo, work_photos)
        VALUES 
        ('$user_id', '$service_name', '$experience', '$phone', '$address', '$description', '$services', '$profile_photo', '$work_photos_str')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('Service details added successfully!');
            window.location='contractor_dashboard.php';
        </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Add Service</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
        }

        .navbar {
            background:#000000;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            color: white;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        .container {
            max-width: 800px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 18px;
        }

        input:focus,
        textarea:focus {
            border-color: #2a5298;
            outline: none;
        }

        .btn {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            width: 100%;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div><strong><?= htmlspecialchars($_SESSION['user_name']); ?></strong></div>
        <div>
            <a href="contractor_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Add Your Service Details</h2>

        <form method="POST" enctype="multipart/form-data">

            <label>Service Name</label>
            <input type="text" name="service_name" required>

            <label>Experience</label>
            <input type="text" name="experience" required>

            <label>Phone</label>
            <input type="text" name="phone" required>

            <label>Address</label>
            <input type="text" name="address" required>

            <label>Description</label>
            <textarea name="description" required></textarea>

            <label>Services Offered (comma separated)</label>
            <textarea name="services" required></textarea>

            <label>Profile Photo</label>
            <input type="file" name="profile_photo" accept="image/*" required>

            <label>Work Photos</label>
            <input type="file" name="work_photos[]" multiple accept="image/*" required>

            <button type="submit" name="submit" class="btn">Save Details</button>

        </form>
    </div>

</body>

</html>