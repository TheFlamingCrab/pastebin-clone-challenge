<?php
include 'init.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare('SELECT password FROM users WHERE username = :username');
    $stmt->bindValue(':username', $username);
    $result = $stmt->execute();

    $row = $result->fetchArray(SQLITE3_ASSOC);
    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['user'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid credentials.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <form method="POST" autocomplete="off">
    <h2>Login</h2>
    <?php if ($error): ?>
      <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <input name="username" placeholder="Username" required autofocus>
    <input name="password" type="password" placeholder="Password" required>
    <button>Login</button>
    <p><a href="register.php">Register</a></p>
  </form>
</body>
</html>

