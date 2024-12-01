<?php
include_once "config/database.php";
include_once "includes/header.php";
$database = new Database();
$db = $database->getConnection();

// Sorting criteria
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'created_at';
$order = ($sort_by == 'first_name' || $sort_by == 'last_name') ? 'ASC' : 'DESC';

// Profession filter criteria
$filter_profession = isset($_GET['profession']) ? $_GET['profession'] : '';

// <<<<<<< Pagination >>>>>>>>
//      Count total members
$query = "SELECT COUNT(*) as total FROM members WHERE profession LIKE ?";
$stmt = $db->prepare($query);
$stmt->execute(['%' . $filter_profession . '%']);
$total_members = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
//      Set members per page and numbers of pages
$members_per_page = 6;
$total_pages = ceil($total_members / $members_per_page);
//      Get current page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($current_page - 1) * $members_per_page;

// Get members after applying sorting, filter and pagination
$query = "SELECT * FROM members WHERE profession LIKE ? ORDER BY $sort_by $order LIMIT $members_per_page OFFSET $offset";
$stmt = $db->prepare($query);
$stmt->execute(['%' . $filter_profession . '%']);
?>
<h2>Members Directory</h2>
<!-- Sorting and filter form -->
<form method="GET" class="mb-3">
    <div class="form-group">
        <label for="sort_by">Sort by:</label>
        <select name="sort_by" id="sort_by" class="form-control" onchange="this.form.submit()">
            <option value="created_at" <?php echo $sort_by == 'created_at' ? 'selected' : ''; ?>>Date Joined</option>
            <option value="first_name" <?php echo $sort_by == 'first_name' ? 'selected' : ''; ?>>First Name</option>
            <option value="last_name" <?php echo $sort_by == 'last_name' ? 'selected' : ''; ?>>Last Name</option>
        </select>
    </div>
    <div class="form-group  ">
        <label for="profession">Filter by Profession:</label>
        <input type="text" name="profession" id="profession" class="form-control" value="<?php echo htmlspecialchars($filter_profession); ?>" placeholder="Enter profession" onchange="this.form.submit()">
</form>

<div class="row">
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="col-md-4">
            <div class="card member-card">
                <div class="card-body text-center">
                    <?php if ($row['profile_picture']): ?>
                        <img src="<?php echo htmlspecialchars('uploads/' . $row['profile_picture']); ?>" width="150" height="200" class="card-img-top object-fit-cover rounded-circle pb-2" alt="Profile Picture">
                    <?php endif; ?>
                    <h5 class="card-title"><?php echo htmlspecialchars($row['first_name'] . ' ' .
                                                $row['last_name']); ?></h5>
                    <p class="card-text">
                        <strong>Profession:</strong> <?php echo
                                                        htmlspecialchars($row['profession']); ?><br>
                        <strong>Company:</strong> <?php echo htmlspecialchars($row['company']);
                                                    ?><br>
                        <strong>Skills:</strong> <?php echo htmlspecialchars($row['skills']); ?><br>
                        <strong>Experience:</strong> <?php echo htmlspecialchars($row['experience']); ?>
                    </p>
                    <?php if (isset($_SESSION['user_id']) && ($_SESSION['role_id'] == 2 || $_SESSION['role_id'] == 3)): ?>
                        <a href="edit_member.php?id=<?php echo $row['id']; ?>" class="btn btnprimary">Edit</a>
                        <a href="delete_member.php?id=<?php echo $row['id']; ?>" class="btn btndanger"
                            onclick="return confirm('Are you sure?')">Delete</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?php if ($current_page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&sort_by=<?php echo $sort_by; ?>&profession=<?php echo $filter_profession; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&sort_by=<?php echo $sort_by; ?>&profession=<?php echo $filter_profession; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
        <?php if ($current_page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&sort_by=<?php echo $sort_by; ?>&profession=<?php echo $filter_profession; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php
include_once "includes/footer.php";
?>