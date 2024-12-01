<?php
include_once "config/database.php";
include_once "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

// number of total members
$query = "SELECT COUNT(*) as total_members FROM members";
$stmt = $db->prepare($query);
$stmt->execute();
$total_members = $stmt->fetch(PDO::FETCH_ASSOC)['total_members'];

// Profession distribution
$query = "SELECT profession, COUNT(*) as count FROM members GROUP BY profession";
$stmt = $db->prepare($query);
$stmt->execute();
$profession_distribution = $stmt->fetchAll(PDO::FETCH_ASSOC);

// New members each month
$query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count FROM members GROUP BY month";
$stmt = $db->prepare($query);
$stmt->execute();
$new_members_per_month = $stmt->fetchAll(PDO::FETCH_ASSOC);

// top 5 companies
$query = "SELECT company, COUNT(*) as count FROM members GROUP BY company ORDER BY count DESC LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute();
$top_companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get all notifications
$query = "SELECT * FROM notifications ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// mark all notifications as read
$query = "UPDATE notifications SET read_status = TRUE WHERE read_status = FALSE";
$stmt = $db->prepare($query);
$stmt->execute();
?>
<h2>Dashboard</h2>
<div class="row">
    <?php if ($role_id == 3): // Admin 
    ?>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Members</h5>
                    <p class="card-text"><?php echo $total_members; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Profession Distribution</h5>
                    <ul class="list-group">
                        <?php foreach ($profession_distribution as $profession): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($profession['profession']); ?>
                                <span class="badge badge-primary badge-pill"><?php echo $profession['count']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">New Members Per Month</h5>
                    <ul class="list-group">
                        <?php foreach ($new_members_per_month as $month): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($month['month']); ?>
                                <span class="badge badge-primary badge-pill"><?php echo $month['count']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Top Companies Represented</h5>
                    <ul class="list-group">
                        <?php foreach ($top_companies as $company): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($company['company']); ?>
                                <span class="badge badge-primary badge-pill"><?php echo $company['count']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($role_id == 2): // Mentor 
    ?>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Profession Distribution</h5>
                    <ul class="list-group">
                        <?php foreach ($profession_distribution as $profession): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($profession['profession']); ?>
                                <span class="badge badge-primary badge-pill"><?php echo $profession['count']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Top Companies Represented</h5>
                    <ul class="list-group">
                        <?php foreach ($top_companies as $company): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($company['company']); ?>
                                <span class="badge badge-primary badge-pill"><?php echo $company['count']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Notifications</h5>
                    <ul class="list-group">
                        <?php foreach ($notifications as $notification): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($notification['message']); ?>
                                <span class="badge badge-<?php echo $notification['read_status'] ? 'secondary' : 'primary'; ?> badge-pill"><?php echo $notification['read_status'] ? 'Read' : 'Unread'; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($role_id == 1): // Member 
    ?>
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h5>
                    <p class="card-text">Here you can find the latest updates and notifications.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Profession Distribution</h5>
                    <ul class="list-group">
                        <?php foreach ($profession_distribution as $profession): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($profession['profession']); ?>
                                <span class="badge badge-primary badge-pill"><?php echo $profession['count']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Top Companies Represented</h5>
                    <ul class="list-group">
                        <?php foreach ($top_companies as $company): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($company['company']); ?>
                                <span class="badge badge-primary badge-pill"><?php echo $company['count']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
</div>

<?php
include_once "includes/footer.php";
?>