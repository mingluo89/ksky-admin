<div class="bg-theme row mx-0">
    <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
        <!-- Edit Label Form -->
        <div class="rounded-20 bg-white my-5 p-3">
            <form action="do.php" method="post">
                <input type="hidden" name="action" value="delete-order">
                <input type="hidden" name="order_id" value="<?= $_GET['id']; ?>">

                <label class="my-3 text-14 fw-bold text-danger" for="cat">Bạn thực sự muốn xóa toàn bộ Đơn hàng này?</label>
                <p>Các phần sau sẽ được xóa theo</p>
                <ul>
                    <li>
                        <p>Tất cả sản phẩm trong đơn</p>
                    </li>
                    <li>
                        <p>Tất cả khoản thanh toán</p>
                    </li>
                    <li>
                        <p>Tất cả phiếu nhập kho</p>
                    </li>
                </ul>

                <div class="d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-danger me-2">Xóa đơn</button>
                    <a href="javascript:history.back()" class="btn btn-outline-dark">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>