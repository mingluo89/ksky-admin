<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;

switch ($_GET['action']) {
  case "city-change":
    $q = mysqli_escape_string($connect, $_GET['q']);
    $sql = "SELECT DISTINCT district FROM city_district_ward WHERE city='$q'";
    echo $sql;
    $res = mysqli_query($connect, $sql);
?>
    <option value="" selected>Chọn Quận</option>
    <?php
    while ($row = mysqli_fetch_array($res)) {
    ?>
      <option value="<?php echo $row['district']; ?>"><?php echo $row['district']; ?></option>
    <?php
    }
    break;

  case "district-change":
    $s = mysqli_escape_string($connect, $_GET['s']);
    $sql = "SELECT DISTINCT ward FROM city_district_ward WHERE district='$s'";
    $res = mysqli_query($connect, $sql);
    ?>
    <option value="" selected>Chọn Phường</option>
    <?php
    while ($row = mysqli_fetch_array($res)) {
    ?>
      <option value="<?php echo $row['ward']; ?>"><?php echo $row['ward']; ?></option>
<?php
    }
    break;

  case "voucher-lookup":
    $s = mysqli_escape_string($connect, $_GET['s']);
    $sql = "SELECT * FROM vouchers WHERE id='$s' AND status='ACTIVE' AND DATE_ADD(NOW(), INTERVAL 7 HOUR) BETWEEN start_date AND end_date";
    $res = mysqli_query($connect, $sql);
    $count = mysqli_num_rows($res);
    $response = array();
    if ($count == 0) {
      $response['discount'] = 0;
      $response['message'] = "Mã không hợp lệ";
    } else {
      while ($row = mysqli_fetch_array($res)) {
        $response['discount'] = $row['discount_value'];
        $response['cap'] = $row['discount_cap'];
        $response['message'] = "Đã áp dụng mã. Giảm " . number_format($row['discount_value'], 0) . "% " . $row['discount_object'] . ", tối đa " . number_format($row['discount_cap'], 0) . "đ";
      }
    }
    echo json_encode($response);
    break;
}
