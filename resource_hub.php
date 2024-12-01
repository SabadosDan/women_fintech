<?php
include_once "config/database.php";
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();

// search and filter criteria
$search = isset($_GET['search']) ? $_GET['search'] : '';
$type_id = isset($_GET['type_id']) ? $_GET['type_id'] : '';

// get all resources
$query = "SELECT resources.*, resource_types.type_name FROM resources
          JOIN resource_types ON resources.type_id = resource_types.id
          WHERE (title LIKE ? OR description LIKE ?)
          AND (type_id = ? OR ? = '')";
$stmt = $db->prepare($query);
$stmt->execute(['%' . $search . '%', '%' . $search . '%', $type_id, $type_id]);
$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get embed URL for video
function getYouTubeEmbedUrl($url)
{
    if (preg_match('/youtu\.be\/([^\?]*)/', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    } elseif (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]*)/', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    } elseif (preg_match('/youtube\.com\/embed\/([^\&\?\/]*)/', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }
    return $url;
}

// Get embed URL for audio
function getYouTubeAudioEmbedUrl($url)
{
    if (preg_match('/youtu\.be\/([^\?]*)/', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&controls=1&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1&playsinline=1';
    } elseif (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]*)/', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&controls=1&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1&playsinline=1';
    } elseif (preg_match('/youtube\.com\/embed\/([^\&\?\/]*)/', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&controls=1&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1&playsinline=1';
    }
    return $url;
}

?>

<div class="container mt-4">
    <h2>Resource Hub</h2>
    <form method="GET" class="mb-3">
        <div class="form-group">
            <label for="search">Search</label>
            <input type="text" name="search" id="search" class="form-control" value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="form-group">
            <label for="type_id">Filter by Type</label>
            <select name="type_id" id="type_id" class="form-control">
                <option value="">All</option>
                <option value="1" <?php echo $type_id == 1 ? 'selected' : ''; ?>>Article</option>
                <option value="2" <?php echo $type_id == 2 ? 'selected' : ''; ?>>Video</option>
                <option value="3" <?php echo $type_id == 3 ? 'selected' : ''; ?>>Podcast</option>
                <option value="4" <?php echo $type_id == 4 ? 'selected' : ''; ?>>Downloadable</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <div class="row">
        <?php foreach ($resources as $resource): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($resource['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($resource['description']); ?></p>
                        <p class="card-text"><strong>Type:</strong> <?php echo htmlspecialchars($resource['type_name']); ?></p>
                        <?php if ($resource['type_id'] == 2 && $resource['url']): // Video 
                        ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="<?php echo htmlspecialchars(getYouTubeEmbedUrl($resource['url'])); ?>" allowfullscreen></iframe>
                            </div>
                        <?php elseif ($resource['type_id'] == 3 && $resource['url']): // Podcast 
                        ?>
                            <div class="embed-responsive embed-responsive-16by9" style="height: 60px;">
                                <iframe class="embed-responsive-item" src="<?php echo htmlspecialchars(getYouTubeAudioEmbedUrl($resource['url'])); ?>" style="height: 60px; width: 100%;"></iframe>
                            </div>
                        <?php elseif ($resource['url']): ?>
                            <a href="<?php echo htmlspecialchars($resource['url']); ?>" class="btn btn-primary" target="_blank">View Resource</a>
                        <?php endif; ?>
                        <?php if ($resource['file_path']): ?>
                            <a href="<?php echo htmlspecialchars($resource['file_path']); ?>" class="btn btn-primary" download>Download Resource</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
include_once "includes/footer.php";
?>