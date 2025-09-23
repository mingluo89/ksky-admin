<?php
// do.php
// ===== Includes (CHỈNH PATH CHO HỢP DỰ ÁN) =====
require_once __DIR__ . '/../lib/connect.php';
require_once __DIR__ . '/../lib/session.php';

// ===== Output & MySQLi mode =====
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ===== Helpers =====
function json_ok($data = [])
{
  echo json_encode(['success' => true] + $data);
  exit;
}
function json_err($msg, $extra = [])
{
  echo json_encode(['success' => false, 'error' => $msg] + $extra);
  exit;
}
function log_error($message)
{
  $f = __DIR__ . '/../logs/sync_nxt_error.log';
  @file_put_contents($f, "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL, FILE_APPEND);
}

/**
 * quarterRange('2025Q3') -> ['2025-07-01', '2025-09-30']
 */
function quarterRange($periodLabel)
{
  $y = (int)substr($periodLabel, 0, 4);
  $q = (int)substr($periodLabel, 5, 1);
  $startMonth = [1 => 1, 2 => 4, 3 => 7, 4 => 10][$q] ?? 1;
  $start = new DateTime(sprintf('%04d-%02d-01', $y, $startMonth));
  $end   = (clone $start)->modify('+3 months')->modify('-1 day');
  return [$start->format('Y-m-d'), $end->format('Y-m-d')];
}

/**
 * periodsTo('2025Q3') -> ['2019Q1', ... , '2025Q3']
 * (Nếu có bảng period, thay bằng SELECT ORDER BY)
 */
function periodsTo($period_to)
{
  $res = [];
  for ($y = 2025; $y <= 2030; $y++) {
    for ($q = 1; $q <= 4; $q++) {
      $p = sprintf('%04dQ%d', $y, $q);
      $res[] = $p;
      if ($p === $period_to) return $res;
    }
  }
  return $res;
}

// ===== Router =====
try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Use POST');
  if (!isset($connect) || !$connect instanceof mysqli) json_err('DB connection missing');

  $action = $_POST['action'] ?? '';

  // ---------- 1) INIT ----------
  if ($action === 'sync_init') {
    $period_to = $_POST['period_to'] ?? '';
    if (!preg_match('/^\d{4}Q[1-4]$/', $period_to)) json_err('period_to invalid');

    $row = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) AS c FROM products"));
    $total_products = (int)$row['c'];

    $_SESSION['sync_nxt_periods'] = periodsTo($period_to);

    json_ok([
      'total_products' => $total_products,
      'periods_count'  => count($_SESSION['sync_nxt_periods'] ?? []),
    ]);
  }

  // ---------- 2) STEP ----------
  if ($action === 'sync_step') {
    $period_to = $_POST['period_to'] ?? '';
    $offset    = max(0, (int)($_POST['offset'] ?? 0));
    $limit     = max(1, (int)($_POST['limit']  ?? 50));
    if (!preg_match('/^\d{4}Q[1-4]$/', $period_to)) json_err('period_to invalid');

    // Periods theo thứ tự tăng dần
    $periods = $_SESSION['sync_nxt_periods'] ?? periodsTo($period_to);
    if (!$periods) json_err('No periods');

    // Lấy batch sản phẩm
    $sqlP = "SELECT id FROM products ORDER BY id ASC LIMIT ?,?";
    $stmtP = mysqli_prepare($connect, $sqlP);
    mysqli_stmt_bind_param($stmtP, 'ii', $offset, $limit);
    mysqli_stmt_execute($stmtP);
    $resP = mysqli_stmt_get_result($stmtP);

    $product_ids = [];
    while ($r = mysqli_fetch_assoc($resP)) $product_ids[] = (int)$r['id'];
    $batch_count = count($product_ids);
    if ($batch_count === 0) json_ok(['batch_count' => 0, 'inserted' => 0, 'updated' => 0, 'errors' => 0]);

    // Prepared upsert
    // period trong DB là DATE -> bind 'YYYY-MM-DD' (ngày đầu quý)
    $upsert = mysqli_prepare($connect, "
      INSERT INTO nxt (
        product_id, period,
        dauky_qty, dauky_value,
        nhap_qty,  nhap_value,
        xuat_qty,  xuat_value,
        price_weighted
      )
      VALUES (?,?,?,?,?,?,?,?,?)
      ON DUPLICATE KEY UPDATE
        dauky_qty      = VALUES(dauky_qty),
        dauky_value    = VALUES(dauky_value),
        nhap_qty       = VALUES(nhap_qty),
        nhap_value     = VALUES(nhap_value),
        xuat_qty       = VALUES(xuat_qty),
        xuat_value     = VALUES(xuat_value),
        price_weighted = VALUES(price_weighted)
    ");
    // types cho bind_param:
    // product_id(i), period(s),
    // dauky_qty(i), dauky_value(s),
    // nhap_qty(i),  nhap_value(s),
    // xuat_qty(i),  xuat_value(s),
    // price_weighted(i)
    mysqli_stmt_bind_param(
      $upsert,
      'isisisisi',
      $pid,
      $per_db,
      $dauky_qty,
      $dauky_value_str,
      $nhap_qty,
      $nhap_value_str,
      $xuat_qty,
      $xuat_value_str,
      $price_weighted
    );

    $inserted = 0;
    $updated = 0;
    $errors = 0;
    $last_error = null;

    // Transaction theo batch
    mysqli_begin_transaction($connect);
    try {
      foreach ($product_ids as $pid) {
        // LẤY mốc đầu kỳ của sản phẩm (DATE) + tồn đầu
        $stmtS = mysqli_prepare($connect, "SELECT start_period, start_qty, start_value FROM products WHERE id=?");
        mysqli_stmt_bind_param($stmtS, 'i', $pid);
        mysqli_stmt_execute($stmtS);
        $rsS = mysqli_stmt_get_result($stmtS);
        $rowS = mysqli_fetch_assoc($rsS) ?: ['start_period' => null, 'start_qty' => 0, 'start_value' => 0];
        $prod_start_period = $rowS['start_period'] ? (string)$rowS['start_period'] : null; // 'YYYY-MM-DD'
        $prod_start_qty    = (int)$rowS['start_qty'];
        $prod_start_value  = (int)$rowS['start_value'];

        // Đầu kỳ của KỲ ĐẦU TIÊN
        $dauky_qty   = 0;
        $dauky_value = '0'; // DECIMAL(20,0) -> giữ dạng string khi bind

        foreach ($periods as $per_label) {
          // 1) Range quý & period DATE để lưu
          [$start, $end] = quarterRange($per_label);
          $per_db = $start; // LƯU period = NGÀY ĐẦU QUÝ (DATE)

          // *** OVERRIDE ĐẦU KỲ THEO MỐC SẢN PHẨM ***
          // Nếu kỳ hiện tại trùng đúng start_period của sản phẩm → đầu kỳ = start_qty/value
          if ($prod_start_period !== null && $per_db === $prod_start_period) {
            $dauky_qty   = $prod_start_qty;
            $dauky_value = (string)$prod_start_value;
          }
          // (Nếu không trùng, giữ nguyên dauky_* từ kỳ trước như đang có)

          // 2) Tổng nhập trong quý
          $sqlN = "SELECT COALESCE(SUM(qty),0) q, COALESCE(SUM(total_before_vat),0) v
                   FROM nhap_detail
                   WHERE product_id=? AND accounting_date BETWEEN ? AND ?";
          $stmtN = mysqli_prepare($connect, $sqlN);
          mysqli_stmt_bind_param($stmtN, 'iss', $pid, $start, $end);
          mysqli_stmt_execute($stmtN);
          $rN   = mysqli_stmt_get_result($stmtN);
          $rowN = mysqli_fetch_assoc($rN) ?: ['q' => 0, 'v' => 0];
          $nhap_qty   = (int)$rowN['q'];
          $nhap_value = (string)$rowN['v'];  // DECIMAL string

          // 3) Tổng xuất (qty) trong quý
          $sqlX = "SELECT COALESCE(SUM(qty),0) q
                   FROM xuat_detail
                   WHERE product_id=? AND accounting_date BETWEEN ? AND ?";
          $stmtX = mysqli_prepare($connect, $sqlX);
          mysqli_stmt_bind_param($stmtX, 'iss', $pid, $start, $end);
          mysqli_stmt_execute($stmtX);
          $rX   = mysqli_stmt_get_result($stmtX);
          $rowX = mysqli_fetch_assoc($rX) ?: ['q' => 0];
          $xuat_qty = (int)$rowX['q'];

          // 4) Tính WAC & xuat_value
          // price_weighted = round((dauky_value + nhap_value) / (dauky_qty + nhap_qty))
          // Tránh float: round(a/b) = intdiv(a + intdiv(b,2), b)
          $dv = (int)$dauky_value;     // đầu kỳ value
          $nv = (int)$nhap_value;      // nhập value
          $tq = (int)($dauky_qty + $nhap_qty);

          if ($tq > 0) {
            $sum_value      = $dv + $nv;
            $price_weighted = intdiv($sum_value + intdiv($tq, 2), $tq); // round
          } else {
            $price_weighted = 0;
          }

          $_xuat_value_int = $price_weighted * $xuat_qty; // integer
          $xuat_value      = (string)$_xuat_value_int;     // bind DECIMAL như string

          // 5) Tính đầu kỳ cho KỲ SAU theo rule
          $next_dauky_qty = $dauky_qty + $nhap_qty - $xuat_qty;
          if ($next_dauky_qty == 0) {
            $next_dauky_value = '0';
          } else {
            $next_dauky_value = (string)($dv + $nv - $_xuat_value_int);
          }

          // 6) Upsert bản ghi kỳ hiện tại
          $dauky_value_str = (string)$dauky_value;
          $nhap_value_str  = (string)$nhap_value;
          $xuat_value_str  = (string)$xuat_value;

          try {
            mysqli_stmt_execute($upsert);
            $affected = mysqli_affected_rows($connect);
            if ($affected === 1) $inserted++;
            else $updated++;
          } catch (Throwable $ex) {
            $errors++;
            $last_error = "Upsert pid=$pid per=$per_db: " . $ex->getMessage();
            log_error($last_error);
          }

          // 7) Chuyển trạng thái cho kỳ tiếp theo
          $dauky_qty   = $next_dauky_qty;
          $dauky_value = $next_dauky_value; // string DECIMAL
        }
      }

      mysqli_commit($connect);
    } catch (Throwable $e) {
      mysqli_rollback($connect);
      $errors++;
      $last_error = "Batch rollback at offset=$offset: " . $e->getMessage();
      log_error($last_error);
    }

    json_ok([
      'batch_count' => $batch_count,
      'inserted'    => $inserted,
      'updated'     => $updated,
      'errors'      => $errors,
      'last_error'  => $last_error
    ]);
  }

  // ---------- Fallback ----------
  json_err('Unknown action');
} catch (Throwable $e) {
  log_error($e->getMessage());
  json_err($e->getMessage());
}
