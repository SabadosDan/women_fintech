<?php
include_once "config/database.php";
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();

// get all events
$query = "SELECT * FROM events ORDER BY event_date ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get all registered events of current user_error
$query = "SELECT e.*, er.status FROM events e
          JOIN event_registrations er ON e.id = er.event_id
          WHERE er.member_id = ? ORDER BY e.event_date ASC";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$registered_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php if (isset($_SESSION['user_id']) && $_SESSION['role_id'] == 2): ?>
    <a class="btn btn-primary" href="add_event.php">Create Event</a>
<?php endif; ?>

<div class="container mt-4">
    <h2>Upcoming Events</h2>
    <div class="row">
        <?php foreach ($events as $event): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                        <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                        <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                        <p class="card-text"><strong>Type:</strong> <?php echo htmlspecialchars($event['event_type']); ?></p>
                        <p class="card-text"><strong>Max Participants:</strong> <?php echo htmlspecialchars($event['max_participants']); ?></p>
                        <a href="register_event.php?event_id=<?php echo $event['id']; ?>" class="btn btn-primary">Register</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<h2>My events</h2>
<div class="row">
    <?php foreach ($registered_events as $event): ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                    <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                    <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                    <p class="card-text"><strong>Type:</strong> <?php echo htmlspecialchars($event['event_type']); ?></p>
                    <p class="card-text"><strong>Status:</strong> <?php echo htmlspecialchars($event['status']); ?></p>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role_id'] == 1): ?>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#feedbackModal<?php echo $event['id']; ?>">Give Feedback</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Feedback -->
        <div class="modal fade" id="feedbackModal<?php echo $event['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel<?php echo $event['id']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feedbackModalLabel<?php echo $event['id']; ?>">Give Feedback for <?php echo htmlspecialchars($event['title']); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="event_feedback.php" method="post">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
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
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</div>

<?php
include_once "includes/footer.php";
?>