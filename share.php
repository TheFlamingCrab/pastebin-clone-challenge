<?php
require 'init.php';

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    exit("Login required");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paste_id = (int)$_POST['id'];
    $user = $_POST['user'];
    $owner = $_SESSION['user'];

    $row = $db->query("SELECT * FROM pastes WHERE id = $paste_id")->fetchArray();
    if ($row && $row['owner'] === $owner) {
        $stmt = $db->prepare("INSERT INTO shares (paste_id, shared_with) VALUES (?, ?)");
        $stmt->bindValue(1, $paste_id);
        $stmt->bindValue(2, $user);
        $stmt->execute();
        // Just respond success, no redirect
        http_response_code(200);
        exit;
    }
    http_response_code(400);
    exit;
}
?>

