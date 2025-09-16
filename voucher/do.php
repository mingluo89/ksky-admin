<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;

switch ($_POST['action']) {
  case 'add':
    $name = mysqli_escape_string($connect, $_POST['name']);
    $code = strtoupper(mysqli_escape_string($connect, $_POST['code']));
    $discount_value = mysqli_escape_string($connect, $_POST['discount_value']);
    $discount_object = mysqli_escape_string($connect, $_POST['discount_object']);
    $discount_cap = mysqli_escape_string($connect, $_POST['discount_cap']);
    $stock = mysqli_escape_string($connect, $_POST['stock']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "INSERT INTO vouchers (name,code,status,discount_value,discount_object,discount_cap,stock,start_date,end_date) VALUES ('$name','$code','ACTIVE','$discount_value','$discount_object','$discount_cap','$stock','$start_date','$end_date')";
    if (mysqli_query($connect, $sql)) {
      $id = mysqli_insert_id($connect);
      header("Location: ./?view=detail&id=" . $id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }

    mysqli_close($connect);
    break;

  case 'edit':
    $id = mysqli_escape_string($connect, $_POST['id']);
    $name = mysqli_escape_string($connect, $_POST['name']);
    $code = strtoupper(mysqli_escape_string($connect, $_POST['code']));
    $discount_value = mysqli_escape_string($connect, $_POST['discount_value']);
    $discount_object = mysqli_escape_string($connect, $_POST['discount_object']);
    $discount_cap = mysqli_escape_string($connect, $_POST['discount_cap']);
    $stock = mysqli_escape_string($connect, $_POST['stock']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "UPDATE vouchers SET name='$name',code='$code',status='ACTIVE',discount_value='$discount_value',discount_object='$discount_object',discount_cap='$discount_cap',stock='$stock',start_date='$start_date',end_date='$end_date' WHERE id = '$id'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./?view=detail&id=" . $id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }

    mysqli_close($connect);
    break;

  case 'delete':
    $id = mysqli_escape_string($connect, $_POST['id']);

    $sql = "DELETE FROM vouchers WHERE id = '" . $id . "'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./");
    } else {
      echo "Error: " . mysqli_error($connect);
    }

    mysqli_close($connect);
    break;
}
