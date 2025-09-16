<div class="row">
    <div class="col-12 col-md-8 offset-md-2 offset-xl-3 col-xl-6">
        <div class="d-flex align-items-center justify-content-between py-3">
            <a href="javascript:history.back()" class="btn"><span class="material-icons text-20 lh-base">arrow_back_ios</span></a>
            <div class="d-flex align-items-center">
                <p class="fw-bold text-20">Tạo mã giảm giá</p>
            </div>
            <div>
            </div>
        </div>
    </div>
</div>
<div class="bg-theme row mx-0 pb-5">
    <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
        <div class="rounded-20 bg-white mb-3 p-3">
            <form action="do.php" method="post">
                <input type="hidden" name="action" value="add">

                <div class="row">
                    <div class="col-4">
                        <div class="d-flex align-items-center mb-4">
                            <span class="material-icons text-20 me-3 text-theme">badge</span>
                            <label class="" for="name">Tên</label>
                        </div>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Tên đối tượng, giá trị, thời gian gợi nhớ" required>
                    </div>
                </div>


                <div class="row">
                    <div class="col-4">
                        <div class="d-flex align-items-center mb-4">
                            <span class="material-icons text-20 me-3 text-theme">abc</span>
                            <label class="" for="code">Mã</label>
                        </div>
                    </div>
                    <div class="col-8">
                        <input type="text" minlength="4" maxlength="12" class="form-control" id="code" name="code" placeholder="4-12 ký tự" inputmode="text" required style="text-transform:uppercase">
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="d-flex align-items-center mb-4">
                            <span class="material-icons text-20 me-3 text-theme">percent</span>
                            <label class="" for="percent_value">Giảm (%)</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <input type="number" step="0.1" min="1" max="100" class="form-control" id="percent_value" name="discount_value" placeholder="1-2 chữ số">
                    </div>
                    <div class="col-4">
                        <select name="discount_object" id="discount_object" class="form-control">
                            <option value="Giá trị sản phẩm">Giá trị sản phẩm</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="d-flex align-items-center mb-4">
                            <span class="material-icons text-20 me-3 text-theme">vertical_align_top</span>
                            <label class="" for="discount_cap">Tối đa</label>
                        </div>
                    </div>
                    <div class="col-8">
                        <input type="number" class="form-control" id="discount_cap" name="discount_cap" placeholder="đ" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-4">
                        <div class="d-flex align-items-center">
                            <span class="material-icons text-20 me-3 text-theme">event</span>
                            <p>Hiệu lực</p>
                        </div>
                    </div>
                    <div class="col-8 col-md-4">
                        <label class="mb-1" for="start_time">Bắt đầu</label>
                        <input type="datetime" class="form-control" id="start_time" name="start_date" value="<?= date("Y-m-d H:i:s"); ?>">
                    </div>
                    <div class="col-8 offset-4 col-md-4 offset-md-0">
                        <label class="mb-1" for="end_time">Kết thúc</label>
                        <input type="datetime" class="form-control" id="end_time" name="end_date" value="<?= date("Y-m-d H:i:s"); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-4">
                        <div class="d-flex align-items-center">
                            <span class="material-icons text-20 me-3 text-theme">warehouse</span>
                            <label class="" for="stock">Số lượng</label>
                        </div>
                    </div>
                    <div class="col-8">
                        <input type="number" class="form-control" id="stock" name="stock" placeholder="Số lượng mã" required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>