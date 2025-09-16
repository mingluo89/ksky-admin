<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;

$sql_supplier = "SELECT DISTINCT company_name FROM xuat ORDER BY company_name";
$res_supplier = mysqli_query($connect, $sql_supplier);
$suggestions = [];
while ($row_supplier = mysqli_fetch_assoc($res_supplier)) {
    $value = trim($row_supplier['company_name']);
    if (!empty($value)) {
        $suggestions[] = $value;
    }
}

echo json_encode($suggestions);
