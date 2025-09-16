<?php
header('Content-Type: application/json');

include('../../../lib/connect.php');
include('../../../lib/session.php');

$data = [];

$sql = "SELECT * FROM xuat";
$res = mysqli_query($connect, $sql);
while ($row = mysqli_fetch_assoc($res)) {
    $data[] = $row;
}

echo json_encode($data);
