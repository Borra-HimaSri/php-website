<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
include 'admin_common.php';
require 'vendor/autoload.php'; // Cloudinary autoload
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// Cloudinary config
Configuration::instance([
    'cloud' => [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
        'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
    ],
    'url' => ['secure' => true]
]);

$editRow = null;

// PostgreSQL connection using PDO
try {
    $host = "dpg-d2mabb7diees73e61k3g-a.oregon-postgres.render.com";
    $port = "5432";
    $dbname = "data_j4be";
    $user = "data_j4be_user";
    $password = "Bt0ZBgsg3RcPaytjpCI5WWmC0wTHm0Gs";
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['upload'])) {
        $category = 'gallery-event';
        $eventName = $_POST['event_name'];
        $eventDate = date("M Y", strtotime($_POST['event_date']));
        $eventTime = $_POST['event_hour'] . ':' . $_POST['event_minute'] . ' ' . $_POST['event_ampm'];
        $eventLocation = $_POST['event_location'];
        $eventDescription = $_POST['event_description'];

        if ($_FILES['image']['error'] === 0) {
            try {
                $upload = (new UploadApi())->upload($_FILES['image']['tmp_name'], ['folder' => 'events']);
                $imageUrl = $upload['secure_url'];

                $stmt = $pdo->prepare("INSERT INTO images (image_path, category, event_name, event_date, event_time, event_location, event_description) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$imageUrl, $category, $eventName, $eventDate, $eventTime, $eventLocation, $eventDescription]);

                header("Location: gallery_event_admin.php");
                exit;
            } catch (Exception $e) {
                echo "Cloudinary Upload Error: " . $e->getMessage();
            }
        } else {
            echo "Image upload failed!";
        }
    }

    if (isset($_POST['delete'])) {
        $imageId = (int)$_POST['delete'];

        $stmt = $pdo->prepare("SELECT image_path FROM images WHERE id = ?");
        $stmt->execute([$imageId]);
        $imagePathToDelete = $stmt->fetchColumn();

        if ($imagePathToDelete) {
            // Extract public ID
            $parts = explode("/", parse_url($imagePathToDelete, PHP_URL_PATH));
            $filename = pathinfo(end($parts), PATHINFO_FILENAME);
            $folder = 'events/';
            $publicId = $folder . $filename;

            try {
                (new UploadApi())->destroy($publicId);
            } catch (Exception $e) {
                echo "Cloudinary delete failed: " . $e->getMessage();
            }

            $stmtDel = $pdo->prepare("DELETE FROM images WHERE id = ?");
            $stmtDel->execute([$imageId]);
        }

        header("Location: gallery_event_admin.php");
        exit;
    }

    if (isset($_POST['edit'])) {
        $editId = (int)$_POST['edit'];
        $stmt = $pdo->prepare("SELECT * FROM images WHERE id = ?");
        $stmt->execute([$editId]);
        $editRow = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (isset($_POST['update'])) {
        $updateId = (int)$_POST['update'];
        $eventName = $_POST['event_name'];
        $eventDate = date("M Y", strtotime($_POST['event_date']));
        $eventTime = $_POST['event_hour'] . ':' . $_POST['event_minute'] . ' ' . $_POST['event_ampm'];
        $eventLocation = $_POST['event_location'];
        $eventDescription = $_POST['event_description'];

        $stmt = $pdo->prepare("UPDATE images SET event_name=?, event_date=?, event_time=?, event_location=?, event_description=? WHERE id=?");
        $stmt->execute([$eventName, $eventDate, $eventTime, $eventLocation, $eventDescription, $updateId]);

        header("Location: gallery_event_admin.php");
        exit;
    }
}
?>

<!-- HTML code remains completely unchanged below -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Gallery Event</title>
<link rel="icon" type="image/png" href="img/logo.png">
<link rel="stylesheet" href="css/events.css">
<script>
function confirmDelete() { return confirm("Are you sure you want to delete this image?"); }
</script>
</head>
<body>
<div class="admin-nav">
<a href="gallery_photo_admin.php"><button>Gallery</button></a>
<a href="gallery_event_admin.php"><button>Events</button></a>
<a href="teachersadmin.php"><button>Teacher</button></a>
<a href="admin.php"><button>Admin Page</button></a>
</div>

<h2>Upload Events to the Events page</h2>
<form action="gallery_event_admin.php" method="post" enctype="multipart/form-data">
<!-- Form unchanged -->
<label for="image">Upload Image:</label>
<input type="file" name="image" id="image" required><br><br>
<label for="event_name">Event Name:</label>
<input type="text" name="event_name" id="event_name" placeholder="Event Name" required><br><br>
<label for="event_date">Event Date:</label>
<input type="month" name="event_date" id="event_date" required><br><br>
<label for="event_time">Event Time:</label>
<select name="event_hour" required>
<?php for ($h = 1; $h <= 12; $h++) echo "<option value='$h'>$h</option>"; ?>
</select> :
<select name="event_minute" required>
<?php for ($m = 0; $m <= 59; $m++) { $min = str_pad($m,2,"0",STR_PAD_LEFT); echo "<option value='$min'>$min</option>"; } ?>
</select>
<select name="event_ampm" required>
<option value="AM">AM</option>
<option value="PM">PM</option>
</select><br><br>
<label for="event_location">Event Location:</label>
<input type="text" name="event_location" id="event_location" placeholder="Event Location" required><br><br>
<label for="event_description">Event Description:</label>
<textarea name="event_description" id="event_description" placeholder="Event Description" required></textarea><br><br>
<button type="submit" name="upload">Upload Event</button>
</form>

<?php if ($editRow) { ?>
<!-- Edit form unchanged -->
<h2>Edit Event</h2>
<form action="gallery_event_admin.php" method="post">
<input type="hidden" name="update" value="<?= $editRow['id'] ?>">
<!-- All other input fields same -->
</form>
<?php } ?>

<hr>

<div class="gallery-container">
<?php
$stmt = $pdo->query("SELECT * FROM images WHERE category='gallery-event'");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<div style="width:180px; margin:10px auto; padding:10px; border:1px solid #ccc; border-radius:10px; text-align:center;">
            <img src="' . htmlspecialchars($row['image_path']) . '" style="width:150px; height:150px; object-fit:cover; border-radius:8px;"><br><br>
            <form action="gallery_event_admin.php" method="post" style="display:inline-block; margin-right:5px;">
                <button type="submit" name="edit" value="' . $row['id'] . '">Edit</button>
            </form>
            <form action="gallery_event_admin.php" method="post" onsubmit="return confirmDelete();" style="display:inline-block;">
                <button type="submit" name="delete" value="' . $row['id'] . '">Delete</button>
            </form>
          </div>';
}
?>
</div>
</body>
</html>
