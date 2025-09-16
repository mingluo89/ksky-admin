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

    $sql = "SELECT * FROM products WHERE product_name LIKE '%$q%' OR product_code LIKE '%$q%'";
    $res = mysqli_query($connect, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
      while ($row = mysqli_fetch_assoc($res)) {
      ?>
        <p
          class="suggest-row border-bottom pb-1 mb-2 border-white"
          data-productid="<?= $row['id']; ?>"
          data-unit="<?= $row['unit']; ?>"
          data-code="<?= $row['product_code']; ?>"
          data-name="<?= $row['product_name']; ?>"><?= $row['product_name'] . " <b>[" . $row['product_code'] . "]</b> "; ?></p>
<?php
      }
    } else {
      echo "<p>Không có kết quả</p>";
    }

    mysqli_close($connect);
    break;
}
