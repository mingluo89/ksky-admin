<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;

switch ($_POST['action']) {
  case 'customer-suggest':
    $q = mysqli_escape_string($connect, $_POST['q']);

    $sql = "SELECT * FROM customers WHERE name LIKE '%$q%' ORDER BY name";
    $res = mysqli_query($connect, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
?>
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($res)) {
            ?>
              <tr onclick="updateForm('<?= $row['id']; ?>','<?= $row['name']; ?>')" style="cursor:pointer">
                <td>
                  <p class="text-12 fw-bold mx-3">#<?= $row['id']; ?></p>
                </td>
                <td>
                  <p class="text-wrap fw-bold text-12"><?= $row['name']; ?></p>
                </td>
                <td class="d-none d-md-table-cell">
                  <p class="text-12"><?= $row['address'] . ", " . $row['street']; ?></p>
                  <p class="text-12 fw-bold"><?= $row['district'] . ", " . $row['city']; ?></p>
                </td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
      <?php
    } else {
      echo "<p class='text-center'>Không có kết quả</p>";
    }

    mysqli_close($connect);
    break;

  case 'customer-address':
    $id = mysqli_escape_string($connect, $_POST['id']);

    $sql = "SELECT * FROM customers WHERE id ='$id'";
    $res = mysqli_query($connect, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
      $rows = array();


      while ($row = mysqli_fetch_assoc($res)) {
        //Populate district options into result
        $sql_district = "SELECT DISTINCT district FROM city_district_ward WHERE city ='" . $row['city'] . "'";
        $res_district = mysqli_query($connect, $sql_district);
        $districtoption = "";
        while ($row_district = mysqli_fetch_assoc($res_district)) {
          $districtoption .= "<option value='" . $row_district['district'] . "'>" . $row_district['district'] . "</option>";
        }
        $row['districtoption'] = $districtoption;

        //Populate ward options into result
        $sql_ward = "SELECT DISTINCT ward FROM city_district_ward WHERE district ='" . $row['district'] . "'";
        $res_ward = mysqli_query($connect, $sql_ward);
        $wardoption = "";
        while ($row_ward = mysqli_fetch_assoc($res_ward)) {
          $wardoption .= "<option value='" . $row_ward['ward'] . "'>" . $row_ward['ward'] . "</option>";
        }
        $row['wardoption'] = $wardoption;

        // Populate customer table into result
        $rows[] = $row;
      }

      echo json_encode($rows);
    } else {
      echo "<p>Không có kết quả</p>";
    }

    mysqli_close($connect);
    break;

  case 'product-add':
    $group_id = mysqli_escape_string($connect, $_POST['groupid']);
    $order_id = mysqli_escape_string($connect, $_POST['orderid']);
    $q = mysqli_escape_string($connect, $_POST['q']);

    $sql = "SELECT * FROM products WHERE name LIKE '%$q%' AND cat1 IN('Thành phẩm','Phân phối')";
    $res = mysqli_query($connect, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
      while ($row = mysqli_fetch_assoc($res)) {
      ?>
        <a href="./?view=add-item&orderid=<?= $order_id; ?>&groupid=<?= $group_id; ?>&productid=<?= $row['id']; ?>&productname=<?= $row['name']; ?>">
          <div class="border-bottom pb-1 my-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <?php if (empty($row['img'])) { ?>
                <div class="">
                  <span class="bg-theme rounded-circle py-1 px-2"><?= mb_substr($row['name'], 0, 1); ?></span>
                </div>
              <?php } else { ?>
                <div class="text-center">
                  <div style="height:25px; width: 25px; border-radius: 5px; background-color: #fafbfc; background-image: url('<?= $row['img']; ?>'); background-position: center center; background-size: contain; margin: auto !important;"></div>
                </div>
              <?php } ?>
              <p class='text-12 fw-bold ms-2'><?= $row['name']; ?></p>
            </div>
          </div>
        </a>
      <?php
      }
    } else {
      echo "<p>Không có kết quả</p>";
    }

    mysqli_close($connect);
    break;

  case 'product-edit':
    $item_id = mysqli_escape_string($connect, $_POST['itemid']);
    $order_id = mysqli_escape_string($connect, $_POST['orderid']);
    $group_id = mysqli_escape_string($connect, $_POST['groupid']);
    $q = mysqli_escape_string($connect, $_POST['q']);

    $sql = "SELECT * FROM products WHERE name LIKE '%$q%' AND cat1 IN('Thành phẩm','Phân phối')";
    $res = mysqli_query($connect, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
      while ($row = mysqli_fetch_assoc($res)) {
      ?>
        <a href="./?view=edit-item&itemid=<?= $item_id; ?>&orderid=<?= $order_id; ?>&groupid=<?= $group_id; ?>&productid=<?= $row['id']; ?>&productname=<?= $row['name']; ?>&select=true">
          <p class='border-bottom pb-1 mb-2 border-white'><?= $row['name']; ?></p>
        </a>
<?php
      }
    } else {
      echo "<p>Không có kết quả</p>";
    }

    mysqli_close($connect);
    break;
}
