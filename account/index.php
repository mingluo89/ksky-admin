<?php
include __DIR__ . '/../lib/session.php';
if (isset($_SESSION['in_phone'])) {
  include __DIR__ . '/../lib/connect.php';
  include __DIR__ . '/../lib/header.php';
  if (isset($_GET['action'])) {
    switch ($_GET['action']) {
      case 'change-pass':
        include('./view-change-pass.php');
        break;

      default:
        include('./view-account.php');
        break;
    }
  } else {
    include('./view-account.php');
  }
} else {
  header('Location: /login');
}
