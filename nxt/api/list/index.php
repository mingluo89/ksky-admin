<?php
header('Content-Type: application/json');

include('../../../lib/connect.php');
include('../../../lib/session.php');

$result = [];

$sql = "SELECT * FROM nxt";
$res = mysqli_query($connect, $sql);
while ($row = mysqli_fetch_assoc($res)) {
    $data = [];
    $data['id'] = $row['id'];
    $data['period'] = $row['period'];
    $data['product_id'] = $row['product_id'];
    $sql_product = "SELECT * FROM products WHERE id = '" . $row['product_id'] . "'";
    $res_product = mysqli_query($connect, $sql_product);
    while ($row_product = mysqli_fetch_assoc($res_product)) {
        $data['product_code'] = $row_product['product_code'];
        $data['product_name'] = $row_product['product_name'];
        $data['unit'] = $row_product['unit'];
    }
    $data['dauky_qty'] = $row['dauky_qty'];
    $data['dauky_value'] = $row['dauky_value'];
    $data['nhap_qty'] = $row['nhap_qty'];
    $data['nhap_value'] = $row['nhap_value'];
    $data['xuat_qty'] = $row['xuat_qty'];
    $data['xuat_value'] = $row['xuat_value'];

    $data['cuoiky_qty'] = $row['dauky_qty'] + $row['nhap_qty'] - $row['xuat_qty'];
    $data['cuoiky_value'] = $row['dauky_value'] + $row['nhap_value'] - $row['xuat_value'];
    $data['price_weighted'] = $row['price_weighted'];
    $result[] = $data;
}

echo json_encode($result);
