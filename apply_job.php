<?php
include_once "config/database.php";
include_once "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $member_id = $_SESSION['user_id'];

    // check if the user has already applied to this job
    $query = "SELECT * FROM job_applications WHERE job_id = ? AND member_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$job_id, $member_id]);
    $application = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$application) {
        // apply
        $query = "INSERT INTO job_applications (job_id, member_id) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$job_id, $member_id]);

        echo "<p>Successfully applied.</p>";
    } else {
        echo "<p>Already applied to this job.</p>";
    }
} else {
    echo "<p>Wrong job.</p>";
}
?>

<a href="list_jobs.php" class="btn btn-primary">Back to Job Listings</a>

<?php
include_once "includes/footer.php";
?>