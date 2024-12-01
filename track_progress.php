<?php
include_once "config/database.php";
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();

// Preluarea programelor de mentorat
$query = "SELECT mp.*, m1.first_name AS mentor_first_name, m1.last_name AS mentor_last_name, m2.first_name AS mentee_first_name, m2.last_name AS mentee_last_name 
          FROM mentorship_program mp
          JOIN members m1 ON mp.mentor_id = m1.id
          JOIN members m2 ON mp.mentee_id = m2.id
          JOIN users u1 ON m1.user_id = u1.id
          JOIN users u2 ON m2.user_id = u2.id
          WHERE (u1.role_id = 2 AND mp.mentor_id = ?) OR (u2.role_id = 1 AND mp.mentee_id = ?)";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
$programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['program_id'], $_POST['progress_date'], $_POST['progress_notes'])) {
        $program_id = $_POST['program_id'];
        $progress_date = $_POST['progress_date'];
        $progress_notes = $_POST['progress_notes'];

        $query = "INSERT INTO mentorship_progress (program_id, progress_date, progress_notes) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$program_id, $progress_date, $progress_notes]);

        echo "<p>Progress tracked successfully.</p>";
    } else {
        echo "<p>All fields are required.</p>";
    }
}
?>

<div class="form-container">
    <h2>Track Progress</h2>
    <form action="track_progress.php" method="post">
        <div class="form-group">
            <label>Mentorship Program</label>
            <select name="program_id" class="form-control" required>
                <option value="">Select Program</option>
                <?php foreach ($programs as $program): ?>
                    <option value="<?php echo $program['id']; ?>"><?php echo htmlspecialchars("Mentor: " . $program['mentor_first_name'] . " " . $program['mentor_last_name'] . " - Mentee: " . $program['mentee_first_name'] . " " . $program['mentee_last_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Progress Date</label>
            <input type="date" name="progress_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Progress Notes</label>
            <textarea name="progress_notes" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Track Progress</button>
    </form>
</div>

<?php
include_once "includes/footer.php";
?>