<?php
$sql1 = "SELECT * FROM orders WHERE id ='" . $_GET['orderid'] . "'";
$res1 = mysqli_query($connect, $sql1);
$count1 = mysqli_num_rows($res1);
if ($count1 > 0) {
    while ($row1 = mysqli_fetch_assoc($res1)) {
        $remain = $row1['total'];
        $sql_payment = "SELECT * FROM orders_payment WHERE orders_id ='" . $_GET['orderid'] . "'";
        $res_payment = mysqli_query($connect, $sql_payment);
        $count_payment = mysqli_num_rows($res_payment);
        if ($count_payment > 0) {
            while ($row_payment = mysqli_fetch_array($res_payment)) {
                $remain -= $row_payment['amount'];
            }
        }
        if ($remain < 0) {
            $remain = 0;
        }
    }
}
?>
<div class="row">
    <div class="col-12 col-md-6 offset-md-3">
        <div class="d-flex align-items-center justify-content-between py-3">
            <a href="javascript:history.back()" class="btn ms-2"><span class="material-icons text-20">arrow_back_ios</span></a>
            <div class="text-center">
                <p class="text-16">Đơn hàng #<?= $_GET['orderid']; ?></p>
                <p class="fw-bold text-20 ">Tạo khoản thanh toán</p>
            </div>
            <div>
                <a href="#" class="btn"></a>
            </div>
        </div>
    </div>
</div>
<div class="rounded-top-30 bg-white vh-100 row mx-0">
    <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
        <!-- Edit Label Form -->
        <div class="rounded-20 my-3 p-3">
            <form action="do.php" method="post">
                <input type="hidden" name="action" value="add-payment">
                <input type="hidden" name="order_id" value="<?= $_GET['orderid']; ?>">

                <label class="form-label mb-2" for="amount">Số tiền thanh toán</label>
                <div class="d-flex align-items-center mb-4">
                    <span class="material-icons text-20 me-3 text-theme">paid</span>
                    <input type="number" class="form-control" id="amount" name="amount" min="0" max="<?= $remain; ?>" placeholder="Tối đa <?= number_format($remain, 0); ?> đ" required>
                    <p class="fw-bold text-16 ms-3">&#8363;</p>
                </div>

                <label class="form-label mb-2">Phương thức thanh toán</label>
                <div class="d-flex mb-3">
                    <span class="material-icons text-20 me-3 text-theme">payment</span>
                    <div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="method" id="method1" value="Tiền mặt" required>
                            <label class="form-check-label" for="method1">Tiền mặt</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="method" id="method2" value="Chuyển khoản" required>
                            <label class="form-check-label" for="method2">Chuyển khoản</label>
                        </div>
                    </div>
                </div>

                <div id="methodBank" style="display:none;">
                    <!-- To Account -->
                    <label class="form-label mb-2" for="to_bank">Tài khoản đến</label>
                    <div class="d-flex align-items-center mb-4">
                        <span class="material-icons text-20 me-3 text-theme">account_balance</span>
                        <select name="to_bank" id="to_bank" class="form-select me-3">
                            <option disabled selected>Chọn ngân hàng</option>
                            <option value="Vietcombank">Vietcombank</option>
                            <option value="ACB">ACB</option>
                            <option value="Techcombank">Techcombank</option>
                        </select>
                        <input type="number" class="form-control" id="to_account" name="to_account" placeholder="Số tài khoản">
                    </div>

                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark" id="submitBtn" disabled>Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    methods = document.querySelectorAll('input[type=radio][name="method"]');
    methodBank = document.getElementById("methodBank");
    to_bank = document.getElementById("to_bank");
    to_account = document.getElementById("to_account");
    submitBtn = document.getElementById("submitBtn");

    Array.prototype.forEach.call(methods, function(method) {
        method.addEventListener('change', handleMethod);
    });

    function handleMethod(event) {
        if (this.value === "Tiền mặt") {
            methodTienMat();
        } else if (this.value === "Chuyển khoản") {
            methodChuyenKhoan();
        }
        submitBtn.disabled = false;
    }

    function methodTienMat() {
        methodBank.style.display = "none";
        to_bank.required = false;
        to_account.required = false;
    }

    function methodChuyenKhoan() {
        methodBank.style.display = "block";
        to_bank.required = true;
        to_account.required = true;
    }
</script>