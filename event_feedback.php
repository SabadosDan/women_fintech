<?php
include_once "config/database.php";
include_once "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $member_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];

    $query = "INSERT INTO event_feedback (event_id, member_id, rating, feedback) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$event_id, $member_id, $rating, $feedback]);

    echo "<p>Feedback sent successfully.</p>";
} else {
    if (isset($_GET['event_id'])) {
        $event_id = $_GET['event_id'];
    } else {
        echo "<p>wrong event.</p>";
        exit();
    }
}
?>

<a href="events.php" class="btn btn-primary">Back to Events</a>

<?php
include_once "includes/footer.php";
?>