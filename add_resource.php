<?php
include_once "config/database.php";
include_once "includes/header.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 3)) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $title = $_POST['title'];
    $description = $_POST['description'];
    $type_id = $_POST['type_id'];
    $url = $_POST['url'];
    $file_path = null;

    // Upload file if type is Downloadable
    if ($type_id == 4 && isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = "uploads/resources/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_path = basename($target_file);
        }
    }

    $query = "INSERT INTO resources (title, description, type_id, url, file_path, user_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$title, $description, $type_id, $url, $file_path, $_SESSION['user_id']]);

    header("Location: resource_hub.php");
    exit();
}
?>

<div class="form-container">
    <h2>Add New Resource</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Type</label>
            <select name="type_id" class="form-control" required>
                <option value="1">Article</option>
                <option value="2">Video</option>
                <option value="3">Podcast</option>
                <option value="4">Downloadable</option>
            </select>
        </div>
        <div class="form-group">
            <label>URL</label>
            <input type="url" name="url" class="form-control">
        </div>
        <div class="form-group">
            <label>File</label>
            <input type="file" name="file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add Resource</button>
    </form>
</div>

<?php
include_once "includes/footer.php";
?>