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
    $program_id = $_POST['program_id'];
    $member_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];

    $query = "INSERT INTO mentorship_feedback (program_id, member_id, rating, feedback) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$program_id, $member_id, $rating, $feedback]);

    echo "<p>Feedback-ul tău a fost trimis cu succes.</p>";
} else {
    if (isset($_GET['program_id'])) {
        $program_id = $_GET['program_id'];
    } else {
        echo "<p>ID-ul programului nu este specificat.</p>";
        exit();
    }
}
?>

<div class="form-container">
    <h2>Mentorship Feedback</h2>
    <form action="mentorship_feedback.php" method="post">
        <input type="hidden" name="program_id" value="<?php echo htmlspecialchars($program_id); ?>">
        <div class="form-group">
            <label>Rating</label>
            <select name="rating" class="form-control" required>
                <option value="1">1 - Very Poor</option>
                <option value="2">2 - Poor</option>
                <option value="3">3 - Average</option>
                <option value="4">4 - Good</option>
                <option value="5">5 - Excellent</option>
            </select>
        </div>
        <div class="form-group">
            <label>Feedback</label>
            <textarea name="feedback" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Feedback</button>
    </form>
</div>

<a href="mentorship_program.php" class="btn btn-primary">Back to Mentorship Program</a>

<?php
include_once "includes/footer.php";
?>