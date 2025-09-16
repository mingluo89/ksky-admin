<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;

switch ($_POST['action']) {
  case 'supplier':
    $q = mysqli_escape_string($connect, $_POST['q']);

    $sql = "SELECT * FROM suppliers WHERE name LIKE '%$q%'";
    $res = mysqli_query($connect, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
      while ($row = mysqli_fetch_assoc($res)) {
?>
        <p onclick='updateForm("<?= $row['id']; ?>","<?= $row['name']; ?>")' class='border-bottom pb-1 mb-2 border-white'><?= $row['name']; ?></p>
      <?php
      }
    } else {
      echo "<p>Không có kết quả</p>";
    }

    mysqli_close($connect);
    break;

  case 'product':
    $q = mysqli_escape_string($connect, $_POST['q']);
    $period = mysqli_escape_string($connect, $_POST['period']);
    $month = date('n');
    $year = date('Y');
    $quarterStartMonth = floor(($month - 1) / 3) * 3 + 1;
    $quarterStartDate = date('Y-m-d', strtotime("$year-$quarterStartMonth-01"));

    $sql = "SELECT * FROM products WHERE product_name LIKE '%$q%' OR product_code LIKE '%$q%'";
    $res = mysqli_query($connect, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
      while ($row = mysqli_fetch_assoc($res)) {
        // Find nearest xuat price
        $sql_nxp = "SELECT * FROM xuat_detail WHERE product_id='" . $row['id'] . "' ORDER BY id DESC LIMIT 0,1;";
        $res_nxp = mysqli_query($connect, $sql_nxp);
        $count_nxp = mysqli_num_rows($res_nxp);
        if ($count_nxp > 0) {
          while ($row_nxp = mysqli_fetch_assoc($res_nxp)) {
            $product_price = $row_nxp['price'];
          }
        } else {
          // Could not find nearest xuat price, then find price_weighted in NXT table
          $sql_nxt = "SELECT * FROM nxt WHERE product_id = '" . $row['id'] . "' AND period = '$period'";
          $res_nxt = mysqli_query($connect, $sql_nxt);
          $count_nxt = mysqli_num_rows($res_nxt);
          if ($count_nxt > 0) {
            while ($row_nxt = mysqli_fetch_assoc($res_nxt)) {
              $product_price = $row_nxt['price_weighted'];
            }
          } else {
            // If still not found, set price to 0
            $product_price = 0;
          }
        }

        // Calculate stock based on NXT table
        $sql_stock = "SELECT * FROM nxt WHERE product_id='" . $row['id'] . "' AND period = '$quarterStartDate'";
        $res_stock = mysqli_query($connect, $sql_stock);
        $count_stock = mysqli_num_rows($res_stock);
        if ($count_stock > 0) {
          while ($row_stock = mysqli_fetch_assoc($res_stock)) {
            $product_stock = $row_stock['dauky_qty'] + $row_stock['nhap_qty'] - $row_stock['xuat_qty'];
          }
        } else {
          $product_stock = "Không tìm ra, hãy sync NXT trước.";
        }
      ?>
        <p
          class="suggest-row border-bottom pb-1 mb-2 border-white"
          data-productid="<?= $row['id']; ?>"
          data-price="<?= $product_price; ?>"
          data-unit="<?= $row['unit']; ?>"
          data-code="<?= $row['product_code']; ?>"
          data-name="<?= $row['product_name']; ?>"
          data-stock="<?= $product_stock; ?>"><?= $row['product_name'] . " <b>[" . $row['product_code'] . "]</b> "; ?></p>
      <?php
      }
    } else {
      echo "<p>Không có kết quả</p>";
    }

    mysqli_close($connect);
    break;

  case 'product-ao':
    $q = mysqli_escape_string($connect, $_POST['q']);
    $period = mysqli_escape_string($connect, $_POST['period']);
    $month = date('n');
    $year = date('Y');
    $quarterStartMonth = floor(($month - 1) / 3) * 3 + 1;
    $quarterStartDate = date('Y-m-d', strtotime("$year-$quarterStartMonth-01"));

    $sql = "SELECT * FROM products WHERE product_name LIKE '%$q%' OR product_code LIKE '%$q%'";
    $res = mysqli_query($connect, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
      while ($row = mysqli_fetch_assoc($res)) {
        $sql_nxt = "SELECT * FROM nxt WHERE product_id = '" . $row['id'] . "' AND period = '$period'";
        $res_nxt = mysqli_query($connect, $sql_nxt);
        $count_nxt = mysqli_num_rows($res_nxt);
        if ($count_nxt > 0) {
          while ($row_nxt = mysqli_fetch_assoc($res_nxt)) {
            $price_weighted = $row_nxt['price_weighted'];
          }
        } else {
          $price_weighted = 0;
        }
        $sql_stock = "SELECT * FROM nxt WHERE product_id='" . $row['id'] . "' AND period = '$quarterStartDate'";
        $res_stock = mysqli_query($connect, $sql_stock);
        $count_stock = mysqli_num_rows($res_stock);
        if ($count_stock > 0) {
          while ($row_stock = mysqli_fetch_assoc($res_stock)) {
            $product_stock = $row_stock['dauky_qty'] + $row_stock['nhap_qty'] - $row_stock['xuat_qty'];
          }
        } else {
          $product_stock = "Không tìm ra, hãy sync NXT trước.";
        }
      ?>
        <p
          class="suggest-row border-bottom pb-1 mb-2 border-white"
          data-productid="<?= $row['id']; ?>"
          data-price="<?= $price_weighted; ?>"
          data-unit="<?= $row['unit']; ?>"
          data-code="<?= $row['product_code']; ?>"
          data-name="<?= $row['product_name']; ?>"
          data-stock="<?= $product_stock; ?>"><?= $row['product_name'] . " <b>[" . $row['product_code'] . "]</b> "; ?></p>
<?php
      }
    } else {
      echo "<p>Không có kết quả</p>";
    }

    mysqli_close($connect);
    break;
}
