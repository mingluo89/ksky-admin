<?php
function firstDateOfQuarter(string $period)
{
    [$y, $q] = [substr($period, 0, 4), substr($period, 5, 1)];
    $m = ['1' => '01', '2' => '04', '3' => '07', '4' => '10'][$q] ?? '01';
    return "$y-$m-01";
}
// Lấy quý hiện tại theo giờ VN
$tz   = new DateTimeZone('Asia/Ho_Chi_Minh');
$now  = new DateTime('now', $tz);
$yCur = (int)$now->format('Y');
$mCur = (int)$now->format('n');
$qCur = intdiv($mCur - 1, 3) + 1;              // 1..4
$currentPeriod = sprintf('%04dQ%d', $yCur, $qCur); // ví dụ 2025Q3
?>
<div class="container-fluid bg-blue-gra vh-100">
    <div class="row">

        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="d-flex align-items-center justify-content-between p-3">
                <a href="/nxt" class="btn btn-sm">
                    <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                </a>
                <div class="d-flex align-items-center">
                    <p class="fw-bold text-14">ĐỒNG BỘ NXT</p>
                </div>
                <div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card my-4 shadow-sm">
                <div class="card-body">
                    <form id="sync-form" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Đồng bộ đến Quý</label>
                            <select name="period_to" id="period_to" class="form-select" required>
                                <?php
                                for ($y = 2025; $y <= 2030; $y++) {
                                    for ($q = 1; $q <= 4; $q++) {
                                        $p = "{$y}Q{$q}";
                                        $sel = ($p === $currentPeriod) ? ' selected' : '';
                                        echo "<option value=\"$p\"$sel>$p</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Kích thước mỗi lô (sản phẩm/nhịp)</label>
                            <input type="number" class="form-control" id="chunk_size" value="50" min="10" max="1000">
                        </div>

                        <div class="col-12 d-grid">
                            <button type="submit" class="btn btn-dark fw-bold">Bắt đầu đồng bộ</button>
                        </div>
                    </form>

                    <div class="my-3"></div>

                    <div id="progress-wrap" class="d-none">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Tiến độ</span><span id="progress-text">0%</span>
                        </div>
                        <div class="progress mb-2" style="height:22px;">
                            <div id="progress-bar" class="progress-bar" style="width:0%;">0%</div>
                        </div>

                        <ul class="list-group small mb-3">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Đã xử lý sản phẩm</span><strong id="stat-processed">0</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Rows INSERT</span><strong id="stat-inserted">0</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Rows UPDATE</span><strong id="stat-updated">0</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Lỗi</span><strong id="stat-errors">0</strong>
                            </li>
                        </ul>

                        <pre id="last-error" class="bg-light p-2 border rounded small" style="max-height:160px;overflow:auto;"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const $ = (s) => document.querySelector(s);
    const fmtPct = (num) => `${num.toFixed(1)}%`;

    async function postForm(url, data) {
        const res = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: new URLSearchParams(data)
        });
        const j = await res.json();
        if (!res.ok || j.success === false) {
            throw new Error(j.error || `HTTP ${res.status}`);
        }
        return j;
    }

    document.getElementById('sync-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const period_to = $('#period_to').value;
        const chunk = parseInt($('#chunk_size').value || '50', 10);

        $('#progress-wrap').classList.remove('d-none');
        $('#last-error').textContent = '';
        $('#progress-bar').classList.remove('bg-success', 'bg-danger');

        try {
            // init
            const init = await postForm('do.php', {
                action: 'sync_init',
                period_to
            });
            const total = init.total_products;
            let offset = 0;
            let inserted = 0,
                updated = 0,
                errors = 0,
                processed = 0;

            const tickUI = () => {
                processed = Math.min(processed, total);
                const pct = total > 0 ? (processed * 100.0 / total) : 100;
                $('#progress-text').textContent = fmtPct(pct);
                $('#progress-bar').style.width = pct + '%';
                $('#progress-bar').textContent = fmtPct(pct);
                $('#stat-processed').textContent = processed;
                $('#stat-inserted').textContent = inserted;
                $('#stat-updated').textContent = updated;
                $('#stat-errors').textContent = errors;
            };
            tickUI();

            while (offset < total) {
                const step = await postForm('do.php', {
                    action: 'sync_step',
                    period_to,
                    offset,
                    limit: chunk
                });

                inserted += step.inserted;
                updated += step.updated;
                errors += step.errors;
                processed = Math.min(total, offset + step.batch_count);

                if (step.last_error) $('#last-error').textContent = step.last_error.substring(0, 4000);

                tickUI();
                offset += step.batch_count;
                await new Promise(r => setTimeout(r, 30));
            }

            $('#progress-bar').classList.add('bg-success');
        } catch (err) {
            $('#last-error').textContent = (err && err.message) ? err.message : String(err);
            $('#progress-bar').classList.add('bg-danger');
        }
    });
</script>