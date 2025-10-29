<?php
// admin/login should set a session like $_SESSION['admin'] = true
session_start();
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name']; $number = $_POST['number']; $total = intval($_POST['total_seats']);
  $stmt = $mysqli->prepare("INSERT INTO trains (name,number,total_seats) VALUES (?,?,?)");
  $stmt->bind_param('ssi',$name,$number,$total);
  $stmt->execute();
}

$trs = $mysqli->query("SELECT * FROM trains")->fetch_all(MYSQLI_ASSOC);
?>
<h1>Trains</h1>
<form method="post">
  <input name="name" placeholder="Train name" required>
  <input name="number" placeholder="Train number" required>
  <input name="total_seats" type="number" placeholder="Total seats" required>
  <button>Add</button>
</form>

<table>
  <thead><tr><th>ID</th><th>Name</th><th>Number</th><th>Seats</th></tr></thead>
  <tbody>
    <?php foreach($trs as $t): ?>
      <tr><td><?= $t['id'] ?></td><td><?= htmlspecialchars($t['name']) ?></td><td><?= htmlspecialchars($t['number']) ?></td><td><?= $t['total_seats'] ?></td></tr>
    <?php endforeach; ?>
  </tbody>
</table>
