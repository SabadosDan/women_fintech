<?php
include_once "config/database.php";
include_once "includes/header.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 3)) {
    header("Location: index.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM members WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_GET['id']]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $profile_picture = $member['profile_picture'];
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = basename($target_file);
        }
    }

    $query = "UPDATE members
 SET first_name=?, last_name=?, email=?, profession=?,
 company=?, profile_picture=?, expertise=?, linkedin_profile=?, skills=?, experience=?, education=?
 WHERE id=?";

    $stmt = $db->prepare($query);
    $stmt->execute([
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['profession'],
        $_POST['company'],
        $profile_picture,
        $_POST['expertise'],
        $_POST['linkedin_profile'],
        $_POST['skills'],
        $_POST['experience'],
        $_POST['education'],
        $_GET['id']
    ]);

    // Create a notification about the update
    $message = "Member updated: " . $_POST['first_name'] . " " . $_POST['last_name'];
    $query = "INSERT INTO notifications (member_id, message) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$_GET['id'], $message]);

    header("Location: members.php");
    exit();
}

?>
<div class="form-container">
    <h2>Edit Member</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control"
                value="<?php echo htmlspecialchars($member['first_name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control"
                value="<?php echo htmlspecialchars($member['last_name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                value="<?php echo htmlspecialchars($member['email']); ?>" required>
        </div>

        <div class="form-group">
            <label>Profession</label>
            <input type="text" name="profession" class="form-control"
                value="<?php echo htmlspecialchars($member['profession']); ?>">
        </div>

        <div class="form-group">
            <label>Company</label>
            <input type="text" name="company" class="form-control"
                value="<?php echo htmlspecialchars($member['company']); ?>">
        </div>

        <div class="form-group">
            <label>Profile Picture</label>
            <?php if ($member['profile_picture']): ?>
                <img src="<?php echo htmlspecialchars('uploads/' . $member['profile_picture']); ?>" class="img-thumbnail mb-2" alt="Profile Picture" style="width: 150px; height: 150px;">
            <?php endif; ?>
            <input type="file" name="profile_picture" class="form-control">
        </div>

        <div class="form-group">
            <label>Expertise</label>
            <textarea name="expertise" class="form-control"><?php echo
                                                            htmlspecialchars($member['expertise']); ?></textarea>
        </div>

        <div class="form-group">
            <label>LinkedIn Profile</label>
            <input type="url" name="linkedin_profile" class="form-control"
                value="<?php echo htmlspecialchars($member['linkedin_profile']); ?>">
        </div>

        <div class="form-group">
            <label>Skills</label>
            <textarea name="skills" class="form-control"><?php echo htmlspecialchars($member['skills']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Experience</label>
            <textarea name="experience" class="form-control"><?php echo htmlspecialchars($member['experience']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Education</label>
            <textarea name="education" class="form-control"><?php echo htmlspecialchars($member['education']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Member</button>
    </form>
</div>
<?php
include_once "includes/footer.php";
?>