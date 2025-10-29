<?php
require_once 'includes/header.php';
$schedule_id = intval($_GET['schedule_id'] ?? 0);
if (!$schedule_id) { echo "<p>Invalid schedule.</p>"; require_once 'includes/footer.php'; exit; }

// fetch schedule and seats
$sql = "SELECT s.*, t.name as train_name FROM schedules s JOIN trains t ON t.id = s.train_id WHERE s.id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $schedule_id);
$stmt->execute();
$res = $stmt->get_result();
$schedule = $res->fetch_assoc();
if (!$schedule) { echo "<p>Schedule not found.</p>"; require_once 'includes/footer.php'; exit; }

// seats
$st = $mysqli->prepare("SELECT * FROM seats WHERE schedule_id = ? ORDER BY id");
$st->bind_param('i',$schedule_id);
$st->execute();
$seats = $st->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<h2>Seats for <?= htmlspecialchars($schedule['train_name']) ?> - <?= date('Y-m-d H:i', strtotime($schedule['depart'])) ?></h2>

<div class="seats-grid">
  <?php foreach($seats as $seat): ?>
    <div class="seat <?= $seat['status'] == 'booked' ? 'booked' : 'avail' ?>">
      <?= htmlspecialchars($seat['seat_no']) ?><br>
      <?= number_format($seat['price'],2) ?>
      <?php if($seat['status'] == 'available'): ?>
        <form method="post" action="book.php" class="inline">
          <input type="hidden" name="seat_id" value="<?= $seat['id'] ?>">
          <input type="hidden" name="schedule_id" value="<?= $schedule_id ?>">
          <?php if(!isset($_SESSION['user_id'])): ?>
            <em>Login to book</em>
          <?php else: ?>
            <input type="text" name="passenger_name" placeholder="Passenger name" required>
            <input type="number" name="passenger_age" placeholder="Age" min="0" required>
            <button type="submit">Book</button>
          <?php endif; ?>
        </form>
      <?php else: ?>
        <span class="small">Booked</span>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
