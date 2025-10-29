<?php
require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php'); exit;
}
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php'); exit;
}

$user_id = $_SESSION['user_id'];
$schedule_id = intval($_POST['schedule_id'] ?? 0);
$seat_id = intval($_POST['seat_id'] ?? 0);
$passenger_name = trim($_POST['passenger_name'] ?? '');
$passenger_age = intval($_POST['passenger_age'] ?? 0);

if (!$schedule_id || !$seat_id || !$passenger_name) {
  die('Missing data.');
}

// Begin transaction
$mysqli->begin_transaction();

try {
  // Lock seat row and check availability
  $stmt = $mysqli->prepare("SELECT status FROM seats WHERE id = ? FOR UPDATE");
  $stmt->bind_param('i', $seat_id);
  $stmt->execute();
  $res = $stmt->get_result();
  $row = $res->fetch_assoc();
  if (!$row) throw new Exception('Seat not found');
  if ($row['status'] === 'booked') throw new Exception('Seat already booked');

  // Insert booking
  $ins = $mysqli->prepare("INSERT INTO bookings (user_id, schedule_id, seat_id, passenger_name, passenger_age) VALUES (?,?,?,?,?)");
  $ins->bind_param('iiisi', $user_id, $schedule_id, $seat_id, $passenger_name, $passenger_age);
  $ins->execute();

  // Mark seat as booked
  $up = $mysqli->prepare("UPDATE seats SET status='booked' WHERE id = ?");
  $up->bind_param('i',$seat_id);
  $up->execute();

  $mysqli->commit();
  echo "<p>Booking successful! Booking ID: " . $ins->insert_id . "</p>";
  echo '<p><a href="index.php">Back to home</a></p>';

} catch (Exception $e) {
  $mysqli->rollback();
  echo "<p>Booking failed: " . htmlspecialchars($e->getMessage()) . "</p>";
  echo '<p><a href="seats.php?schedule_id='.$schedule_id.'">Back</a></p>';
}
require_once 'includes/footer.php';
