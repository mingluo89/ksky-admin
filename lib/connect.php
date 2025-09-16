<?php
$host = "localhost:3306";
$username = "comkimba66eb_admin";
$password = "QZ9}[_A^nG&b";
$db_name = "ksky_master";
$connect = mysqli_connect($host, $username, $password, $db_name);
mysqli_set_charset($connect, "utf8");
setlocale(LC_MONETARY, 'vn_VN');
date_default_timezone_set("Asia/Bangkok");
