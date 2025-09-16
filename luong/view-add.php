<div class="container-fluid bg-blue-gra vh-100">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="d-flex align-items-center justify-content-between p-3">
                <a href="javascript:history.back()" class="btn btn-sm">
                    <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                </a>
                <div class="d-flex align-items-center">
                    <p class="fw-bold text-14">TẠO KHOẢN LƯƠNG</p>
                </div>
                <div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <!-- Add Form -->
            <div class="px-3">
                <div class="bg-white shadow-gg rounded p-3">
                    <form action="/luong/do.php" method="post">
                        <input type="hidden" name="action" value="add">

                        <!-- Tháng -->
                        <div class="row mb-3">
                            <div class="col-4 col-md-3 col-form-label">
                                <div class="d-flex align-items-center">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">event_note</span>
                                    <label for="month">Tháng</label>
                                </div>
                            </div>
                            <div class="col-8 col-md-9">
                                <input type="month" class="form-control" id="month" name="month" value="<?= date("Y-m"); ?>" required>
                            </div>
                        </div>

                        <!-- Tên -->
                        <div class="row mb-3">
                            <div class="col-4 col-md-3 col-form-label">
                                <div class="d-flex align-items-center">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">account_circle</span>
                                    <label for="full_name">Tên</label>
                                </div>
                            </div>
                            <div class="col-8 col-md-9">
                                <input type="search" class="form-control" id="full_name" name="full_name" value="" placeholder="" required>
                                <!-- <div id="suggestions" class="list-group" style="display:none; position:absolute; z-index:999;"></div> -->
                                <div class="text-center" style="display:none;">
                                    <div id="loader" class="loader"></div>
                                </div>
                                <div id="suggestProduct" class="bg-light py-1 px-2 rounded-20 mt-2" style="display:none">
                                </div>
                            </div>
                        </div>

                        <!-- CCCD -->
                        <div class="row mb-3" id="cccd_group" style="display:none;">
                            <div class="col-4 col-md-3 col-form-label">
                                <div class="d-flex align-items-center">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">badge</span>
                                    <label for="cccd">CCCD</label>
                                </div>
                            </div>
                            <div class="col-8 col-md-9">
                                <input type="text" class="form-control" id="cccd" name="cccd" value="" minlength="12" maxlength="12" placeholder="" required>
                            </div>
                        </div>

                        <!-- MST -->
                        <div class="row mb-3" id="mst_group" style="display:none;">
                            <div class="col-4 col-md-3 col-form-label">
                                <div class="d-flex align-items-center">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">barcode</span>
                                    <label for="mst">MST</label>
                                </div>
                            </div>
                            <div class="col-8 col-md-9">
                                <input type="number" class="form-control" id="mst" name="mst" value="" min="0" placeholder="" required>
                            </div>
                        </div>

                        <!-- Vị trí -->
                        <div class="row mb-3" id="title_group" style="display:none;">
                            <div class="col-4 col-md-3 col-form-label">
                                <div class="d-flex align-items-center">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">business</span>
                                    <label class="form-label mb-2" for="title">Vị trí</label>
                                </div>
                            </div>
                            <div class="col-8 col-md-9">
                                <input type="text" class="form-control" id="title" name="title" placeholder="" required>
                            </div>
                        </div>

                        <!-- Tổng -->
                        <div class="row mb-3">
                            <div class="col-4 col-md-3 col-form-label">
                                <div class="d-flex align-items-center">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">functions</span>
                                    <label for="total">Tổng</label>
                                </div>
                            </div>
                            <div class="col-8 col-md-9">
                                <input type="number" class="form-control" id="total" name="total" value="" min="0" placeholder="" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-sm btn-dark fw-bold">Tạo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        $("#full_name").keyup(function() {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    action: "employee",
                    q: $(this).val()
                },
                success: function(data) {
                    $("#suggestProduct").html(data)
                    $("#suggestProduct").show();
                }
            });
        });
    });

    $(document).on("click", ".suggest-row", function() {
        const full_name = $(this).data("fullname");
        $("#full_name").val(full_name);
        const cccd = $(this).data("cccd");
        $("#cccd").val(cccd);
        const mst = $(this).data("mst");
        $("#mst").val(mst);
        const title = $(this).data("title");
        $("#title").val(title);

        $("#suggestProduct").hide();
        $("#cccd_group").show();
        $("#mst_group").show();
        $("#title_group").show();
    });
</script>