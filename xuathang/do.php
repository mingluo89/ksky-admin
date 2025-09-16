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

function update_xuat($connect, $xuat_id)
{
  // Calculate subtotal
  $sql1 = "SELECT SUM(total_before_vat) as total_before_vat FROM xuat_detail WHERE xuat_id = '$xuat_id'";
  $res1 = mysqli_query($connect, $sql1);
  while ($row1 = mysqli_fetch_array($res1)) {
    $total_before_vat = (is_null($row1['total_before_vat'])) ? 0 : $row1['total_before_vat'];
  }

  // Find VAT
  $sql2 = "SELECT vat_rate FROM xuat WHERE id = '$xuat_id'";
  $res2 = mysqli_query($connect, $sql2);
  while ($row2 = mysqli_fetch_array($res2)) {
    $vat_rate = $row2['vat_rate'];
  }
  $vat = $total_before_vat * $vat_rate / 100;
  $total_after_vat = $total_before_vat + $vat;

  // Update subtotal, vat, vat_rate, total
  $sql3 = "UPDATE xuat SET total_before_vat = '$total_before_vat',vat_rate='$vat_rate',vat='$vat',total_after_vat='$total_after_vat' WHERE id = '$xuat_id'";
  if (mysqli_query($connect, $sql3)) {
    return true;
  } else {
    return false;
  }
}

function update_nxt($connect, $product_id)
{
  $result = true;
  // Find product info
  $sql_product = "SELECT * FROM products WHERE id = '$product_id'";
  $res_product = mysqli_query($connect, $sql_product);
  while ($row_product = mysqli_fetch_assoc($res_product)) {
    $dauky_qty = 0;
    $dauky_value = 0;

    // Get the latest period of that product in NXT table
    $sql_until = "SELECT MAX(period) as until FROM nxt WHERE product_id = '$product_id'";
    $res_until = mysqli_query($connect, $sql_until);
    while ($row_until = mysqli_fetch_assoc($res_until)) {
      if (is_null($row_until['until'])) {
        // If do not have any period (means NXT table is empty), do nothing, go to sync
        $result = false;
        echo "NXT không có kỳ nào cho sp này! Bạn nên sync bảng NXT.";
      } else {
        $result *= $result;
        // If found latest periods, loop from 2025Q1 to the latest found
        $until = $row_until['until'];
        $sql_period = "SELECT * FROM period WHERE date_start <='$until'";
        $res_period = mysqli_query($connect, $sql_period);
        while ($row_period = mysqli_fetch_assoc($res_period)) {
          $period_start = $row_period['date_start'];
          $period_end = getQuarterEndDate($period_start);

          // Calculate dauky_qty, dauky_value
          // if current period is the product's start period, get dauky_qty and dauky_value from table products
          // If current period is after the product's start period, the calculation at the loop end has done it
          if ($row_period['date_start'] == $row_product['start_period']) {
            $dauky_qty = $row_product['start_qty'];
            $dauky_value = $row_product['start_value'];
          }

          // Calculate nhap_qty, nhap_value
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

          // Calculate xuat_qty
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

          // Calculate weighted avg price, then xuat_value
          $total_qty = $dauky_qty + $nhap_qty;
          if ($total_qty > 0) {
            $price_weighted = round(($dauky_value + $nhap_value) / $total_qty);
          } else {
            $price_weighted = 0; // or null, depending on your logic
          }
          $xuat_value = $price_weighted * $xuat_qty;

          // Main SQL (Update if exist, else insert)
          $sql_check = "SELECT * FROM nxt WHERE period = '" . $row_period['date_start'] . "' AND product_id = '" . $row_product['id'] . "'";
          $res_check = mysqli_query($connect, $sql_check);
          $count_check = mysqli_num_rows($res_check);
          if ($count_check > 0) {
            // SQL Update
            while ($row_check = mysqli_fetch_array($res_check)) {
              $sql = "UPDATE nxt SET dauky_qty='$dauky_qty',dauky_value='$dauky_value',nhap_qty='$nhap_qty',nhap_value='$nhap_value',xuat_qty='$xuat_qty',xuat_value='$xuat_value',price_weighted='$price_weighted' WHERE id = '" . $row_check['id'] . "'";
            }
            if (mysqli_query($connect, $sql)) {
              $result = true;
              echo "<p><span class='text-success fw-bold'>Done</span> Updated | Period " . $row_period['date_start'] . " - Product " . $row_product['id'] . "</p>";
            } else {
              $result = false;
              echo "<p><span class='text-danger fw-bold'>Fail</span> Updated | Period " . $row_period['date_start'] . " - Product " . $row_product['id'] . "| Error: " . mysqli_error($connect) . "</p>";
            }
          } else {
            // SQL Insert
            $sql = "INSERT INTO nxt (period,product_id,dauky_qty,dauky_value,nhap_qty,nhap_value,xuat_qty,xuat_value,price_weighted) VALUES ('$period_start','$product_id','$dauky_qty','$dauky_value','$nhap_qty','$nhap_value','$xuat_qty','$xuat_value','$price_weighted')";
            if (mysqli_query($connect, $sql)) {
              $result = true;
              echo "<p><span class='text-success fw-bold'>Done</span> Inserted | Period " . $row_period['date_start'] . " - Product " . $row_product['id'] . "</p>";
            } else {
              $result = false;
              echo "<p><span class='text-danger fw-bold'>Fail</span> Inserted | Period " . $row_period['date_start'] . " - Product " . $row_product['id'] . "| Error: " . mysqli_error($connect) . "</p>";
            }
          }

          // Calculate dauky_qty, dauky_value for next loop
          $dauky_qty = $dauky_qty + $nhap_qty - $xuat_qty;
          if ($dauky_qty == 0) {
            $dauky_value = 0;
          } else {
            $dauky_value = $dauky_value + $nhap_value - $xuat_value;
          }
        }
      }
    }
  }
  return $result;
}

switch ($_POST['action']) {
  case 'add-xuat':
    // Step 1: Get current database name from the connection
    $db_result = mysqli_query($connect, "SELECT DATABASE()");
    $db_row = mysqli_fetch_row($db_result);
    $current_db = $db_row[0];

    // Step 2: Get next AUTO_INCREMENT value from xuat table
    $res_next_id = mysqli_query($connect, "
    SELECT AUTO_INCREMENT 
    FROM information_schema.TABLES 
    WHERE TABLE_SCHEMA = '$current_db' AND TABLE_NAME = 'xuat'");
    $row_next_id = mysqli_fetch_assoc($res_next_id);
    $next_id = $row_next_id['AUTO_INCREMENT'];
    $ksky_xuat_id = 'KSKY' . str_pad($next_id, 6, '0', STR_PAD_LEFT);

    $accounting_xuat_id = mysqli_escape_string($connect, $_POST['accounting_xuat_id']);
    $accounting_date = mysqli_escape_string($connect, $_POST['accounting_date']);
    $company_name = mysqli_escape_string($connect, $_POST['company_name']);
    $total_before_vat = 0;
    $vat_rate =  mysqli_escape_string($connect, $_POST['vat_rate']);
    $vat = 0;
    $total_after_vat = 0;

    $sql = "INSERT INTO xuat (accounting_xuat_id,ksky_xuat_id,accounting_date,company_name,total_before_vat,vat_rate,vat,total_after_vat) VALUES ('$accounting_xuat_id','$ksky_xuat_id','$accounting_date','$company_name','$total_before_vat','$vat_rate','$vat','$total_after_vat')";
    echo $sql;
    if (mysqli_query($connect, $sql)) {
      $xuat_id = mysqli_insert_id($connect);
      header("Location: ./?view=detail&id=" . $xuat_id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'edit-xuat':
    $id = mysqli_escape_string($connect, $_POST['xuat_id']);

    $accounting_xuat_id = mysqli_escape_string($connect, $_POST['accounting_xuat_id']);
    $accounting_date = mysqli_escape_string($connect, $_POST['accounting_date']);
    $company_name = mysqli_escape_string($connect, $_POST['company_name']);

    $sql_xuat = "SELECT * FROM xuat WHERE id = '" . $id . "'";
    $res_xuat = mysqli_query($connect, $sql_xuat);
    while ($row_xuat = mysqli_fetch_assoc($res_xuat)) {
      $total_before_vat = $row_xuat['total_before_vat'];
    }
    $vat_rate = mysqli_escape_string($connect, $_POST['vat_rate']);
    $vat = round($total_before_vat * $vat_rate / 100);
    $total_after_vat = $total_before_vat + $vat;

    $sql = "UPDATE xuat 
    SET accounting_xuat_id='$accounting_xuat_id', 
    accounting_date='$accounting_date', 
    company_name='$company_name', 
    total_before_vat='$total_before_vat', 
    vat_rate='$vat_rate', 
    vat='$vat', 
    total_after_vat='$total_after_vat' 
    WHERE id = '$id'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./?view=detail&id=" . $id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'delete-xuat':
    $xuat_id = mysqli_escape_string($connect, $_POST['xuat_id']);

    $sql = "DELETE FROM xuat WHERE id = '" . $xuat_id . "'";
    if (mysqli_query($connect, $sql)) {
      $sql_detail = "DELETE FROM xuat_detail WHERE xuat_id = '" . $xuat_id . "'";
      if (mysqli_query($connect, $sql_detail)) {
        header("Location: ./");
      } else {
        echo "Error delete xuat_detail: " . mysqli_error($connect);
      }
    } else {
      echo "Error delete xuat: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'add-item-real':
    $xuat_id = mysqli_escape_string($connect, $_POST['xuat_id']);
    $sql_xuat = "SELECT * FROM xuat WHERE id = '$xuat_id'";
    $res_xuat = mysqli_query($connect, $sql_xuat);
    while ($row_xuat = mysqli_fetch_array($res_xuat)) {
      $ksky_xuat_id = $row_xuat['ksky_xuat_id'];
      $accounting_date = $row_xuat['accounting_date'];
      $accounting_xuat_id = $row_xuat['accounting_xuat_id'];
    }

    $product_id = mysqli_escape_string($connect, $_POST['product_id']);
    $product_code = mysqli_escape_string($connect, $_POST['product_code']);
    $product_name = mysqli_escape_string($connect, $_POST['product_name']);
    if (empty($_POST['product_name_display'])) {
      $product_name_display = $product_name;
    } else {
      $product_name_display = mysqli_escape_string($connect, $_POST['product_name_display']);
    }

    $unit = mysqli_escape_string($connect, $_POST['unit']);
    $qty = mysqli_escape_string($connect, $_POST['qty']);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $total_before_vat = $price * $qty;

    $is_it = "0";
    $is_it_for = "0";

    $sql = "INSERT INTO xuat_detail 
    (xuat_id,
    ksky_xuat_id,
    accounting_xuat_id,
    accounting_date,
    product_id,
    product_code,
    product_name,
    product_name_display,
    unit,
    qty,
    price,
    total_before_vat,
    is_it,
    is_it_for) 
    VALUES ('$xuat_id',
    '$ksky_xuat_id',
    '$accounting_xuat_id',
    '$accounting_date',
    '$product_id',
    '$product_code',
    '$product_name',
    '$product_name_display',
    '$unit',
    '$qty',
    '$price',
    '$total_before_vat',
    '$is_it',
    '$is_it_for')";
    if (mysqli_query($connect, $sql)) {
      if (update_xuat($connect, $xuat_id)) {
        if (update_nxt($connect, $product_id)) {
          header("Location: ./?view=detail&id=" . $xuat_id);
        } else {
          echo "Error: " . mysqli_error($connect);
        }
      } else {
        echo "Error: " . mysqli_error($connect);
      }
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'add-item-ao':
    $xuat_id = mysqli_escape_string($connect, $_POST['xuat_id']);
    $sql_xuat = "SELECT * FROM xuat WHERE id = '$xuat_id'";
    $res_xuat = mysqli_query($connect, $sql_xuat);
    while ($row_xuat = mysqli_fetch_array($res_xuat)) {
      $ksky_xuat_id = $row_xuat['ksky_xuat_id'];
      $accounting_date = $row_xuat['accounting_date'];
      $accounting_xuat_id = $row_xuat['accounting_xuat_id'];
    }

    $product_id = mysqli_escape_string($connect, $_POST['product_id']);
    $product_code = mysqli_escape_string($connect, $_POST['product_code']);
    $product_name = mysqli_escape_string($connect, $_POST['product_name']);
    if (empty($_POST['product_name_display'])) {
      $product_name_display = $product_name;
    } else {
      $product_name_display = mysqli_escape_string($connect, $_POST['product_name_display']);
    }

    $unit = mysqli_escape_string($connect, $_POST['unit']);
    $qty = mysqli_escape_string($connect, $_POST['qty']);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $total_before_vat = $price * $qty;

    $is_it = "1";
    $is_it_for = mysqli_escape_string($connect, $_POST['is_it_for']);

    $sql = "INSERT INTO xuat_detail 
      (xuat_id,
      ksky_xuat_id,
      accounting_xuat_id,
      accounting_date,
      product_id,
      product_code,
      product_name,
      product_name_display,
      unit,
      qty,
      price,
      total_before_vat,
      is_it,
      is_it_for) 
      VALUES ('$xuat_id',
      '$ksky_xuat_id',
      '$accounting_xuat_id',
      '$accounting_date',
      '$product_id',
      '$product_code',
      '$product_name',
      '$product_name_display',
      '$unit',
      '$qty',
      '$price',
      '$total_before_vat',
      '$is_it',
      '$is_it_for')";
    if (mysqli_query($connect, $sql)) {
      if (update_xuat($connect, $xuat_id)) {
        if (update_nxt($connect, $product_id)) {
          header("Location: ./?view=tinhtoan&detailid=" . $is_it_for);
        } else {
          echo "Error: " . mysqli_error($connect);
        }
      } else {
        echo "Error: " . mysqli_error($connect);
      }
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'edit-item':
    $item_id = mysqli_escape_string($connect, $_POST['item_id']);
    $xuat_id = mysqli_escape_string($connect, $_POST['xuat_id']);
    $product_id = mysqli_escape_string($connect, $_POST['product_id']);

    $qty = mysqli_escape_string($connect, $_POST['qty']);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $total_before_vat = $price * $qty;

    $sql = "UPDATE xuat_detail 
    SET qty='$qty',
    price='$price',
    total_before_vat='$total_before_vat' 
    WHERE id = '$item_id'";

    if (mysqli_query($connect, $sql)) {
      if (update_xuat($connect, $xuat_id)) {
        if (update_nxt($connect, $product_id)) {
          header("Location: ./?view=detail&id=" . $xuat_id);
        } else {
          echo "Error: " . mysqli_error($connect);
        }
      } else {
        echo "Error: " . mysqli_error($connect);
      }
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'delete-item':
    $xuat_id = mysqli_escape_string($connect, $_POST['xuat_id']);
    $item_id = mysqli_escape_string($connect, $_POST['item_id']);
    $product_id = mysqli_escape_string($connect, $_POST['product_id']);

    // Check if this item_id has any is_it_for item in xuat_detail
    $sql_check = "SELECT * FROM xuat_detail WHERE xuat_id = '$xuat_id' AND is_it = '1' AND is_it_for = '$item_id'";
    $res_check = mysqli_query($connect, $sql_check);
    $count_check = mysqli_num_rows($res_check);
    // If has, loop through each item, DELETE xuat_detail by its row id and update_nxt for product_id of that item
    if ($count_check > 0) {
      while ($row_check = mysqli_fetch_array($res_check)) {
        $sql_del = "DELETE FROM xuat_detail WHERE id = '" . $row_check['id'] . "'";
        if (mysqli_query($connect, $sql_del)) {
          echo "Deleted Item #" . $row_check['id'] . "<br>";
          if (update_nxt($connect, $row_check['product_id'])) {
            echo "Updated NXT Product ID #" . $row_check['product_id'] . "<br>";
          } else {
            echo "Error Updated NXT Product ID #" . $row_check['product_id'] . ":" . mysqli_error($connect) . "<br>";
          }
        } else {
          echo "Error Deleted Item #" . $row_check['id'] . ":" . mysqli_error($connect) . "<br>";
        }
      }
    }

    // Finally, delete the item_id, update nxt for product_id, update_xuat
    $sql = "DELETE FROM xuat_detail WHERE id = '" . $item_id . "'";
    echo $sql;
    if (mysqli_query($connect, $sql)) {
      if (update_xuat($connect, $xuat_id)) {
        if (update_nxt($connect, $product_id)) {
          header("Location: ./?view=detail&id=" . $xuat_id);
        } else {
          echo "Error: " . mysqli_error($connect);
        }
      } else {
        echo "Error: " . mysqli_error($connect);
      }
    } else {
      echo "Error: " . mysqli_error($connect);
    }

    mysqli_close($connect);
    break;
}
