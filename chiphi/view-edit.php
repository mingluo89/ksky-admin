<?php
$sql1 = "SELECT * FROM chiphi WHERE id ='" . $_GET['id'] . "'";
$res1 = mysqli_query($connect, $sql1);
$count1 = mysqli_num_rows($res1);
if ($count1 > 0) {
    while ($row1 = mysqli_fetch_assoc($res1)) {
?>
        <div class="container-fluid bg-blue-gra vh-100">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                    <div class="d-flex align-items-center justify-content-between p-3">
                        <a href="javascript:history.back()" class="btn btn-sm">
                            <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                        </a>
                        <div class="d-flex align-items-center">
                            <p class="fw-bold text-14">SỬA CHI PHÍ</p>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                    <!-- Add Form -->
                    <div class="px-3">
                        <div class="bg-white shadow-gg rounded p-3">
                            <form action="/chiphi/do.php" method="post">
                                <input type="hidden" name="action" value="edit-chiphi">
                                <input type="hidden" name="id" value="<?= $row1['id']; ?>" required>

                                <!-- Số Hoá Đơn -->
                                <label class="form-label mb-2" for="log_time">Số hoá đơn VAT Chi phí</label>
                                <div class="d-flex align-items-center mb-4">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">checkbook</span>
                                    <input type="text" class="form-control" id="accountingId" name="accounting_chiphi_id" value="<?= $row1['accounting_chiphi_id']; ?>" placeholder="Số hoá đơn VAT" required>
                                </div>

                                <!-- Ngày hoá đơn -->
                                <label class="form-label mb-2" for="log_time">Ngày hoá đơn</label>
                                <div class="d-flex align-items-center mb-4">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">event_note</span>
                                    <input type="date" class="form-control" id="accountingDate" name="accounting_date" value="<?= $row1['accounting_date']; ?>" required>
                                </div>

                                <!-- Tên Công Ty -->
                                <label class="form-label mb-2" for="supplier_name">Tên công ty</label>
                                <div class="d-flex align-items-center mb-4">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">business</span>
                                    <textarea class="form-control" id="companyInput" name="company_name" placeholder="Nhập để tìm" type="text" list="suppliers" required><?= $row1['company_name']; ?></textarea>
                                    <div id="suggestions" class="list-group" style="display:none; position:absolute; z-index:999;"></div>
                                </div>

                                <!-- Thành tiền trước VAT -->
                                <label class="form-label mb-2" for="total_before_vat">Thành tiền trước VAT</label>
                                <div class="d-flex align-items-center mb-4">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">functions</span>
                                    <input type="number" class="form-control" id="total_before_vat" name="total_before_vat" value="<?= $row1['total_before_vat']; ?>" min="0" placeholder="" required>
                                </div>

                                <!-- Thành tiền sau VAT -->
                                <label class="form-label mb-2" for="total_after_vat">Thành tiền sau VAT</label>
                                <div class="d-flex align-items-center mb-4">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">functions</span>
                                    <input type="number" class="form-control" id="total_after_vat" name="total_after_vat" value="<?= $row1['total_after_vat']; ?>" min="0" placeholder="" required>
                                </div>

                                <!-- Mục -->
                                <label class="form-label mb-2" for="category">Mục</label>
                                <div class="d-flex align-items-center mb-4">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">category</span>
                                    <input type="text" class="form-control" id="category" name="category" value="<?= $row1['category']; ?>" placeholder="" required>
                                    <div id="categorySuggestion" class="list-group" style="display:none; position:absolute; z-index:999;"></div>
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
            let suggestionList = [];

            $(document).ready(function() {
                // Fetch suggestion list from database via AJAX
                $.getJSON('ajax-get-company.php', function(data) {
                    suggestionList = data;
                });
                $("#companyInput").on("input", function() {
                    const inputVal = $(this).val().split(/\s+/).pop();
                    const $suggestions = $("#suggestions");

                    if (inputVal.length === 0) {
                        $suggestions.hide();
                        return;
                    }

                    function removeAccents(str) {
                        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                    }

                    const filtered = suggestionList.filter(item =>
                        item && removeAccents(item).includes(removeAccents(inputVal))
                    );

                    if (filtered.length === 0) {
                        $suggestions.hide();
                        return;
                    }

                    const offset = $(this).offset();
                    const height = $(this).outerHeight();
                    $suggestions.css({
                        top: offset.top + height,
                        left: offset.left,
                        width: $(this).outerWidth()
                    });

                    $suggestions.empty();
                    filtered.forEach(item => {
                        $suggestions.append(`<a class="list-group-item list-group-item-action">${item}</a>`);
                    });
                    $suggestions.show();
                });

                $("#suggestions").on("click", ".list-group-item", function() {
                    const selected = $(this).text();
                    let words = $("#companyInput").val().split(/\s+/);
                    words.pop();
                    words.push(selected);
                    $("#companyInput").val(words.join(" ") + " ");
                    $("#suggestions").hide();
                });

                $(document).on("click", function(e) {
                    if (!$(e.target).closest("#companyInput, #suggestions").length) {
                        $("#suggestions").hide();
                    }
                });
            });
        </script>

        <script>
            let suggestionCategoryList = [];

            $(document).ready(function() {
                // Fetch suggestion list from database via AJAX
                $.getJSON('ajax-get-category.php', function(data) {
                    suggestionCategoryList = data;
                });
                $("#category").on("input", function() {
                    const inputVal = $(this).val().split(/\s+/).pop();
                    const $categorySuggestion = $("#categorySuggestion");

                    if (inputVal.length === 0) {
                        $categorySuggestion.hide();
                        return;
                    }

                    function removeAccents(str) {
                        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                    }

                    const filtered = suggestionCategoryList.filter(item =>
                        item && removeAccents(item).includes(removeAccents(inputVal))
                    );

                    if (filtered.length === 0) {
                        $categorySuggestion.hide();
                        return;
                    }

                    const offset = $(this).offset();
                    const height = $(this).outerHeight();
                    $categorySuggestion.css({
                        top: offset.top + height,
                        left: offset.left,
                        width: $(this).outerWidth()
                    });

                    $categorySuggestion.empty();
                    filtered.forEach(item => {
                        $categorySuggestion.append(`<a class="list-group-item list-group-item-action">${item}</a>`);
                    });
                    $categorySuggestion.show();
                });

                $("#categorySuggestion").on("click", ".list-group-item", function() {
                    const selected = $(this).text();
                    let words = $("#category").val().split(/\s+/);
                    words.pop();
                    words.push(selected);
                    $("#category").val(words.join(" ") + " ");
                    $("#categorySuggestion").hide();
                });

                $(document).on("click", function(e) {
                    if (!$(e.target).closest("#category, #categorySuggestion").length) {
                        $("#categorySuggestion").hide();
                    }
                });
            });
        </script>

    <?php
    }
} else {
    ?>
    <p class="text-center">Không tìm thấy hoá đơn chi phí #<?= $_GET['id']; ?></p>
<?php
}
?>