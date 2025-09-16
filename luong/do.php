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
  case 'add':
    $month =  date("Y-m-01", strtotime(mysqli_escape_string($connect, $_POST['month'])));
    $full_name =  mysqli_escape_string($connect, $_POST['full_name']);
    $cccd =  mysqli_escape_string($connect, $_POST['cccd']);
    $mst =  mysqli_escape_string($connect, $_POST['mst']);
    $title =  mysqli_escape_string($connect, $_POST['title']);
    $total =  mysqli_escape_string($connect, $_POST['total']);

    $sql = "INSERT INTO luong 
    (month,
    full_name,
    cccd,
    mst,
    title,
    total) 
    VALUES ('$month',
    '$full_name',
    '$cccd',
    '$mst',
    '$title',
    '$total')";
    echo $sql;
    if (mysqli_query($connect, $sql)) {
      $chiphi_id = mysqli_insert_id($connect);
      header("Location: ./?view=detail&id=" . $chiphi_id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'edit':
    $id = mysqli_escape_string($connect, $_POST['id']);

    $month =  date("Y-m-01", strtotime(mysqli_escape_string($connect, $_POST['month'])));
    $full_name =  mysqli_escape_string($connect, $_POST['full_name']);
    $cccd =  mysqli_escape_string($connect, $_POST['cccd']);
    $mst =  mysqli_escape_string($connect, $_POST['mst']);
    $title =  mysqli_escape_string($connect, $_POST['title']);
    $total =  mysqli_escape_string($connect, $_POST['total']);

    $sql = "UPDATE luong 
    SET month='$month',
    full_name='$full_name',
    cccd='$cccd',
    mst='$mst',
    title='$title',
    total='$total' 
    WHERE id = '$id'";
    echo $sql;
    if (mysqli_query($connect, $sql)) {
      header("Location: ./");
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'delete':
    $id = mysqli_escape_string($connect, $_POST['id']);

    $sql = "DELETE FROM luong WHERE id = '" . $id . "'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./");
    } else {
      echo "Error delete luong: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;
}
