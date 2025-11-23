<?php
// src/views/layout/header.php
?><!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Quiz System</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
  <h1>Quiz Management</h1>
  <nav>
    <?php if (!empty($_SESSION['admin'])): ?>
      <a href="?r=admin/dashboard">Dashboard</a> |
      <a href="?r=admin/batches">Batches</a> |
      <a href="?r=admin/techs">Techs</a> |
      <a href="?r=admin/questions">Questions</a> |
      <a href="?r=admin/results">Results</a> |
      <a href="?r=auth/logout">Logout</a>
    <?php else: ?>
      <a href="?r=auth/login">Admin Login</a> |
      <a href="?r=guest/email">Guest Exam</a>
    <?php endif; ?>
  </nav>
  <hr>
</header>
<main>
