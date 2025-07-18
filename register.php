<?php


include 'init.php';




$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $checkStmt = $db->prepare('SELECT 1 FROM users WHERE username = :username');
    $checkStmt->bindValue(':username', $username);
    $result = $checkStmt->execute();
    if ($result->fetchArray()) {
        $error = 'Username already taken. Please choose another.';
    } else {
        // Insert new user
        $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', password_hash($password, PASSWORD_BCRYPT));
        if ($stmt->execute()) {
            header('Location: login.php');
            exit;
        } else {
            $error = 'Registration failed. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <form method="POST" autocomplete="off">
    <h2>Register</h2>
    <?php if ($error): ?>
      <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <input name="username" placeholder="Username" required autofocus>
    <input name="password" type="password" placeholder="Password" required>
    <button>Register</button>
    <p><a href="login.php">Back to login</a></p>
  </form>
</body>
</html>

