<?php
session_start();
include './includes/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'contractor') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM contractor_details WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$contractor = mysqli_fetch_assoc($result);

if (!$contractor) {
    echo "<script>alert('No record found! Please add your details first.'); window.location='add_service.php';</script>";
    exit();
}

if (isset($_POST['update'])) {

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

    if (!empty($_FILES['profile_photo']['name'])) {
        $profile_photo = basename($_FILES['profile_photo']['name']);
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_dir . $profile_photo);
    } else {
        $profile_photo = $contractor['profile_photo'];
    }

    $uploaded_work_photos = [];

    if (!empty($_FILES['work_photos']['name'][0])) {
        foreach ($_FILES['work_photos']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES['work_photos']['name'][$key]);
            if (move_uploaded_file($tmp_name, $target_dir . $file_name)) {
                $uploaded_work_photos[] = $file_name;
            }
        }
        $all_photos = array_merge(explode(',', $contractor['work_photos']), $uploaded_work_photos);
        $work_photos_str = implode(',', $all_photos);
    } else {
        $work_photos_str = $contractor['work_photos'];
    }

    $update_sql = "UPDATE contractor_details SET 
        service_name='$service_name',
        experience='$experience',
        phone='$phone',
        address='$address',
        description='$description',
        services='$services',
        profile_photo='$profile_photo',
        work_photos='$work_photos_str'
        WHERE user_id='$user_id'";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Your details updated successfully!'); window.location='contractor_dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Service</title>

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
        }

        /* FORM CONTAINER */
        .container {
            max-width: 850px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        /* FORM ELEMENTS */
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 18px;
            transition: 0.3s;
            font-size: 14px;
        }

        input:focus,
        textarea:focus {
            border-color: #2a5298;
            box-shadow: 0 0 6px rgba(42, 82, 152, 0.2);
            outline: none;
        }

        textarea {
            resize: vertical;
        }

        /* BUTTON */
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

        /* IMAGE PREVIEW */
        .preview-images {
            margin-top: 10px;
        }

        .photo-box {
            display: inline-block;
            position: relative;
            margin: 8px;
        }

        .photo-box img {
            width: 120px;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .delete-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            cursor: pointer;
            font-size: 12px;
        }

        .delete-btn:hover {
            background: darkred;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div><strong><?= htmlspecialchars($_SESSION['user_name']); ?></strong></div>
        <div>
            <a href="contractor_dashboard.php">Dashboard</a>
            <!-- <a href="logout.php">Logout</a> -->
        </div>
    </div>

    <div class="container">
        <h2>Edit Your Service Details</h2>

        <form method="POST" enctype="multipart/form-data">

            <label>Service Name</label>
            <input type="text" name="service_name" value="<?= htmlspecialchars($contractor['service_name']); ?>" required>

            <label>Experience</label>
            <input type="text" name="experience" value="<?= htmlspecialchars($contractor['experience']); ?>" required>

            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($contractor['phone']); ?>" required>

            <label>Address</label>
            <input type="text" name="address" value="<?= htmlspecialchars($contractor['address']); ?>" required>

            <label>Description</label>
            <textarea name="description" rows="3" required><?= htmlspecialchars($contractor['description']); ?></textarea>

            <label>Services Offered (comma separated)</label>
            <textarea name="services" rows="2" required><?= htmlspecialchars($contractor['services']); ?></textarea>

            <label>Profile Photo</label>
            <input type="file" name="profile_photo" accept="image/*">
            <div class="preview-images">
                <img src="uploads/<?= htmlspecialchars($contractor['profile_photo']); ?>" width="120">
            </div>

            <label>Add More Work Photos</label>
            <input type="file" name="work_photos[]" multiple accept="image/*">

            <div class="preview-images">
                <?php
                $existing_photos = explode(',', $contractor['work_photos']);
                foreach ($existing_photos as $photo) {
                    if (!empty($photo)) {
                        echo '<div class="photo-box">
                <img src="uploads/' . htmlspecialchars(trim($photo)) . '">
                <button type="button" class="delete-btn" data-photo="' . htmlspecialchars(trim($photo)) . '">×</button>
              </div>';
                    }
                }
                ?>
            </div>

            <button type="submit" name="update" class="btn">Save Changes</button>

        </form>
    </div>

    <script>
        document.querySelectorAll(".delete-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                const photoName = this.dataset.photo;
                if (confirm("Delete this photo?")) {
                    fetch("delete_photo.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: "photo=" + encodeURIComponent(photoName)
                        })
                        .then(res => res.text())
                        .then(data => {
                            if (data.includes("Deleted")) {
                                this.parentElement.remove();
                            } else {
                                alert("Error deleting photo");
                            }
                        });
                }
            });
        });
    </script>

</body>

</html>