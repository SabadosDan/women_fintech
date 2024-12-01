<?php
include_once "config/database.php";
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();

// Preluarea job-urilor
$query = "SELECT * FROM jobs ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Preluarea job-urilor filtrate
$search = isset($_GET['search']) ? $_GET['search'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$job_type = isset($_GET['job_type']) ? $_GET['job_type'] : '';

$query = "SELECT * FROM jobs WHERE (title LIKE ? OR description LIKE ?) AND (location LIKE ?) AND (job_type LIKE ?) ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute(['%' . $search . '%', '%' . $search . '%', '%' . $location . '%', '%' . $job_type . '%']);
$filtered_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Preluarea profesiei utilizatorului curent pentru recomandări
$current_user_profession = '';
if (isset($_SESSION['user_id'])) {
    $query = "SELECT profession FROM members WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
    $current_user_profession = $stmt->fetch(PDO::FETCH_ASSOC)['profession'];
}

// Recomandarea job-urilor bazate pe similaritatea profesiilor
$query = "SELECT * FROM jobs WHERE description LIKE ? ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute(['%' . $current_user_profession . '%']);
$recommended_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2>Job Listings</h2>
    <div class="row">
        <?php foreach ($jobs as $job): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($job['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($job['description']); ?></p>
                        <p class="card-text"><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
                        <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                        <p class="card-text"><strong>Type:</strong> <?php echo htmlspecialchars($job['job_type']); ?></p>
                        <a href="apply_job.php?job_id=<?php echo $job['id']; ?>" class="btn btn-primary">Apply</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Filter Jobs</h2>
    <form method="GET" class="mb-3">
        <div class="form-group">
            <label for="search">Search</label>
            <input type="text" name="search" id="search" class="form-control" value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" id="location" class="form-control" value="<?php echo htmlspecialchars($location); ?>">
        </div>
        <div class="form-group">
            <label for="job_type">Job Type</label>
            <select name="job_type" id="job_type" class="form-control">
                <option value="">All</option>
                <option value="full-time" <?php echo $job_type == 'full-time' ? 'selected' : ''; ?>>Full-time</option>
                <option value="part-time" <?php echo $job_type == 'part-time' ? 'selected' : ''; ?>>Part-time</option>
                <option value="contract" <?php echo $job_type == 'contract' ? 'selected' : ''; ?>>Contract</option>
                <option value="internship" <?php echo $job_type == 'internship' ? 'selected' : ''; ?>>Internship</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <h2>Filtered Jobs</h2>
    <div class="row">
        <?php foreach ($filtered_jobs as $job): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($job['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($job['description']); ?></p>
                        <p class="card-text"><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
                        <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                        <p class="card-text"><strong>Type:</strong> <?php echo htmlspecialchars($job['job_type']); ?></p>
                        <a href="apply_job.php?job_id=<?php echo $job['id']; ?>" class="btn btn-primary">Apply</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Recommended Jobs</h2>
    <div class="row">
        <?php foreach ($recommended_jobs as $job): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($job['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($job['description']); ?></p>
                        <p class="card-text"><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
                        <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                        <p class="card-text"><strong>Type:</strong> <?php echo htmlspecialchars($job['job_type']); ?></p>
                        <a href="apply_job.php?job_id=<?php echo $job['id']; ?>" class="btn btn-primary">Apply</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
include_once "includes/footer.php";
?>