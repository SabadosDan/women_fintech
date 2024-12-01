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
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];
    $event_type = $_POST['event_type'];
    $max_participants = $_POST['max_participants'];
    $created_by = $_SESSION['user_id'];

    $query = "INSERT INTO events (title, description, event_date, location, event_type, max_participants, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$title, $description, $event_date, $location, $event_type, $max_participants, $created_by]);

    header("Location: events.php");
    exit();
}
?>

<div class="form-container">
    <h2>Add New Event</h2>
    <form action="add_event.php" method="post">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Event Date</label>
            <input type="datetime-local" name="event_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Location</label>
            <input type="text" name="location" class="form-control">
        </div>
        <div class="form-group">
            <label>Event Type</label>
            <select name="event_type" class="form-control" required>
                <option value="workshop">Workshop</option>
                <option value="mentoring">Mentoring</option>
                <option value="networking">Networking</option>
                <option value="conference">Conference</option>
            </select>
        </div>
        <div class="form-group">
            <label>Max Participants</label>
            <input type="number" name="max_participants" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Event</button>
    </form>
</div>

<?php
include_once "includes/footer.php";
?>