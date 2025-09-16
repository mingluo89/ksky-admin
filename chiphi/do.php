<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;

function getQuarterEndDate($startDate)
{
  $date = new DateTime($startDate);
  $date->modify('+3 months')->modify('-1 day');
  return $date->format('Y-m-d');
}

switch ($_POST['action']) {
  case 'add-chiphi':
    $db_result = mysqli_query($connect, "SELECT DATABASE()");
    $db_row = mysqli_fetch_row($db_result);
    $current_db = $db_row[0];
    $res_next_id = mysqli_query($connect, "
    SELECT AUTO_INCREMENT 
    FROM information_schema.TABLES 
    WHERE TABLE_SCHEMA = '$current_db' AND TABLE_NAME = 'chiphi'");
    $row_next_id = mysqli_fetch_assoc($res_next_id);
    $next_id = $row_next_id['AUTO_INCREMENT'];
    $ksky_chiphi_id = 'ksky' . str_pad($next_id, 6, '0', STR_PAD_LEFT);

    $accounting_chiphi_id = mysqli_escape_string($connect, $_POST['accounting_chiphi_id']);
    $accounting_date = mysqli_escape_string($connect, $_POST['accounting_date']);
    $company_name = mysqli_escape_string($connect, $_POST['company_name']);

    $total_before_vat =  mysqli_escape_string($connect, $_POST['total_before_vat']);
    $total_after_vat =  mysqli_escape_string($connect, $_POST['total_after_vat']);
    $category = mysqli_escape_string($connect, $_POST['category']);

    $sql = "INSERT INTO chiphi 
    (accounting_chiphi_id,
    ksky_chiphi_id,
    accounting_date,
    company_name,
    total_before_vat,
    total_after_vat,
    category) 
    VALUES ('$accounting_chiphi_id',
    '$ksky_chiphi_id',
    '$accounting_date',
    '$company_name',
    '$total_before_vat',
    '$total_after_vat',
    '$category')";
    if (mysqli_query($connect, $sql)) {
      $chiphi_id = mysqli_insert_id($connect);
      header("Location: ./?view=detail&id=" . $chiphi_id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'edit-chiphi':
    $id = mysqli_escape_string($connect, $_POST['id']);
    $accounting_chiphi_id = mysqli_escape_string($connect, $_POST['accounting_chiphi_id']);
    $accounting_date = mysqli_escape_string($connect, $_POST['accounting_date']);
    $company_name = mysqli_escape_string($connect, $_POST['company_name']);

    $total_before_vat =  mysqli_escape_string($connect, $_POST['total_before_vat']);
    $total_after_vat =  mysqli_escape_string($connect, $_POST['total_after_vat']);

    $category = mysqli_escape_string($connect, $_POST['category']);

    $sql = "UPDATE chiphi 
    SET accounting_chiphi_id='$accounting_chiphi_id',
    accounting_date='$accounting_date',
    company_name='$company_name',
    total_before_vat='$total_before_vat',
    total_after_vat='$total_after_vat',
    category='$category' 
    WHERE id = '$id'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./");
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'delete':
    $id = mysqli_escape_string($connect, $_POST['id']);

    $sql = "DELETE FROM chiphi WHERE id = '" . $id . "'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./");
    } else {
      echo "Error delete chiphi: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;
}
