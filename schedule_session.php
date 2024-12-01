<?php
include_once "config/database.php";
include_once "includes/header.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 1)) {
    header("Location: index.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

// get programs where the user is a mentor or mentee
$query = "SELECT * FROM mentorship_program WHERE mentor_id = ? OR mentee_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
$programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $program_id = $_POST['program_id'];
    $session_date = $_POST['session_date'];
    $topic = $_POST['topic'];
    $notes = $_POST['notes'];

    $query = "INSERT INTO mentorship_sessions (program_id, session_date, topic, notes) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$program_id, $session_date, $topic, $notes]);

    echo "<p>Session scheduled successfully.</p>";
}
?>

<div class="form-container">
    <h2>Schedule Session</h2>
    <form action="schedule_session.php" method="post">
        <div class="form-group">
            <label>Mentorship Program</label>
            <select name="program_id" class="form-control" required>
                <option value="">Select Program</option>
                <?php foreach ($programs as $program): ?>
                    <option value="<?php echo $program['id']; ?>"><?php echo htmlspecialchars("Mentor: " . $program['mentor_id'] . " - Mentee: " . $program['mentee_id']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Session Date</label>
            <input type="datetime-local" name="session_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Topic</label>
            <input type="text" name="topic" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Schedule Session</button>
    </form>
</div>

<?php
include_once "includes/footer.php";
?>