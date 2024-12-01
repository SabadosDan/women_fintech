<?php
include_once "config/database.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 3)) {
    header("Location: index.php");
    exit();
}
if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "DELETE FROM members WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_GET['id']]);
}

// Create a notification about the deletion
$message = "Member deleted" . $_POST['first_name'] . " " . $_POST['last_name'];
$query = "INSERT INTO notifications (member_id, message) VALUES (?, ?)";
$stmt = $db->prepare($query);
$stmt->execute([$_GET['id'], $message]);

header("Location: members.php");
exit();
