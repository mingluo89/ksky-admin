<div class="container-fluid bg-blue-gra vh-100">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="d-flex align-items-center justify-content-between p-3">
                <a href="javascript:history.back()" class="btn btn-sm">
                    <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                </a>
                <div class="d-flex align-items-center">
                    <p class="fw-bold text-14">TẠO ĐƠN XUẤT</p>
                </div>
                <div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <!-- Add Form -->
            <div class="px-3">
                <div class="bg-white shadow-gg rounded p-3">
                    <form action="/xuathang/do.php" method="post">
                        <input type="hidden" name="action" value="add-xuat">

                        <!-- Ngày hoá đơn -->
                        <div class="row mb-3">
                            <div class="col-4 col-md-3 col-form-label">
                                <div class="d-flex align-items-center">
                                    <label class="text-12" for="accountingDate">Ngày hoá đơn</label>
                                </div>
                            </div>
                            <div class="col-8 col-md-9">
                                <input type="date" class="form-control" id="accountingDate" name="accounting_date" value="<?= date("Y-m-d"); ?>" required>
                            </div>
                        </div>

                        <!-- Số Hoá Đơn -->
                        <div class="row mb-3">
                            <div class="col-4 col-md-3 col-form-label">
                                <div class="d-flex align-items-center">
                                    <label class="text-12" for="accountingId">Số hoá đơn VAT</label>
                                </div>
                            </div>
                            <div class="col-8 col-md-9">
                                <input type="text" class="form-control" id="accountingId" name="accounting_xuat_id" value="" placeholder="Số hoá đơn VAT" required>
                            </div>
                        </div>

                        <!-- Tên Công Ty -->
                        <div class="row mb-3">
                            <div class="col-4 col-md-3 col-form-label">
                                <div class="d-flex align-items-center">
                                    <label class="text-12" for="companyInput">Tên công ty</label>
                                </div>
                            </div>
                            <div class="col-8 col-md-9">
                                <textarea class="form-control" id="companyInput" name="company_name" placeholder="Nhập để tìm" type="text" list="suppliers" rows="4" required></textarea>
                                <div id="suggestions" class="list-group" style="display:none; position:absolute; z-index:999;"></div>
                            </div>
                        </div>

                        <!-- %VAT -->
                        <div class="row mb-3">
                            <div class="col-4 col-md-3 col-form-label">
                                <div class="d-flex align-items-center">
                                    <label class="text-12" for="vatRate">%VAT</label>
                                </div>
                            </div>
                            <div class="col-8 col-md-9">
                                <input type="number" class="form-control" id="vatRate" name="vat_rate" value="" min="0" step="0.1" placeholder="chỉ nhập số. VD: VAT 10% nhập 10" required>
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