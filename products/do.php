<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;

switch ($_POST['action']) {
  case 'add':
    $product_name = mysqli_escape_string($connect, $_POST['product_name']);
    $product_code = mysqli_escape_string($connect, $_POST['product_code']);
    $unit = mysqli_escape_string($connect, $_POST['unit']);
    $start_period = mysqli_escape_string($connect, $_POST['start_period']);
    $start_qty = mysqli_escape_string($connect, $_POST['start_qty']);
    $start_value = mysqli_escape_string($connect, $_POST['start_value']);

    $sql = "INSERT INTO products (product_code,product_name,unit,start_period,start_qty,start_value) VALUES ('$product_code','$product_name','$unit','$start_period','$start_qty','$start_value')";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./");
    } else {
      echo "Error: " . mysqli_error($connect);
    }

    mysqli_close($connect);
    break;

  case 'edit':
    $id = mysqli_escape_string($connect, $_POST['id']);
    $product_name = mysqli_escape_string($connect, $_POST['product_name']);
    $product_code = mysqli_escape_string($connect, $_POST['product_code']);
    $unit = mysqli_escape_string($connect, $_POST['unit']);
    $start_period = mysqli_escape_string($connect, $_POST['start_period']);
    $start_qty = mysqli_escape_string($connect, $_POST['start_qty']);
    $start_value = mysqli_escape_string($connect, $_POST['start_value']);

    $sql = "UPDATE products SET product_code='$product_code',product_name='$product_name',unit='$unit',start_period='$start_period',start_qty='$start_qty',start_value='$start_value' WHERE id = '$id'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./");
    } else {
      echo "Error: " . mysqli_error($connect);
    }

    mysqli_close($connect);
    break;

  case 'delete':
    $id = mysqli_escape_string($connect, $_POST['id']);

    $sql = "DELETE FROM products WHERE id = '" . $id . "'";
    if (mysqli_query($connect, $sql)) {
      $sql_nxt = "DELETE FROM nxt WHERE product_id = '" . $id . "'";
      if (mysqli_query($connect, $sql_nxt)) {
        header("Location: ./");
      } else {
        echo "Error: " . mysqli_error($connect);
      }
    } else {
      echo "Error: " . mysqli_error($connect);
    }

    mysqli_close($connect);
    break;
}
