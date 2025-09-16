<?php
$log_type = $_GET['logtype'];
switch ($log_type) {
    case '1':
        $status_before = "WAIT";
        $status_after = "WAIT";
        $log_name = "Gán shipper";
        break;
    case '2':
        $status_before = "WAIT";
        $status_after = "WAIT";
        $log_name = "Đổi shipper";
        break;
    case '3':
        $status_before = "WAIT";
        $status_after = "SHIP";
        $log_name = "Xuất kho";
        break;
    case '4':
        $status_before = "SHIP";
        $status_after = "SHIP";
        $log_name = "Giao thất bại";
        break;
    case '5':
        $status_before = "SHIP";
        $status_after = "COMPLETE";
        $log_name = "Giao thành công";
        break;
    case '6':
        $status_before = "SHIP";
        $status_after = "CANCEL";
        $log_name = "Hủy đơn";
        break;
    case '7':
        $status_before = "WAIT";
        $status_after = "CANCEL";
        $log_name = "Hủy đơn";
        break;
    case '8':
        $status_before = "COMPLETE";
        $status_after = "CANCEL";
        $log_name = "Hủy đơn";
        break;

    default:
        $status_before = "WAIT";
        $status_after = "WAIT";
        $log_name = "Gán shipper";
        break;
}
$sql = "SELECT * FROM orders WHERE id ='" . $_GET['orderid'] . "'";
$res = mysqli_query($connect, $sql);
while ($row = mysqli_fetch_array($res)) {
    $shipper_id = $row['shipper_id'];
}
?>
<div class="row">
    <div class="col-12 col-md-6 offset-md-3">
        <div class="d-flex align-items-center justify-content-between py-3">
            <a href="javascript:history.back()" class="btn ms-2"><span class="material-icons text-20">arrow_back_ios</span></a>
            <div class="text-center">
                <p class="text-16">Đơn hàng #<?= $_GET['orderid']; ?></p>
            </div>
            <div>
            </div>
        </div>
    </div>
</div>
<div class="rounded-top-30 bg-white vh-100 row mx-0">
    <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
        <div class="rounded-20 my-3 p-3">
            <form action="do.php" method="post">
                <input type="hidden" name="action" value="add-log">

                <input type="hidden" name="order_id" value="<?= $_GET['orderid']; ?>">
                <input type="hidden" name="log_type" value="<?= $log_type; ?>">
                <input type="hidden" name="status_before" value="<?= $status_before; ?>">
                <input type="hidden" name="status_after" value="<?= $status_after; ?>">
                <input type="hidden" name="log_name" value="<?= $log_name; ?>">

                <div>
                    <div class="row">
                        <h3 class="fw-bold text-center"><?= $log_name; ?></h3>

                        <?php
                        if ($log_type == "1" || $log_type == "2") {
                        ?>
                            <!-- Chọn shipper -->
                            <div class="col-12">
                                <label class="form-label mb-2">Chọn shipper</label>
                                <?php
                                $sql2 = "SELECT * FROM ops_user ORDER BY name";
                                $res2 = mysqli_query($connect, $sql2);
                                $count2 = mysqli_num_rows($res2);
                                if ($count2 > 0) {
                                    while ($row2 = mysqli_fetch_assoc($res2)) {
                                ?>
                                        <div class="row mb-2">
                                            <div class="col-1 text-center">
                                                <input class="form-check-input" type="radio" name="shipper_id" id="shipper_id<?= $row2['id']; ?>" value="<?= $row2['id']; ?>" <?= ($row2['id'] == $shipper_id) ? "checked" : ""; ?> required>
                                            </div>
                                            <div class="col-11">
                                                <label for="shipper_id<?= $row2['id']; ?>"><?= $row2['name']; ?>
                                                </label>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        <?php } ?>

                        <!-- Note -->
                        <div class="col-12">
                            <label class="form-label mb-2" for="note">Ghi chú</label>
                            <div class="d-flex align-items-center mb-3">
                                <span class="material-icons text-20 me-3 text-theme">note</span>
                                <textarea class="form-control" id="note" name="note" placeholder="" rows="3"></textarea>
                            </div>
                        </div>

                        <?php
                        if ($log_type == "4" || $log_type == "5" || $log_type == "6") {
                        ?>
                            <!-- Image -->
                            <div class="col-12">
                                <p>Upload Image</p>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="d-grid">
                        <input type="submit" name="submit" class="btn btn-dark mt-4 mb-3" value="Gửi">
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
            total = qty * price;
            var num = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            $("#total").html(num);
        });
        $("#price").keyup(function() {
            qty = $("#qty").val();
            price = $("#price").val();
            total = qty * price;
            var num = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            $("#total").html(num);
        });
        $('input[type=radio][name="customer_group_id"]').change(function() {
            price = $('input[type=radio][name="customer_group_id"]:checked').data("price")
            $("#price").val(price);
            qty = $("#qty").val();
            total = qty * price;
            var num = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            $("#total").html(num);
        });
    });
</script>