<?php
include_once "config/database.php";
include_once "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $member_id = $_SESSION['user_id'];

    // check if user is already registered to this event
    $query = "SELECT * FROM event_registrations WHERE event_id = ? AND member_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$event_id, $member_id]);
    $registration = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$registration) {
        // register user to event
        $query = "INSERT INTO event_registrations (event_id, member_id) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$event_id, $member_id]);

        echo "<p>Successfully registered to event.</p>";
    } else {
        echo "<p>You are already registered.</p>";
    }
} else {
    echo "<p>wrong event.</p>";
}
?>

<a href="events.php" class="btn btn-primary">Back to Events</a>

<?php
include_once "includes/footer.php";
?>