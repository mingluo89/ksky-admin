<div class="bg-theme row mx-0">
    <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
        <!-- Edit Label Form -->
        <div class="rounded-20 bg-white my-5 p-3">
            <form action="do.php" method="post">
                <input type="hidden" name="action" value="delete-item">
                <input type="hidden" name="order_id" value="<?= $_GET['orderid']; ?>">
                <input type="hidden" name="item_id" value="<?= $_GET['itemid']; ?>">

                <label class="my-3 text-14 fw-bold text-danger" for="cat">Xóa item này khỏi phiếu mua hàng?</label>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <td>
                                <p class="fw-bold">Tên</p>
                            </td>
                            <td>
                                <p class=""><?= $_GET['name']; ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="fw-bold">Số lượng</p>
                            </td>
                            <td>
                                <p class="fw-bold"><?= $_GET['qty']; ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="fw-bold">Giá</p>
                            </td>
                            <td>
                                <p class=""><?= number_format($_GET['price'], 0); ?> đ</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="fw-bold">Thành tiền</p>
                            </td>
                            <td>
                                <p class="fw-bold text-success"><?= number_format($_GET['qty'] * $_GET['price'], 0); ?> đ</p>
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