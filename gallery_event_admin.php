<?php
// Include common admin operations like session check, DB connection, and logout handling
include 'admin_common.php';

// Handle gallery-event upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $imagePath = 'uploads/' . uniqid('img_', true) . '-' . basename($_FILES['image']['name']);
    $category = 'gallery-event';
    $eventName = $_POST['event_name'];  
    $eventDate = date("M Y", strtotime($_POST['event_date']));

    $eventTime = $_POST['event_hour'] . ':' . $_POST['event_minute'] . ' ' . $_POST['event_ampm'];

    $eventLocation = $_POST['event_location'];
    $eventDescription = $_POST['event_description'];

    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        $sql = "INSERT INTO images (image_path, category, event_name, event_date, event_time, event_location, event_description)
                VALUES ('$imagePath', '$category', '$eventName', '$eventDate', '$eventTime', '$eventLocation', '$eventDescription')";
        if ($conn->query($sql)) {
            header("Location: gallery_event_admin.php");
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

// Handle event editing
if (isset($_POST['edit'])) {
    $editId = $_POST['edit'];
    $result = $conn->query("SELECT * FROM images WHERE id = $editId");
    $editRow = $result->fetch_assoc();
}

// Handle event update
if (isset($_POST['update'])) {
    $updateId = $_POST['update'];
    $eventName = $_POST['event_name'];
   $eventDate = date("M Y", strtotime($_POST['event_date']));
    $eventTime = $_POST['event_hour'] . ':' . $_POST['event_minute'] . ' ' . $_POST['event_ampm'];

    $eventLocation = $_POST['event_location'];
    $eventDescription = $_POST['event_description'];

    $sql = "UPDATE images SET 
                event_name = '$eventName', 
                event_date = '$eventDate', 
                event_time = '$eventTime', 
                event_location = '$eventLocation', 
                event_description = '$eventDescription' 
            WHERE id = $updateId";
    if ($conn->query($sql)) {
        header("Location: gallery_event_admin.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Gallery Event</title>
    <link rel="stylesheet" href="css/events.css">
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

    <h2>Upload Events to the Events page</h2>
    <form action="gallery_event_admin.php" method="post" enctype="multipart/form-data">
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
  <?php for ($m = 0; $m <= 59; $m++) {
      $min = str_pad($m, 2, "0", STR_PAD_LEFT);
      echo "<option value='$min'>$min</option>";
  } ?>
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
    
    <?php if (isset($editRow)) { ?>
        <h2>Edit Event</h2>
        <form action="gallery_event_admin.php" method="post">
            <input type="hidden" name="update" value="<?= $editRow['id'] ?>">
            
            <label for="event_name">Event Name:</label>
            <input type="text" name="event_name" id="event_name" value="<?= $editRow['event_name'] ?>" required><br><br>
            
            <label for="event_date">Event Date:</label>
            <?php $eventDateValue = date("Y-m", strtotime($editRow['event_date'])); ?>
<input type="month" name="event_date" id="event_date" value="<?= $eventDateValue ?>" required>
<br><br>
            
            <label for="event_time">Event Time:</label>
<select name="event_hour" required>
  <?php for ($h = 1; $h <= 12; $h++) echo "<option value='$h'>$h</option>"; ?>
</select> :

<select name="event_minute" required>
  <?php for ($m = 0; $m <= 59; $m++) {
      $min = str_pad($m, 2, "0", STR_PAD_LEFT);
      echo "<option value='$min'>$min</option>";
  } ?>
</select>

<select name="event_ampm" required>
  <option value="AM">AM</option>
  <option value="PM">PM</option>
</select><br><br>

            
            <label for="event_location">Event Location:</label>
            <input type="text" name="event_location" id="event_location" value="<?= $editRow['event_location'] ?>" required><br><br>
            
            <label for="event_description">Event Description:</label>
            <textarea name="event_description" id="event_description" required><?= $editRow['event_description'] ?></textarea><br><br>
            
            <button type="submit">Update Event</button>
        </form>
    <?php } ?>
    
   
    <div class="gallery-container">
        <?php
        $result = $conn->query("SELECT * FROM images WHERE category='gallery-event'");
        while ($row = $result->fetch_assoc()) {
            echo '<div>
                    <img src="' . $row['image_path'] . '" style="width: 150px; height: 150px; object-fit: cover;">
                    <form action="gallery_event_admin.php" method="post">
                        <button type="submit" name="edit" value="' . $row['id'] . '">Edit</button>
                    </form>
                    <form action="gallery_event_admin.php" method="post" onsubmit="return confirmDelete();">
                        <button type="submit" name="delete" value="' . $row['id'] . '">Delete</button>
                    </form>
                  </div>';
        }
        ?>
    </div>
</body>
</html>