<?php require 'init.php'; ?>
<?php if (!isset($_SESSION['user'])) exit("Login required"); ?>

<?php
$id = (int)($_GET['id'] ?? 0);
if (!$id) exit("Invalid paste ID.");

$res = $db->query("SELECT * FROM pastes WHERE id = $id");
$row = $res->fetchArray();
if (!$row) exit("Paste not found.");

$owner = $row['owner'];
$viewer = $_SESSION['user'];

$is_owner = ($owner === $viewer);
$shared = $db->querySingle("SELECT 1 FROM shares WHERE paste_id = $id AND shared_with = '$viewer'");

if (!$is_owner && !$shared) exit("This paste is not shared with you.");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title><?= htmlspecialchars($row['title']) ?> - My Pastebin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 800px;
      margin: 2rem auto;
      padding: 0 1rem;
      background: #f9f9f9;
      color: #333;
    }
    h1 {
      color: #444;
      margin-bottom: 0.5rem;
    }
    nav {
      margin-bottom: 1rem;
    }
    nav a, button.back-button {
      color: #1a73e8;
      text-decoration: none;
      background: none;
      border: none;
      padding: 0;
      font-size: 1rem;
      cursor: pointer;
      font-family: Arial, sans-serif;
    }
    nav a:hover, button.back-button:hover {
      text-decoration: underline;
    }
    .content {
      background: white;
      padding: 1rem 1.5rem;
      border-radius: 6px;
      box-shadow: 0 1px 5px rgb(0 0 0 / 0.1);
      white-space: pre-wrap; /* preserve new lines */
      margin-bottom: 2rem;
      font-family: Consolas, monospace;
      font-size: 1rem;
      color: #222;
    }
  </style>
</head>
<body>

<nav>
  <a href="index.php">Home</a> | <a href="logout.php">Logout</a>
</nav>

<h1><?= htmlspecialchars($row['title']) ?></h1>

<div class="content"><?= $row['content'] ?></div>

<button class="back-button" onclick="history.back()">← Back</button>

</body>
</html>

