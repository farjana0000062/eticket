<?php
require_once 'includes/header.php';

// fetch stations for dropdown
$stmt = $mysqli->query("SELECT id, name, code FROM stations ORDER BY name");
$stations = $stmt->fetch_all(MYSQLI_ASSOC);
?>
<h1>Find Trains</h1>
<form action="results.php" method="get" class="search-form">
  <label>From
    <select name="from" required>
      <option value="">Select</option>
      <?php foreach($stations as $s): ?>
        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?> (<?= $s['code'] ?>)</option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>To
    <select name="to" required>
      <option value="">Select</option>
      <?php foreach($stations as $s): ?>
        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Date <input type="date" name="date" required></label>
  <button type="submit">Search</button>
</form>
<?php require_once 'includes/footer.php'; ?>
