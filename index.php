<?php
include __DIR__ . '/lib/session.php';

if (isset($_SESSION['in_phone'])) {
    header("Location: /home");
} else {
    header("Location: /login");
}
