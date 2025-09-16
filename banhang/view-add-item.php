<?php
if (isset($_GET['productid'])) {
    $product_id = $_GET['productid'];
    $product_name = $_GET['productname'];
} else {
    $product_id = "";
    $product_name = "";
}

$sql = "SELECT * FROM orders a LEFT JOIN customers b ON a.customer_id = b.id WHERE a.id ='" . $_GET['orderid'] . "'";
$res = mysqli_query($connect, $sql);
while ($row = mysqli_fetch_array($res)) {
    $customer_group = $row['customer_group_id'];
}
?>
<div class="row">
    <div class="col-12 col-md-6 offset-md-3">
        <div class="d-flex align-items-center justify-content-between py-3">
            <a href="javascript:history.back()" class="btn ms-2"><span class="material-icons text-20">arrow_back_ios</span></a>
            <div class="text-center">
                <p class="text-16">Đơn hàng #<?= $_GET['orderid']; ?></p>
                <p class="fw-bold text-20">Thêm line</p>
            </div>
            <div>
            </div>
        </div>
    </div>
</div>
<div class="rounded-top-30 bg-white vh-100 row mx-0">
    <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
        <div class="rounded shadow-gg bg-white my-3 p-3">
            <form action="do.php" method="post">
                <input type="hidden" name="action" value="add-item">
                <input type="hidden" name="order_id" value="<?= $_GET['orderid']; ?>">
                <input type="hidden" name="product_id" id="product_id" value="<?= $product_id; ?>">

                <label class="form-label my-2 fw-bold" for="product_name">Tên Sản phẩm</label>
                <div class="d-flex align-items-center mb-3">
                    <span class="material-icons text-20 me-3 text-theme">search</span>
                    <input value="<?= $product_name; ?>" class="form-control" id="product_name" name="product_name" placeholder="Tìm tên, mã sản phẩm" type="search" autofocus required>
                </div>

                <div class="text-center">
                    <div id="loader" class="loader" style="display:none;"></div>
                </div>
                <div id="suggestProduct" class="mb-3" style="display:none">
                    <p class="mb-3 fw-bold text-dark">Chọn sản phẩm</p>
                </div>

                <div style="<?= (isset($_GET['productid'])) ? "" : "display:none"; ?>">
                    <div class="row">
                        <div class="col-12">

                            <!-- Chọn nhóm khách -->
                            <label class="form-label my-2 fw-bold" for="price">Chọn giá theo nhóm khách</label>
                            <div id="groupRadio">

                                <?php
                                $price_input_value = 0;

                                $sql2 = "SELECT * FROM customers_group ORDER BY id";
                                $res2 = mysqli_query($connect, $sql2);
                                $count2 = mysqli_num_rows($res2);
                                if ($count2 > 0) {
                                    while ($row2 = mysqli_fetch_assoc($res2)) {
                                        // Auto Select Price
                                        if ($row2['id'] == $customer_group) {
                                            $group_check = "checked";
                                        } else {
                                            $group_check = "";
                                        }
                                        // Finding price
                                        $sql_price = "SELECT * FROM product_price WHERE product_id = '" . $_GET['productid'] . "' AND customer_group_id = '" . $row2['id'] . "'";
                                        $res_price = mysqli_query($connect, $sql_price);
                                        $count_price = mysqli_num_rows($res_price);
                                        if ($count_price == 0) {
                                            $price_text = "Chưa đặt giá";
                                            $price_color = "grey";
                                            $price_value = 0;
                                        } else {
                                            while ($row_price = mysqli_fetch_array($res_price)) {
                                                $price_color = "success";
                                                $price_text = number_format($row_price['price'], 0) . " đ";
                                                $price_value = $row_price['price'];
                                                if ($group_check == "checked") {
                                                    // Prepare Price Input Value
                                                    $price_input_value = $price_value;
                                                }
                                            }
                                        }
                                ?>
                                        <div class="row mb-2">
                                            <div class="col-1 text-center">
                                                <input class="form-check-input" type="radio" name="customer_group_id" id="customer_group_id<?= $row2['id']; ?>" value="<?= $row2['id']; ?>" data-price="<?= $price_value; ?>" <?= $group_check; ?>>
                                            </div>
                                            <div class="col-11">
                                                <label for="customer_group_id<?= $row2['id']; ?>" style="display:inline-block; width: 100% !important;">
                                                    <div class="row">
                                                        <div class="col-4 border-start border-top border-bottom">
                                                            <div class="fw-bold p-1"><?= $row2['name']; ?></div>
                                                        </div>
                                                        <div class="col-8 border bg-grey">
                                                            <div class="fw-bold me-0 ms-0 p-1 text-<?= $price_color; ?>"><?= $price_text; ?></div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>

                            <!-- Chọn option giá -->
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="customer_group_id" id="customer_group_id0" value="other" data-price="0">
                                <label class="form-check-label" for="customer_group_id0">
                                    Tự nhập giá khác
                                </label>
                            </div>
                        </div>

                        <!-- Giá -->
                        <div class="col-6">
                            <label class="form-label my-2 fw-bold" for="price">Giá</label>
                            <div class="d-flex align-items-center mb-3">
                                <span class="material-icons text-20 me-3 text-theme">sell</span>
                                <input type="number" class="form-control" min="0" step="1" name="price" id="price" value="<?= $price_input_value; ?>" required>
                                <p class="fw-bold text-16 ms-3">&#8363;</p>
                            </div>
                        </div>

                        <!-- SL -->
                        <div class="col-6">
                            <label class="form-label my-2 fw-bold" for="qty">Số Lượng</label>
                            <div class="d-flex align-items-center mb-3">
                                <span class="material-icons text-20 me-3 text-theme">123</span>
                                <input type="number" class="form-control" min="0" name="qty" id="qty" required>
                                <!-- <p class="fw-bold text-16 ms-3">cái</p> -->
                            </div>
                        </div>

                        <!-- Thuế -->
                        <div class="col-6">
                            <label class="form-label my-2 fw-bold">Thuế suất</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tax_rate" id="taxRate1" value="0" data-tax="0">
                                <label class="form-check-label" for="taxRate1">
                                    0%
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tax_rate" id="taxRate2" value="5" data-tax="5">
                                <label class="form-check-label" for="taxRate2">
                                    5%
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tax_rate" id="taxRate3" value="8" data-tax="8">
                                <label class="form-check-label" for="taxRate3">
                                    8%
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tax_rate" id="taxRate4" value="10" data-tax="10" checked>
                                <label class="form-check-label" for="taxRate4">
                                    10%
                                </label>
                            </div>
                        </div>

                        <!-- Thành tiền -->
                        <div class="col-6">
                            <label class="form-label my-2 fw-bold">Thành tiền</label>
                            <div class="d-flex align-items-center mb-3">
                                <span class="material-icons text-20 me-3 text-theme">paid</span>
                                <p class="fw-bold text-success"><span id="total">0</span> &#8363;</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <input type="submit" name="submit" class="btn btn-dark mt-4 mb-3" value="Thêm">
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 1s linear infinite;
        /* Safari */
        animation: spin 1s linear infinite;
        margin-left: auto;
        margin-right: auto;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $("#product_name").keyup(function() {
            $.ajax({
                type: "POST",
                url: "post.php",
                data: {
                    action: "product-add",
                    q: $(this).val(),
                    orderid: "<?= $_GET['orderid']; ?>",
                    groupid: "<?= $_GET['groupid']; ?>"
                },
                beforeSend: function() {
                    $("#loader").show();
                    $("#suggestProduct").hide();
                },
                success: function(data) {
                    $("#suggestProduct").html(data);
                    $("#loader").hide();
                    $("#suggestProduct").show();
                }
            });
        });
        $("#qty").keyup(function() {
            qty = $("#qty").val();
            price = $("#price").val();
            tax_rate = $('input[type=radio][name="tax_rate"]:checked').data("tax");
            subtotal = qty * price;
            tax = subtotal * tax_rate / 100;
            total = subtotal + tax;
            var num = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            $("#total").html(num);
        });
        $("#price").keyup(function() {
            qty = $("#qty").val();
            price = $("#price").val();
            tax_rate = $('input[type=radio][name="tax_rate"]:checked').data("tax");
            subtotal = qty * price;
            tax = subtotal * tax_rate / 100;
            total = subtotal + tax;
            var num = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            $("#total").html(num);
        });
        $('input[type=radio][name="tax_rate"]').change(function() {
            qty = $("#qty").val();
            price = $("#price").val();
            tax_rate = $('input[type=radio][name="tax_rate"]:checked').data("tax");
            subtotal = qty * price;
            tax = subtotal * tax_rate / 100;
            total = subtotal + tax;
            var num = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            $("#total").html(num);
        });
        $('input[type=radio][name="customer_group_id"]').change(function() {
            price = $('input[type=radio][name="customer_group_id"]:checked').data("price")
            $("#price").val(price);
            qty = $("#qty").val();
            tax_rate = $('input[type=radio][name="tax_rate"]:checked').data("tax");
            subtotal = qty * price;
            tax = subtotal * tax_rate / 100;
            total = subtotal + tax;
            var num = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            $("#total").html(num);
        });
    });
</script>