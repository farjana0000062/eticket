<?php
// public/includes/header.php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/db.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>eTicket Clone</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
  <div class="container">
    <a href="index.php" class="brand">Railway</a>
    <nav>
      <a href="index.php">Home</a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
