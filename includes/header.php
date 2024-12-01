<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Women in FinTech</title>
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body id="body">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="resources/logo.png" alt="Women in FinTech" width="50  " height="50"
                    class="d-inline-block align-top">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" datatarget="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="members.php">Members</a>
                    </li>
                    <?php if (isset($_SESSION['user_id']) && ($_SESSION['role_id'] == 2 || $_SESSION['role_id'] == 3)): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="add_member.php">Add Member</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="resource_hub.php">Resource Hub</a>
                    </li>
                    <?php if (isset($_SESSION['user_id']) && ($_SESSION['role_id'] == 2 || $_SESSION['role_id'] == 3)): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="add_resource.php">Add resource</a>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role_id'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="recommendations.php">Recommendations</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="events.php">Events</a>
                    </li>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role_id'] == 3): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="match_mentor.php">Match mentor-mentee</a>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role_id'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="jobs.php">Jobs</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <button id="dark-mode-toggle" class="btn btn-secondary ml-auto mr-2">Dark Mode</button>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="login.php" class="btn btn-info ms-2">Log In</a>
                <?php else: ?>
                    <a href="logout.php" class="btn btn-info ms-2">Log Out</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">