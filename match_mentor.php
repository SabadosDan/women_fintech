<?php
include_once "config/database.php";
include_once "includes/header.php";



$database = new Database();
$db = $database->getConnection();

// Preluarea mentorilor
$query = "SELECT m.* FROM members m
          JOIN users u ON m.user_id = u.id
          WHERE u.role_id = 2";
$stmt = $db->prepare($query);
$stmt->execute();
$mentors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Preluarea mentee-urilor
$query = "SELECT m.* FROM members m
          JOIN users u ON m.user_id = u.id
          WHERE u.role_id = 1";
$stmt = $db->prepare($query);
$stmt->execute();
$mentees = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mentor_id = $_POST['mentor_id'];
    $mentee_id = $_POST['mentee_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $query = "INSERT INTO mentorship_program (mentor_id, mentee_id, start_date, end_date) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$mentor_id, $mentee_id, $start_date, $end_date]);

    echo "<p>Mentorship program created successfully.</p>";
}
?>

<div class="form-container">
    <h2>Match Mentor and Mentee</h2>
    <form action="match_mentor.php" method="post">
        <div class="form-group">
            <label>Mentor</label>
            <select name="mentor_id" class="form-control" required>
                <option value="">Select Mentor</option>
                <?php foreach ($mentors as $mentor): ?>
                    <option value="<?php echo $mentor['id']; ?>"><?php echo htmlspecialchars($mentor['first_name'] . ' ' . $mentor['last_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Mentee</label>
            <select name="mentee_id" class="form-control" required>
                <option value="">Select Mentee</option>
                <?php foreach ($mentees as $mentee): ?>
                    <option value="<?php echo $mentee['id']; ?>"><?php echo htmlspecialchars($mentee['first_name'] . ' ' . $mentee['last_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Program</button>
    </form>
</div>

<?php
include_once "track_progress.php";
// include_once "mentorship_feedback.php";
include_once "includes/footer.php";
?>