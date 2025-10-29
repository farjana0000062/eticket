<?php
require_once 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $pw = $_POST['password'];
  if ($name && $email && $pw) {
    $hash = password_hash($pw, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("INSERT INTO users (name,email,password_hash) VALUES (?,?,?)");
    $stmt->bind_param('sss',$name,$email,$hash);
    if ($stmt->execute()) {
      echo "<p>Registered. <a href='login.php'>Login now</a></p>";
      require_once 'includes/footer.php'; exit;
    } else {
      echo "<p>Error: possibly email already used.</p>";
    }
  }
}
?>
<h2>Register</h2>
<form method="post">
  <input name="name" required placeholder="Full name">
  <input name="email" type="email" required placeholder="Email">
  <input name="password" type="password" required placeholder="Password">
  <button>Register</button>
</form>
<?php require_once 'includes/footer.php'; ?>
