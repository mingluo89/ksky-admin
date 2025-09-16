<div class="bg-theme row mx-0">
    <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
        <div class="rounded-20 bg-white my-5 p-3">
            <form action="do.php" method="post">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $_GET['id']; ?>">

                <label class="my-3 text-14 fw-bold text-danger" for="cat">Bạn thực sự muốn xóa Mã giảm giá này?</label>
                <p>Các phần sau sẽ <u><b>KHÔNG BỊ XÓA</b></u></p>
                <ul>
                    <li>
                        <p>Tất cả sản phẩm của khách hàng này</p>
                    </li>
                    <li>
                        <p>Tất cả khoản thanh toán của khách hàng này</p>
                    </li>
                    <li>
                        <p>Tất cả phiếu nhập kho của khách hàng này</p>
                    </li>
                </ul>

                <div class="d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-theme me-2">Xóa</button>
                    <a href="javascript:history.back()" class="btn btn-outline-dark">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>