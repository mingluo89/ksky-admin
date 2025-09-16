<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;
switch ($_GET['action']) {
        case "price-form":
                $id = mysqli_escape_string($connect, $_GET['id']);
                $redirect = mysqli_escape_string($connect, $_GET['redirect']);
?>
                <form action="/products/do.php" method="post">
                        <input type="hidden" name="action" value="edit-batch-product-price">
                        <input type="hidden" name="product_id" value="<?= $id; ?>">
                        <input type="hidden" name="redirect" value="<?= $redirect; ?>">
                        <table class="table">
                                <tbody>
                                        <?php
                                        $sql_group = "SELECT * FROM customers_group ORDER BY id";
                                        $res_group = mysqli_query($connect, $sql_group);
                                        while ($row_group = mysqli_fetch_array($res_group)) {
                                        ?>
                                                <tr class="border-bottom">
                                                        <td class="bg-light">
                                                                <p class="text-14 fw-bold"><?= $row_group['name']; ?></p>
                                                        </td>
                                                        <td>

                                                                <?php
                                                                $sql_price = "SELECT * FROM product_price WHERE product_id = '$id' AND customer_group_id = '" . $row_group['id'] . "'";
                                                                $res_price = mysqli_query($connect, $sql_price);
                                                                $count_price = mysqli_num_rows($res_price);
                                                                if ($count_price == 0) {
                                                                ?>
                                                                        <input type="number" name="pricefor<?= $row_group['id']; ?>" class="form-control" min="0" step="1">
                                                                        <?php
                                                                } else {
                                                                        // Form edit product price
                                                                        while ($row_price = mysqli_fetch_array($res_price)) {
                                                                        ?>
                                                                                <input type="number" name="pricefor<?= $row_group['id']; ?>" class="form-control" min="0" step="1" value="<?= $row_price['price']; ?>">
                                                                <?php
                                                                        }
                                                                }
                                                                ?>

                                                        </td>
                                                </tr>
                                        <?php
                                        }
                                        ?>

                                </tbody>
                        </table>
                        <div class="d-grid">
                                <button type="submit" class="btn btn-dark btn-block">Lưu</button>
                        </div>

                </form>
        <?php
                break;
        case "unit-form":
                $id = mysqli_escape_string($connect, $_GET['id']);
                $redirect = mysqli_escape_string($connect, $_GET['redirect']);
        ?>
                <form action="/products/do.php" method="post">
                        <input type="hidden" name="action" value="edit-product-unit">
                        <input type="hidden" name="product_id" value="<?= $id; ?>">
                        <input type="hidden" name="redirect" value="<?= $redirect; ?>">

                        <?php
                        $arr_unit = ["thùng", "bao", "gói", "hũ", "hộp", "cây", "chai"];
                        foreach ($arr_unit as $key => $value) {
                        ?>
                                <div class="form-check">
                                        <input class="form-check-input" value="<?= $value; ?>" type="radio" name="unit" id="unit<?= $key; ?>">
                                        <label class="form-check-label" for="unit<?= $key; ?>">
                                                <?= $value; ?>
                                        </label>
                                </div>
                        <?php
                        }
                        ?>

                        <div class="d-grid">
                                <button type="submit" class="btn btn-dark btn-block">Lưu</button>
                        </div>

                </form>
<?php
                break;
}
