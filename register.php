<?php
include_once "config/database.php";
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();

// get members without users account
$query = "SELECT * FROM members WHERE user_id IS NULL";
$stmt = $db->prepare($query);
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $role_id = $_POST['role_id'];
    $member_id = isset($_POST['member_id']) ? $_POST['member_id'] : null;

    $query = "INSERT INTO users (username, password, email, role_id) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$username, $password, $email, $role_id]);

    // Assign the user to the member
    $user_id = $db->lastInsertId();
    if ($member_id) {
        $query = "UPDATE members SET user_id = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$user_id, $member_id]);
    }

    header("Location: login.php");
    exit();
}
?>

<div class="form-container">
    <h2>Register</h2>
    <form action="register.php" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role_id" class="form-control" required>
                <option value="1">Member</option>
                <option value="2">Mentor</option>
            </select>
            <p class="my-1">Talk with owner for Admin role.</p>
        </div>
        <div class="form-group" id="member_dropdown" style="display: block;">
            <label>Assign Role To ... </label>
            <select name="member_id" class="form-control">
                <option value="">Select user</option>
                <?php foreach ($members as $member): ?>
                    <option value="<?php echo $member['id']; ?>"><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <p class="mt-3">Already have an account ? <a href="login.php">Log in here</a></p>
</div>

<script>
    // function toggleMemberDropdown() {
    //     var roleSelect = document.querySelector('select[name="role_id"]');
    //     var memberDropdown = document.getElementById('member_dropdown');
    //     if (roleSelect.value == '1') {
    //         memberDropdown.style.display = 'block';
    //     } else {
    //         memberDropdown.style.display = 'none';
    //     }
    // }

    // document.addEventListener('DOMContentLoaded', function() {
    //     var roleSelect = document.querySelector('select[name="role_id"]');
    //     roleSelect.addEventListener('change', toggleMemberDropdown);
    //     toggleMemberDropdown();
    // });
</script>


<?php include_once "includes/footer.php"; ?>