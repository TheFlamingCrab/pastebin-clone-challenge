<?php require 'init.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>My Pastebin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 900px;
      margin: 2rem auto;
      padding: 0 1rem;
      background: #f9f9f9;
      color: #333;
    }
    h1, h2 {
      color: #444;
    }
    a {
      color: #1a73e8;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    ul {
      list-style: none;
      padding-left: 0;
    }
    li {
      background: white;
      padding: 0.5rem 1rem;
      margin-bottom: 0.5rem;
      border-radius: 6px;
      box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    form.share-form {
      margin-left: 1rem;
      display: flex;
      align-items: center;
    }
    form.share-form input {
      padding: 0.25rem 0.5rem;
      border: 1px solid #ccc;
      border-radius: 3px;
      font-size: 0.9rem;
      min-width: 150px;
    }
    form.share-form button {
      margin-left: 0.3rem;
      padding: 0.25rem 0.6rem;
      background: #1a73e8;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      font-size: 0.9rem;
    }
    form.share-form button:hover {
      background: #155ab6;
    }
    .share-message {
      font-size: 0.9rem;
      font-style: italic;
      color: green;
      margin-left: 10px;
      user-select: none;
    }
    nav {
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

<?php if (!isset($_SESSION['user'])): ?>
  <h1>My amazing pastebin app of... well... amazing</h1>
  <nav>
    <a href="login.php">Login</a> | <a href="register.php">Register</a>
  </nav>
<?php else: ?>
  <p>Logged in as <?= htmlspecialchars($_SESSION['user']) ?> | <a href="logout.php">Logout</a></p>
  <nav>
    <a href="new.php">Create New Paste</a>
  </nav>

  <h2>Your Pastes</h2>
  <ul>
  <?php
  $user = $_SESSION['user'];
  $res = $db->query("SELECT * FROM pastes WHERE owner = '$user'");
  while ($row = $res->fetchArray()):
  ?>
    <li>
      <a href="paste.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a>

      <form class="share-form" method="POST" action="share.php" style="margin:0;">
        <input name="user" placeholder="Share with user" required />
        <input type="hidden" name="id" value="<?= $row['id'] ?>" />
        <button type="submit">Share</button>
      </form>
    </li>
  <?php endwhile; ?>
  </ul>

  <h2>Shared With You</h2>
  <ul>
  <?php
  $res = $db->query("SELECT p.* FROM pastes p JOIN shares s ON p.id = s.paste_id WHERE s.shared_with = '$user'");
  while ($row = $res->fetchArray()):
  ?>
    <li><a href="paste.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a> (from <?= htmlspecialchars($row['owner']) ?>)</li>
  <?php endwhile; ?>
  </ul>

<?php endif; ?>

<script>
document.querySelectorAll('form.share-form').forEach(form => {
  form.addEventListener('submit', async function(e) {
    e.preventDefault();  // prevent page reload

    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: formData
      });

      if (!response.ok) {
        alert('Failed to share. Please try again.');
        return;
      }

      // Show success message next to form
      let messageEl = form.querySelector('.share-message');
      if (!messageEl) {
        messageEl = document.createElement('div');
        messageEl.className = 'share-message';
        form.appendChild(messageEl);
      }
      messageEl.textContent = `Successfully shared with ${form.user.value}`;

      // Clear the input field
      form.user.value = '';
    } catch (err) {
      alert('Error sharing paste: ' + err.message);
    }
  });
});
</script>

</body>
</html>

