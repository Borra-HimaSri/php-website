<?php
// Include common admin operations like session check, DB connection, and logout handling
include 'admin_common.php';

// Handle gallery-latest upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $imagePath = 'uploads/' . uniqid('img_', true) . '-' . basename($_FILES['image']['name']);
    $category = 'gallery-latest';
    $newsName = $_POST['news_name'];
    $newsDescription = $_POST['news_description'];
    $newsDate = $_POST['news_date'];

    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        $sql = "INSERT INTO images (image_path, category, news_name, news_description, news_date)
                VALUES ('$imagePath', '$category', '$newsName', '$newsDescription', '$newsDate')";
        if ($conn->query($sql)) {
            header("Location: gallery_latest_admin.php");
            exit;
        }
    } else {
        echo "Image upload failed!";
    }
}

// Handle image deletion
if (isset($_POST['delete'])) {
    $imageId = $_POST['delete'];
    $result = $conn->query("SELECT image_path FROM images WHERE id = $imageId");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePathToDelete = $row['image_path'];
        $conn->query("DELETE FROM images WHERE id = $imageId");
        if (file_exists($imagePathToDelete)) {
            unlink($imagePathToDelete);
        }
    }
}

// Handle editing
if (isset($_POST['edit'])) {
    $editId = $_POST['edit'];
    $result = $conn->query("SELECT * FROM images WHERE id = $editId");
    $editRow = $result->fetch_assoc();
}

// Handle updates
if (isset($_POST['update'])) {
    $updateId = $_POST['update'];
    $newsName = $_POST['news_name'];
    $newsDescription = $_POST['news_description'];
    $newsDate = $_POST['news_date'];

    $sql = "UPDATE images SET 
                news_name = '$newsName', 
                news_description = '$newsDescription', 
                news_date = '$newsDate' 
            WHERE id = $updateId";
    if ($conn->query($sql)) {
        header("Location: gallery_latest_admin.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Gallery Latest</title>
    <link rel="stylesheet" href="css/news.css">
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this image?");
        }
    </script>
</head>
<body>
<div class="admin-nav">
<a href="gallery_photo_admin.php"><button>Gallery</button></a>
    <a href="gallery_event_admin.php"><button> Events</button></a>
    <a href="gallery_latest_admin.php"><button>News</button></a>
    <a href="admin.php"><button>Admin Page</button></a>
</div>



    <h2>Upload to Gallery Latest</h2>
    <form action="gallery_latest_admin.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <input type="text" name="news_name" placeholder="News Title" required>
        <textarea name="news_description" placeholder="News Description" required></textarea>
        <input type="date" name="news_date" required>
        <button type="submit" name="upload">Upload Image</button>
    </form>

    <?php if (isset($editRow)) { ?>
        <h2>Edit News</h2>
        <form action="gallery_latest_admin.php" method="post">
            <input type="hidden" name="update" value="<?= $editRow['id'] ?>">
            <input type="text" name="news_name" value="<?= $editRow['news_name'] ?>" required>
            <textarea name="news_description" required><?= $editRow['news_description'] ?></textarea>
            <input type="date" name="news_date" value="<?= $editRow['news_date'] ?>" required>
            <button type="submit">Update News</button>
        </form>
    <?php } ?>


    <div class="gallery-container">
        <?php
        $result = $conn->query("SELECT * FROM images WHERE category='gallery-latest'");
        while ($row = $result->fetch_assoc()) {
            echo '<div>
                    <img src="' . $row['image_path'] . '" style="width: 150px; height: 150px; object-fit: cover;">
                    <form action="gallery_latest_admin.php" method="post">
                        <button type="submit" name="edit" value="' . $row['id'] . '">Edit</button>
                    </form>
                    <form action="gallery_latest_admin.php" method="post" onsubmit="return confirmDelete();">
                        <button type="submit" name="delete" value="' . $row['id'] . '">Delete</button>
                    </form>
                  </div>';
        }
        ?>
    </div>
</body>
</html>
