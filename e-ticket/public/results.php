<?php
require_once 'includes/header.php';

$from = intval($_GET['from'] ?? 0);
$to = intval($_GET['to'] ?? 0);
$date = $_GET['date'] ?? '';

if (!$from || !$to || !$date) {
  echo "<p>Invalid search.</p>";
  require_once 'includes/footer.php';
  exit;
}

// match schedules where DATE(depart) = $date
$sql = "SELECT s.*, t.name AS train_name, t.number
        FROM schedules s
        JOIN trains t ON t.id = s.train_id
        WHERE s.station_from_id = ? AND s.station_to_id = ? AND DATE(s.depart) = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('iis', $from, $to, $date);
$stmt->execute();
$res = $stmt->get_result();
$rows = $res->fetch_all(MYSQLI_ASSOC);
?>
<h2>Results for <?= htmlspecialchars($date) ?></h2>
<?php if (empty($rows)): ?>
  <p>No trains found.</p>
<?php else: ?>
  <table class="results">
    <thead><tr><th>Train</th><th>Depart</th><th>Arrive</th><th>Fare</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach($rows as $r):
          $depart = date('Y-m-d H:i', strtotime($r['depart']));
          $arrive = date('Y-m-d H:i', strtotime($r['arrive']));
      ?>
      <tr>
        <td><?= htmlspecialchars($r['train_name']) ?> (<?= htmlspecialchars($r['number']) ?>)</td>
        <td><?= $depart ?></td>
        <td><?= $arrive ?></td>
        <td><?= number_format($r['fare'],2) ?></td>
        <td><a href="seats.php?schedule_id=<?= $r['id'] ?>">Select Seats</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif;

require_once 'includes/footer.php';
