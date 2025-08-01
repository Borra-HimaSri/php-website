
<?php include 'db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Upload logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        $filename = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO teachers (name, image) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $filename);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Delete logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("SELECT image FROM teachers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

   if (!empty($image) && file_exists("uploads/" . $image) && !is_dir("uploads/" . $image)) {
    unlink("uploads/" . $image);
}


    $stmt = $conn->prepare("DELETE FROM teachers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch teachers
$result = $conn->query("SELECT * FROM teachers ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Team</title>
    <link rel="stylesheet" href="css/gallery.css">
    <style>
        body { font-family: Arial; margin: 30px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        img { height: 60px; }
    </style>
</head>
<body>

<h2>Add Teacher</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Teacher name" required>
    <input type="file" name="image" required>
    <button type="submit">Add</button>
</form>

<h2>All Teachers</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Photo</th>
        <th>Name</th>
        <th>Delete</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt=""></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this teacher?')">Delete</a></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
