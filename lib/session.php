<?php
if (!isset($_SESSION)) {
	ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
	ini_set('session.gc-maxlifetime', 60 * 60 * 24 * 7);
	session_start();
}
if (isset($_SESSION['in_phone'])) {
	$in_id = $_SESSION['in_id'];
	$in_phone = $_SESSION['in_phone'];
	$in_name = $_SESSION['in_name'];
}
