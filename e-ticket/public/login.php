<?php
require_once 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $pw = $_POST['password'];
  $stmt = $mysqli->prepare("SELECT id, password_hash FROM users WHERE email = ?");
  $stmt->bind_param('s',$email);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_assoc();
  if ($res && password_verify($pw, $res['password_hash'])) {
    $_SESSION['user_id'] = $res['id'];
    header('Location: index.php'); exit;
  } else {
    echo "<p>Invalid credentials</p>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="css/login.css"> -->
    <title>Document</title>
</head>
<body>
    
<h2>Login</h2>
<form method="post">
  <input name="email" type="email" required placeholder="Email">
  <input name="password" type="password" required placeholder="Password">
  <button>Login</button>
</form>
<?php require_once 'includes/footer.php'; ?>
</body>
</html>
