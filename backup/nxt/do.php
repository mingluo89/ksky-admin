<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;

switch ($_POST['action']) {
  case 'sync':
    include("../lib/header.php");
?>

    <body>
      <div class="container-fluid vh-100">
        <div class="row">
          <?php include('../lib/nav-side.php'); ?>

          <div class="col-12 col-lg-9 col-xl-10 px-0">
            <div class="container-fluid bg-blue-gra vh-100 pb-3" style="overflow:auto;">
              <div class="row">
                <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                  <div class="d-flex align-items-center justify-content-between p-3">
                    <a href="/nxt" class="btn btn-sm">
                      <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                    </a>
                    <div class="d-flex align-items-center">
                      <p class="fw-bold text-14">KẾT QUẢ CẬP NHẬT</p>
                    </div>
                    <div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                  <div class="px-3">
                    <div class="bg-white shadow-gg rounded p-3">
                      <?php
                      $until = mysqli_escape_string($connect, $_POST['until']);

                      function getQuarterEndDate($startDate)
                      {
                        $date = new DateTime($startDate);
                        $date->modify('+3 months')->modify('-1 day');
                        return $date->format('Y-m-d');
                      }

                      // Loop through all products
                      $sql_product = "SELECT * FROM products";
                      $res_product = mysqli_query($connect, $sql_product);
                      while ($row_product = mysqli_fetch_assoc($res_product)) {

                        $dauky_qty = 0;
                        $dauky_value = 0;

                        // Loop through selected periods
                        $sql_period = "SELECT * FROM period WHERE date_start <='$until'";
                        $res_period = mysqli_query($connect, $sql_period);
                        while ($row_period = mysqli_fetch_assoc($res_period)) {
                          $period_start = $row_period['date_start'];
                          $period_end = getQuarterEndDate($period_start);

                          $product_id = $row_product['id'];
                          // If current period is after the product's start period, the calculation at the loop end has done it
                          // if current period is the product's start period, get from table products
                          if ($row_period['date_start'] == $row_product['start_period']) {
                            $dauky_qty = $row_product['start_qty'];
                            $dauky_value = $row_product['start_value'];
                          }

                          $sql_nhap = "SELECT SUM(qty) as quantity, SUM(total_before_vat) as value FROM nhap_detail WHERE product_id = '" . $row_product['id'] . "' AND (accounting_date BETWEEN '$period_start' AND '$period_end')";
                          $res_nhap = mysqli_query($connect, $sql_nhap);
                          $count_nhap = mysqli_num_rows($res_nhap);
                          if ($count_nhap > 0) {
                            while ($row_nhap = mysqli_fetch_assoc($res_nhap)) {
                              if (empty($row_nhap['quantity'])) {
                                $nhap_qty = 0;
                              } else {
                                $nhap_qty = $row_nhap['quantity'];
                              }
                              if (empty($row_nhap['value'])) {
                                $nhap_value = 0;
                              } else {
                                $nhap_value = $row_nhap['value'];
                              }
                            }
                          } else {
                            $nhap_qty = 0;
                            $nhap_value = 0;
                          }
                          $sql_xuat = "SELECT SUM(qty) as quantity FROM xuat_detail WHERE product_id = '" . $row_product['id'] . "' AND (accounting_date BETWEEN '$period_start' AND '$period_end')";
                          $res_xuat = mysqli_query($connect, $sql_xuat);
                          $count_xuat = mysqli_num_rows($res_xuat);
                          if ($count_xuat > 0) {
                            while ($row_xuat = mysqli_fetch_assoc($res_xuat)) {
                              if (empty($row_xuat['quantity'])) {
                                $xuat_qty = 0;
                              } else {
                                $xuat_qty = $row_xuat['quantity'];
                              }
                            }
                          } else {
                            $xuat_qty = 0;
                          }
                          $total_qty = $dauky_qty + $nhap_qty;
                          if ($total_qty > 0) {
                            $price_weighted = round(($dauky_value + $nhap_value) / $total_qty);
                          } else {
                            $price_weighted = 0; // or null, depending on your logic
                          }
                          $xuat_value = $price_weighted * $xuat_qty;

                          $sql_check = "SELECT * FROM nxt WHERE period = '" . $row_period['date_start'] . "' AND product_id = '" . $row_product['id'] . "'";
                          $res_check = mysqli_query($connect, $sql_check);
                          $count_check = mysqli_num_rows($res_check);
                          if ($count_check > 0) {
                            // SQL Update
                            while ($row_check = mysqli_fetch_array($res_check)) {
                              $sql = "UPDATE nxt SET dauky_qty='$dauky_qty',dauky_value='$dauky_value',nhap_qty='$nhap_qty',nhap_value='$nhap_value',xuat_qty='$xuat_qty',xuat_value='$xuat_value',price_weighted='$price_weighted' WHERE id = '" . $row_check['id'] . "'";
                            }
                            if (mysqli_query($connect, $sql)) {
                              echo "<p><span class='text-success fw-bold'>Done</span> Update | Period " . $row_period['date_start'] . " - Product " . $row_product['id'] . "</p>";
                            } else {
                              echo "<p><span class='text-danger fw-bold'>Fail</span> Update | Period " . $row_period['date_start'] . " - Product " . $row_product['id'] . "| Error: " . mysqli_error($connect) . "</p>";
                            }
                          } else {
                            // SQL Insert
                            $sql = "INSERT INTO nxt (period,product_id,dauky_qty,dauky_value,nhap_qty,nhap_value,xuat_qty,xuat_value,price_weighted) VALUES ('$period_start','$product_id','$dauky_qty','$dauky_value','$nhap_qty','$nhap_value','$xuat_qty','$xuat_value','$price_weighted')";
                            if (mysqli_query($connect, $sql)) {
                              echo "<p><span class='text-success fw-bold'>Done</span> Insert | Period " . $row_period['date_start'] . " - Product " . $row_product['id'] . "</p>";
                            } else {
                              echo "<p><span class='text-danger fw-bold'>Fail</span> Insert | Period " . $row_period['date_start'] . " - Product " . $row_product['id'] . "| Error: " . mysqli_error($connect) . "</p>";
                            }
                          }
                          // echo "<pre>".$sql."</pre>";
                          $dauky_qty = $dauky_qty + $nhap_qty - $xuat_qty;
                          if ($dauky_qty == 0) {
                            $dauky_value = 0;
                          } else {
                            $dauky_value = $dauky_value + $nhap_value - $xuat_value;
                          }
                        }
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </body>
<?php
    mysqli_close($connect);
    break;

  case 'sync-one':

    mysqli_close($connect);
    break;
}
