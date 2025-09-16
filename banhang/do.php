<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;

switch ($_POST['action']) {
  case 'add-order':
    $log_time = date("Y-m-d H:i:s");
    $week = date("Y-\WW", strtotime($log_time));
    $ship_time = $log_time;
    $customer_id = mysqli_escape_string($connect, $_POST['customer_id']);
    $employee_id = $in_id;
    $city = mysqli_escape_string($connect, $_POST['city']);
    $district = mysqli_escape_string($connect, $_POST['district']);
    $ward = mysqli_escape_string($connect, $_POST['ward']);
    $street = mysqli_escape_string($connect, $_POST['street']);
    $address = mysqli_escape_string($connect, $_POST['address']);

    $sql = "INSERT INTO orders (log_time,week,ship_time,status,customer_id,employee_id,subtotal,ship,discount,discount_option,discount_value,total,city,district,ward,street,address) VALUES ('$log_time','$week','$ship_time','WAIT','$customer_id','$employee_id','0','0','0','absolute','0','0','$city','$district','$ward','$street','$address')";
    if (mysqli_query($connect, $sql)) {
      $order_id = mysqli_insert_id($connect);
      header("Location: ./?view=detail&id=" . $order_id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'edit-order':
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);
    $log_time = date("Y-m-d H:i:s");
    $week = date("Y-\WW", strtotime($log_time));
    $ship_time = mysqli_escape_string($connect, $_POST['ship_time']);
    $customer_id = mysqli_escape_string($connect, $_POST['customer_id']);

    $sql = "UPDATE orders SET log_time='$log_time',week='$week',ship_time='$ship_time',customer_id='$customer_id' WHERE id = '$order_id'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./?view=detail&id=" . $order_id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'cancel-order':
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);
    $log_time = date("Y-m-d H:i:s");

    $sql = "UPDATE orders SET status='CANCEL' WHERE id = '$order_id'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./?view=detail&id=" . $order_id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;


  case 'delete-order':
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);

    $sql = "DELETE FROM orders WHERE id = '" . $order_id . "'";
    if (mysqli_query($connect, $sql)) {
      $sql_detail = "DELETE FROM orders_detail WHERE orders_id = '" . $order_id . "'";
      if (mysqli_query($connect, $sql_detail)) {
        $sql_payment = "DELETE FROM orders_payment WHERE orders_id = '" . $order_id . "'";
        if (mysqli_query($connect, $sql_payment)) {
          header("Location: ./");
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


  case 'add-item':
    $log_date = date("Y-m-d");
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);
    $product_id = mysqli_escape_string($connect, $_POST['product_id']);
    $customer_group_id = mysqli_escape_string($connect, $_POST['customer_group_id']);
    $qty = mysqli_escape_string($connect, $_POST['qty']);
    $price = mysqli_escape_string($connect, $_POST['price']);
    $subtotal = $price * $qty;
    $tax_rate = mysqli_escape_string($connect, $_POST['tax_rate']);
    $tax = $subtotal * $tax_rate / 100;
    $total = $subtotal + $tax;

    $sql = "INSERT INTO orders_detail (log_date,orders_id,product_id,qty,price,subtotal,tax_rate,tax,total) VALUES ('$log_date','$order_id','$product_id','$qty','$price','$subtotal','$tax_rate','$tax','$total')";
    if (mysqli_query($connect, $sql)) {
      // Calculate subtotal
      $sql1 = "SELECT SUM(total) as subtotal FROM orders_detail WHERE orders_id = '$order_id'";
      $res1 = mysqli_query($connect, $sql1);
      while ($row1 = mysqli_fetch_array($res1)) {
        $subtotal = $row1['subtotal'];
      }

      // Find ship, discount
      $sql2 = "SELECT ship,discount_option,discount_value FROM orders WHERE id = '$order_id'";
      $res2 = mysqli_query($connect, $sql2);
      while ($row2 = mysqli_fetch_array($res2)) {
        $ship = $row2['ship'];
        $discount_option = $row2['discount_option'];
        $discount_value = $row2['discount_value'];
        if ($discount_option == "absolute") {
          $discount = $discount_value;
        } else if ($discount_option == "percent") {
          $discount = $subtotal * $discount_value / 100;
        }
      }
      $total = $subtotal + $ship - $discount;

      // Update subtotal, ship, discount, total
      $sql3 = "UPDATE orders SET subtotal = '$subtotal',ship='$ship',discount='$discount',total='$total' WHERE id = '$order_id'";
      if (mysqli_query($connect, $sql3)) {
        $sql4 = "SELECT * FROM product_price WHERE product_id = '$product_id' AND customer_group_id = '$customer_group_id'";
        $res4 = mysqli_query($connect, $sql4);
        $count4 = mysqli_num_rows($res4);
        if ($count4 > 0) {
          $sql5 = "UPDATE product_price SET price = '$price' WHERE product_id = '$product_id' AND customer_group_id = '$customer_group_id'";
        } else {
          $sql5 = "INSERT INTO product_price (product_id,customer_group_id,price) VALUES ('$product_id','$customer_group_id','$price')";
        }
        if (mysqli_query($connect, $sql5)) {
          header("Location: ./?view=detail&id=" . $order_id);
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
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);
    $product_id = mysqli_escape_string($connect, $_POST['product_id']);
    $customer_group_id = mysqli_escape_string($connect, $_POST['customer_group_id']);
    $qty = mysqli_escape_string($connect, $_POST['qty']);
    $price = mysqli_escape_string($connect, $_POST['price']);
    $subtotal = $price * $qty;
    $tax_rate = mysqli_escape_string($connect, $_POST['tax_rate']);
    $tax = $subtotal * $tax_rate / 100;
    $total = $subtotal + $tax;

    $sql = "UPDATE orders_detail SET product_id='$product_id',qty='$qty',price='$price',subtotal='$subtotal',tax_rate='$tax_rate',tax='$tax',total='$total' WHERE id = '$item_id'";
    if (mysqli_query($connect, $sql)) {
      // Calculate subtotal
      $sql1 = "SELECT SUM(total) as subtotal FROM orders_detail WHERE orders_id = '$order_id'";
      $res1 = mysqli_query($connect, $sql1);
      while ($row1 = mysqli_fetch_array($res1)) {
        $subtotal = $row1['subtotal'];
      }
      // Find ship, discount
      $sql2 = "SELECT ship,discount_option,discount_value FROM orders WHERE id = '$order_id'";
      $res2 = mysqli_query($connect, $sql2);
      while ($row2 = mysqli_fetch_array($res2)) {
        $ship = $row2['ship'];
        $discount_option = $row2['discount_option'];
        $discount_value = $row2['discount_value'];
        if ($discount_option == "absolute") {
          $discount = $discount_value;
        } else if ($discount_option == "percent") {
          $discount = $subtotal * $discount_value / 100;
        }
      }
      $total = $subtotal + $ship - $discount;

      // Update subtotal, ship, discount, total
      $sql3 = "UPDATE orders SET subtotal = '$subtotal',ship='$ship',discount='$discount',total='$total' WHERE id = '$order_id'";
      if (mysqli_query($connect, $sql3)) {
        $sql4 = "SELECT * FROM product_price WHERE product_id = '$product_id' AND customer_group_id = '$customer_group_id'";
        $res4 = mysqli_query($connect, $sql4);
        $count4 = mysqli_num_rows($res4);
        if ($count4 > 0) {
          $sql5 = "UPDATE product_price SET price = '$price' WHERE product_id = '$product_id' AND customer_group_id = '$customer_group_id'";
        } else {
          $sql5 = "INSERT INTO product_price (product_id,customer_group_id,price) VALUES ('$product_id','$customer_group_id','$price')";
        }
        if (mysqli_query($connect, $sql5)) {
          header("Location: ./?view=detail&id=" . $order_id);
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
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);
    $item_id = mysqli_escape_string($connect, $_POST['item_id']);

    $sql = "DELETE FROM orders_detail WHERE id = '" . $item_id . "'";
    if (mysqli_query($connect, $sql)) {
      // Calculate subtotal
      $sql0 = "SELECT * FROM orders_detail WHERE orders_id = '$order_id'";
      $res0 = mysqli_query($connect, $sql0);
      $count0 = mysqli_num_rows($res0);
      if ($count0 == 0) {
        $subtotal = 0;
      } else {
        $sql1 = "SELECT SUM(total) as subtotal FROM orders_detail WHERE orders_id = '$order_id'";
        $res1 = mysqli_query($connect, $sql1);
        while ($row1 = mysqli_fetch_array($res1)) {
          $subtotal = $row1['subtotal'];
        }
      }

      // Find ship, discount
      $sql2 = "SELECT ship,discount_option,discount_value FROM orders WHERE id = '$order_id'";
      $res2 = mysqli_query($connect, $sql2);
      while ($row2 = mysqli_fetch_array($res2)) {
        $ship = $row2['ship'];
        $discount_option = $row2['discount_option'];
        $discount_value = $row2['discount_value'];
        if ($discount_option == "absolute") {
          $discount = $discount_value;
        } else if ($discount_option == "percent") {
          $discount = $subtotal * $discount_value / 100;
        }
      }
      $total = $subtotal + $ship - $discount;

      // Update subtotal, ship, discount, total
      $sql3 = "UPDATE orders SET subtotal = '$subtotal',ship='$ship',discount='$discount',total='$total' WHERE id = '$order_id'";
      if (mysqli_query($connect, $sql3)) {
        header("Location: ./?view=detail&id=" . $order_id);
      } else {
        echo "Error: " . mysqli_error($connect);
      }
    } else {
      echo "Error: " . mysqli_error($connect);
    }

    mysqli_close($connect);
    break;

  case 'log-donghang':
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);
    $customer_id = mysqli_escape_string($connect, $_POST['customer_id']);
    $hub_id = mysqli_escape_string($connect, $_POST['hub_id']);
    $employee_id = mysqli_escape_string($connect, $_POST['employee_id']);
    $out_time_assign = mysqli_escape_string($connect, $_POST['out_time_assign']);

    // Tạo xuat
    $sql = "INSERT INTO xuat (order_id,status,hub_id,employee_id,out_time_assign) VALUES ('$order_id','PACK','$hub_id','$employee_id','$out_time_assign')";
    if (mysqli_query($connect, $sql)) {
      echo "Xong " . $sql . "<br>";
      $xuat_id = mysqli_insert_id($connect);
      $shipper_id = mysqli_escape_string($connect, $_POST['shipper_id']);
      $ship_time_assign = mysqli_escape_string($connect, $_POST['ship_time_assign']);

      // Chuẩn bị địa chỉ From (query từ hub_id)
      $sql_from = "SELECT * FROM hubs WHERE id ='$hub_id'";
      $res_from = mysqli_query($connect, $sql_from);
      while ($row_from = mysqli_fetch_array($res_from)) {
        $from_city = $row_from['city'];
        $from_district = $row_from['district'];
        $from_ward = $row_from['ward'];
        $from_street = $row_from['street'];
        $from_address = $row_from['address'];
        $from_lat = $row_from['latitude'];
        $from_long = $row_from['longitude'];
      }

      // Chuẩn bị địa chỉ To (query từ customer_id)
      $sql_to = "SELECT * FROM customers WHERE id ='$customer_id'";
      $res_to = mysqli_query($connect, $sql_to);
      while ($row_to = mysqli_fetch_array($res_to)) {
        $to_customer_group_id = $row_to['customer_group_id'];
        $to_city = $row_to['city'];
        $to_district = $row_to['district'];
        $to_ward = $row_to['ward'];
        $to_street = $row_to['street'];
        $to_address = $row_to['address'];
        $to_lat = $row_to['latitude'];
        $to_long = $row_to['longitude'];
      }

      // Tính khoảng cách

      // Tạo Vandon có xuat_id
      $sql1 = "INSERT INTO vandon (
        order_id,
        xuat_id,
        status,
        hub_id,
        shipper_id,
        ship_time_assign,
        from_city,
        from_district,
        from_ward,
        from_street,
        from_address,
        from_lat,
        from_long,
        to_customer_id,
        to_customer_group_id,
        to_city,
        to_district,
        to_ward,
        to_street,
        to_address,
        to_lat,
        to_long
        ) VALUES (
        '$order_id',
        '$xuat_id',
        'OUT',
        '$hub_id',
        '$shipper_id',
        '$ship_time_assign',
        '$from_city',
        '$from_district',
        '$from_ward',
        '$from_street',
        '$from_address',
        '$from_lat',
        '$from_long',
        '$customer_id',
        '$to_customer_group_id',
        '$to_city',
        '$to_district',
        '$to_ward',
        '$to_street',
        '$to_address',
        '$to_lat',
        '$to_long'
        )";
      if (mysqli_query($connect, $sql1)) {
        $vandon_id = mysqli_insert_id($connect);
        // Update orders cột shipper_id
        $sql2 = "UPDATE orders SET shipper_id='$shipper_id' WHERE id = '$order_id'";
        if (mysqli_query($connect, $sql2)) {
          // Upate xuat cột vandon_id
          $sql3 = "UPDATE xuat SET vandon_id='$vandon_id' WHERE id = '$xuat_id'";
          if (mysqli_query($connect, $sql3)) {
            header("Location: ./?view=detail&id=" . $order_id);
          } else {
            echo "Error: " . mysqli_error($connect);
          }
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

  case 'add-payment':
    $log_time = date("Y-m-d H:i:s");
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);
    $amount = mysqli_escape_string($connect, $_POST['amount']);
    $status = "DONE";
    $method = mysqli_escape_string($connect, $_POST['method']);

    if ($method == "Tiền mặt") {
      $sql = "INSERT INTO orders_payment (log_time,orders_id,amount,status,method) VALUES ('$log_time','$order_id','$amount','$status','$method')";
    } else if ($method == "Chuyển khoản") {
      $to_bank = mysqli_escape_string($connect, $_POST['to_bank']);
      $to_account = mysqli_escape_string($connect, $_POST['to_account']);
      $sql = "INSERT INTO orders_payment (log_time,orders_id,amount,status,method,to_bank,to_account) VALUES ('$log_time','$order_id','$amount','$status','$method','$to_bank','$to_account')";
    }

    if (mysqli_query($connect, $sql)) {
      header("Location: ./?view=detail&id=" . $order_id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;


  case 'edit-payment':
    $payment_id = mysqli_escape_string($connect, $_POST['payment_id']);

    $log_time = date("Y-m-d H:i:s");
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);
    $amount = mysqli_escape_string($connect, $_POST['amount']);
    $status = "DONE";
    $method = mysqli_escape_string($connect, $_POST['method']);

    if ($method == "Tiền mặt") {
      $sql = "UPDATE orders_payment SET log_time='$log_time',orders_id='$order_id',amount='$amount',status='$status',method='$method',to_bank=null,to_account=null WHERE id = '$payment_id'";
    } else if ($method == "Chuyển khoản") {
      $to_bank = mysqli_escape_string($connect, $_POST['to_bank']);
      $to_account = mysqli_escape_string($connect, $_POST['to_account']);
      $sql = "UPDATE orders_payment SET log_time='$log_time',orders_id='$order_id',amount='$amount',status='$status',method='$method',to_bank='$to_bank',to_account='$to_account' WHERE id = '$payment_id'";
    }

    if (mysqli_query($connect, $sql)) {
      header("Location: ./?view=detail&id=" . $order_id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;


  case 'delete-payment':
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);
    $payment_id = mysqli_escape_string($connect, $_POST['payment_id']);

    $sql = "DELETE FROM orders_payment WHERE id = '" . $payment_id . "'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./?view=detail&id=" . $order_id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }

    mysqli_close($connect);
    break;

  case 'edit-address':
    $id = mysqli_escape_string($connect, $_POST['id']);
    $city = mysqli_escape_string($connect, $_POST['city']);
    $district = mysqli_escape_string($connect, $_POST['district']);
    $ward = mysqli_escape_string($connect, $_POST['ward']);
    $street = mysqli_escape_string($connect, $_POST['street']);
    $address = mysqli_escape_string($connect, $_POST['address']);

    $sql = "UPDATE orders SET city='$city',district='$district',ward='$ward',street='$street',address='$address' WHERE id = '$id'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./?view=detail&id=" . $id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'ship-discount':
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);

    $subtotal = mysqli_escape_string($connect, $_POST['subtotal']);
    $ship = mysqli_escape_string($connect, $_POST['ship']);
    $discount_option = mysqli_escape_string($connect, $_POST['discount_option']);
    $discount_value = mysqli_escape_string($connect, $_POST['discount_value']);

    if ($discount_option == "absolute") {
      $discount = $discount_value;
      $total = $subtotal + $ship - $discount;
    } else if ($discount_option == "percent") {
      $discount = $subtotal * $discount_value / 100;
      $total = $subtotal + $ship - $discount;
    } else if ($discount_option == "voucher") {
      $discount = $discount_value;
      $total = $subtotal + $ship - $discount;
      $vouchers_id = mysqli_escape_string($connect, $_POST['voucher_id']);
      $vouchers_code = mysqli_escape_string($connect, $_POST['voucher_code']);
      $sql_voucher = "INSERT INTO orders_voucher(orders_id,vouchers_id,value,vouchers_code) VALUES ('$order_id','$vouchers_id','$discount','$vouchers_code')";
      if (mysqli_query($connect, $sql_voucher)) {
      } else {
        echo "Error: " . mysqli_error($connect);
      }
    }

    $sql = "UPDATE orders SET ship='$ship', discount_option='$discount_option', discount_value='$discount_value', discount='$discount', total='$total' WHERE id = '$order_id'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./?view=detail&id=" . $order_id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;


  case 'add-log':
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);

    $log_type = mysqli_escape_string($connect, $_POST['log_type']);
    $status_before = mysqli_escape_string($connect, $_POST['status_before']);
    $status_after = mysqli_escape_string($connect, $_POST['status_after']);
    $log_name = mysqli_escape_string($connect, $_POST['log_name']);
    $employee_id = $in_id;

    // Log type 1,2 mới lấy shipper_id từ input, còn lại lấy từ query
    if ($log_type == "1" || $log_type == "2") {
      $shipper_id = mysqli_escape_string($connect, $_POST['shipper_id']);
    } else {
      // Query bảng orders tìm shipper_id từ order_id
      $sql_shipperid = "SELECT * FROM orders WHERE id = '$order_id'";
      $res_shipperid = mysqli_query($connect, $sql_shipperid);
      while ($row_shipperid = mysqli_fetch_array($res_shipperid)) {
        $shipper_id = $row_shipperid['shipper_id'];
      }
    }

    // Query bảng ops_user tìm shipper_name, phone
    $sql_shipper = "SELECT * FROM ops_user WHERE id = '$shipper_id'";
    $res_shipper = mysqli_query($connect, $sql_shipper);
    while ($row_shipper = mysqli_fetch_array($res_shipper)) {
      $shipper_name = $row_shipper['name'];
      $shipper_phone = $row_shipper['phone'];
    }

    $note = mysqli_escape_string($connect, $_POST['note']);

    // Log type 4,5,6 mới lấy hình
    if ($log_type == "4" || $log_type == "5" || $log_type == "6") {
      $img1 = mysqli_escape_string($connect, $_POST['img1']);
    } else {
      $img1 = null;
    }

    $sql = "INSERT INTO orders_log (order_id,log_type,status_before,status_after,log_name,employee_id,shipper_id,shipper_name,shipper_phone,note,img1) VALUES ('$order_id','$log_type','$status_before','$status_after','$log_name','$employee_id','$shipper_id','$shipper_name','$shipper_phone','$note','$img1')";
    if (mysqli_query($connect, $sql)) {
      $sql1 = "UPDATE orders SET status = '$status_after', shipper_id = '$shipper_id' WHERE id = '$order_id'";
      if (mysqli_query($connect, $sql1)) {
        header("Location: ./?view=detail&id=" . $order_id);
      } else {
        echo "Error: " . mysqli_error($connect);
      }
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;

  case 'edit-ship-time':
    $order_id = mysqli_escape_string($connect, $_POST['order_id']);
    $ship_time = mysqli_escape_string($connect, $_POST['ship_time']);

    $sql = "UPDATE orders SET ship_time='$ship_time' WHERE id = '$order_id'";
    if (mysqli_query($connect, $sql)) {
      header("Location: ./?view=add-item&orderid=" . $order_id);
    } else {
      echo "Error: " . mysqli_error($connect);
    }
    mysqli_close($connect);
    break;
}
