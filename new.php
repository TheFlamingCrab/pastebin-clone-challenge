<?php
require 'init.php';
if (!isset($_SESSION['user'])) {
    exit("Login required");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];  // No sanitization on purpose (XSS vulnerable)
    $user = $_SESSION['user'];

    $stmt = $db->prepare("INSERT INTO pastes (owner, title, content) VALUES (?, ?, ?)");
    $stmt->bindValue(1, $user);
    $stmt->bindValue(2, $title);
    $stmt->bindValue(3, $content);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Create New Paste - My Pastebin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 700px;
      margin: 2rem auto;
      padding: 0 1rem;
      background: #f9f9f9;
      color: #333;
    }
    h1 {
      color: #444;
    }
    form {
      background: white;
      padding: 1.5rem;
      border-radius: 6px;
      box-shadow: 0 1px 5px rgb(0 0 0 / 0.1);
    }
    input, textarea {
      width: 100%;
      padding: 0.5rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 3px;
      font-size: 1rem;
      box-sizing: border-box;
    }
    button {
      padding: 0.7rem 1.2rem;
      background: #1a73e8;
      color: white;
      border: none;
      border-radius: 3px;
      font-size: 1rem;
      cursor: pointer;
    }
    button:hover {
      background: #155ab6;
    }
    nav {
      margin-bottom: 1rem;
    }
    nav a {
      color: #1a73e8;
      text-decoration: none;
    }
    nav a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<nav>
  <a href="index.php">Home</a> | <a href="logout.php">Logout</a>
</nav>

<h1>Create New Paste</h1>

<form method="POST" autocomplete="off">
  <input name="title" placeholder="Title" required />
  <textarea name="content" rows="10" placeholder="Paste content here..."></textarea>
  <button type="submit">Create</button>
</form>

</body>
</html>

