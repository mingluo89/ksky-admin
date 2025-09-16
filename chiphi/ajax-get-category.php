<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;

$sql_category = "SELECT DISTINCT category FROM chiphi ORDER BY category";
$res_category = mysqli_query($connect, $sql_category);
$suggestions = [];
while ($row_category = mysqli_fetch_assoc($res_category)) {
    $value = trim($row_category['category']);
    if (!empty($value)) {
        $suggestions[] = $value;
    }
}

echo json_encode($suggestions);
