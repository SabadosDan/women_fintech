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

    // Verify and upload the profile picture
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = basename($target_file);
        }
    }


    $query = "INSERT INTO members
 (first_name, last_name, email, profession, company, profile_picture, expertise, linkedin_profile, skills, experience, education)
 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($query);

    $stmt->execute([
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['profession'],
        $_POST['company'],
        $profile_picture,
        $_POST['expertise'],
        $_POST['linkedin_profile']
    ]);

    // Create a notification about the new member
    $member_id = $db->lastInsertId();
    $message = "New member added: " . $_POST['first_name'] . " " . $_POST['last_name'];
    $query = "INSERT INTO notifications (member_id, message) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$member_id, $message]);

    header("Location: members.php");
    exit();
}
?>
<div class="form-container">
    <h2>Add New Member</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Profession</label>
            <input type="text" name="profession" class="form-control">
        </div>

        <div class="form-group">
            <label>Company</label>
            <input type="text" name="company" class="form-control">
        </div>

        <div>
            <label>Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control">
        </div>

        <div class="form-group">
            <label>Expertise</label>
            <textarea name="expertise" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label>LinkedIn Profile</label>
            <input type="url" name="linkedin_profile" class="form-control">
        </div>

        <div class="form-group">
            <label>Skills</label>
            <textarea name="skills" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label>Experience</label>
            <textarea name="experience" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label>Education</label>
            <textarea name="education" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Member</button>
    </form>
</div>
<?php
include_once "includes/footer.php";
?>