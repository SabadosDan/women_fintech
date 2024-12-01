<?php
include_once "config/database.php";
include_once "includes/header.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: index.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

// get profession of the current user
$query = "SELECT profession FROM members WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$current_user_profession = $stmt->fetch(PDO::FETCH_ASSOC)['profession'];

// get members with the same profession
$query = "SELECT * FROM members WHERE profession = ? AND (user_id != ? OR user_id IS NULL)";
$stmt = $db->prepare($query);
$stmt->execute([$current_user_profession, $_SESSION['user_id']]);
$recommended_members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mt-4">
    <h2>Recommended Connections</h2>
    <div class="row">
        <?php foreach ($recommended_members as $member): ?>
            <div class="col-md-4">
                <div class="card member-card">
                    <div class="card-body text-center">
                        <?php if ($member['profile_picture']): ?>
                            <img src="uploads/<?php echo htmlspecialchars($member['profile_picture']); ?>" width="150" height="200" class="card-img-top object-fit-cover rounded-circle pb-2" alt="Profile Picture">
                        <?php endif; ?>
                        <h5 class="card-title"><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></h5>
                        <p class="card-text">
                            <strong>Profession:</strong> <?php echo htmlspecialchars($member['profession']); ?><br>
                            <strong>Company:</strong> <?php echo htmlspecialchars($member['company']); ?><br>
                            <strong>Skills:</strong> <?php echo htmlspecialchars($member['skills']); ?><br>
                            <strong>Experience:</strong> <?php echo htmlspecialchars($member['experience']); ?><br>
                            <strong>Education:</strong> <?php echo htmlspecialchars($member['education']); ?>
                        </p>
                        <a href="view_profile.php?id=<?php echo $member['id']; ?>" class="btn btn-primary">View Profile</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
include_once "includes/footer.php";
?>