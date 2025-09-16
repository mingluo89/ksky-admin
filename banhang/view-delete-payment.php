<div class="bg-theme row mx-0">
    <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
        <!-- Edit Label Form -->
        <div class="rounded-20 bg-white my-5 p-3">
            <form action="do.php" method="post">
                <input type="hidden" name="action" value="delete-payment">
                <input type="hidden" name="order_id" value="<?= $_GET['orderid']; ?>">
                <input type="hidden" name="payment_id" value="<?= $_GET['paymentid']; ?>">

                <label class="my-3 text-14 fw-bold text-danger" for="cat">Xóa khoản thanh toán này?</label>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <td>
                                <p class="fw-bold">Số tiền</p>
                            </td>
                            <td>
                                <p class="fw-bold text-success"><?= number_format($_GET['amount'], 0); ?> đ</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="fw-bold">Phương thức</p>
                            </td>
                            <td>
                                <p class=""><?= $_GET['method']; ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-dark me-2">Xóa</button>
                    <a href="javascript:history.back()" class="btn btn-outline-dark">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>