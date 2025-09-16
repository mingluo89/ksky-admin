<?php
function splitOrder($orderLines, $maxTotal = 10000, $maxOrders = 5)
{
    $splitOrders = [];
    $remainingLines = $orderLines;

    while (!empty($remainingLines) && count($splitOrders) < $maxOrders) {
        $currentOrder = [];
        $currentTotal = 0;

        // Randomly determine a target total for this order (e.g., 50%-90% of maxTotal)
        $randomMaxTotal = rand(floor($maxTotal * 0.5), floor($maxTotal * 0.9));

        // Shuffle the remaining lines for randomization
        shuffle($remainingLines);

        foreach ($remainingLines as $index => $line) {
            $productPrice = $line['price'];
            $remainingQuantity = $line['quantity'];

            // Calculate the maximum quantity that can be added without exceeding the random target total
            $maxAddableQuantity = floor(($randomMaxTotal - $currentTotal) / $productPrice);

            if ($maxAddableQuantity > 0) {
                // Add as much quantity as possible (up to the remaining quantity)
                $toAdd = min($maxAddableQuantity, $remainingQuantity);
                $currentOrder[] = [
                    'product_id' => $line['product_id'],
                    'price' => $productPrice,
                    'quantity' => $toAdd,
                    'total' => $toAdd * $productPrice
                ];
                $currentTotal += $toAdd * $productPrice;

                // Reduce the remaining quantity of this product
                $remainingLines[$index]['quantity'] -= $toAdd;

                // Remove the product if all its quantity is used
                if ($remainingLines[$index]['quantity'] <= 0) {
                    unset($remainingLines[$index]);
                }
            }

            // Stop adding to the current order if the random total is reached
            if ($currentTotal >= $randomMaxTotal) {
                break;
            }
        }

        // Add the current order to the split orders
        if (!empty($currentOrder)) {
            $splitOrders[] = $currentOrder;
        }
    }

    // If there are leftover lines but the maximum number of orders is reached,
    // distribute the remaining items evenly across the split orders
    if (!empty($remainingLines) && count($splitOrders) >= $maxOrders) {
        foreach ($remainingLines as $line) {
            foreach ($splitOrders as &$order) {
                $remainingQuantity = $line['quantity'];
                $canAdd = floor(($maxTotal - array_sum(array_column($order, 'total'))) / $line['price']);

                if ($canAdd > 0) {
                    $toAdd = min($canAdd, $remainingQuantity);
                    $order[] = [
                        'product_id' => $line['product_id'],
                        'price' => $line['price'],
                        'quantity' => $toAdd,
                        'total' => $toAdd * $line['price']
                    ];
                    $remainingQuantity -= $toAdd;
                    if ($remainingQuantity <= 0) {
                        break;
                    }
                }
            }
        }
    }

    return $splitOrders;
}

// Example order
$orderLines = [
    ['product_id' => "Nui", 'price' => 2293, 'quantity' => 10, 'total' => 22930],
    ['product_id' => "Bánh gấu", 'price' => 1572, 'quantity' => 30, 'total' => 47160],
    ['product_id' => "Gạo Tấm Thơm", 'price' => 491, 'quantity' => 60, 'total' => 29460],
    ['product_id' => "Bánh mè", 'price' => 1200, 'quantity' => 60, 'total' => 72000],
    ['product_id' => "Kẹo dẻo", 'price' => 875, 'quantity' => 60, 'total' => 52500],
    ['product_id' => "Mứt", 'price' => 983, 'quantity' => 60, 'total' => 58980],
];
if (isset($_GET['sodon'])) {
    $maxsodon = $_GET['sodon'];
} else {
    $maxsodon = 15;
}
if (isset($_GET['motdon'])) {
    $max1don = $_GET['motdon'];
} else {
    $max1don = 30000;
}
// Split the order with a max total of $10,000 per order and a max of 4 split orders
$splitOrders = splitOrder($orderLines, $max1don, $maxsodon);
echo '<pre>';
print_r($splitOrders);
echo '</pre>';
?>

<?php

include("../lib/session.php");
if (isset($_SESSION['in_phone'])) {
    include("../lib/connect.php");
    include("../lib/header.php");
?>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                    <?php
                    $totalorg = array_sum(array_column($orderLines, 'total'));
                    ?>
                    <form action="./" method="get">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Số đơn tối đa</label>
                            <input class="form-control" type="number" name="sodon" placeholder="Mặc định 15" value="<?= $maxsodon; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Giá trị tối đa 1 đơn</label>
                            <input class="form-control" type="number" name="motdon" placeholder="Mặc định 30,000" value="<?= $max1don; ?>">
                        </div>
                        <input class="btn btn-sm btn-primary" type="submit" value="Chia">

                    </form>
                    <hr>

                    <p class="mb-2"><b>Order Gốc:</b> Tổng tiền <span class="btn btn-sm btn-danger fw-bold text-12"><?= number_format($totalorg, 0); ?></span> chia tối đa <span class="btn btn-sm btn-dark fw-bold text-12"><?= $maxsodon; ?></span> đơn, mỗi đơn dưới <span class="btn btn-sm btn-dark fw-bold text-12"><?= number_format($max1don, 0); ?></span></p>
                    <table class="table table-hovered table-bordered">
                        <thead>
                            <th>
                                <p>ID</p>
                            </th>
                            <th>
                                <p>Product ID</p>
                            </th>
                            <th>
                                <p>SL</p>
                            </th>
                            <th>
                                <p>Giá</p>
                            </th>
                            <th>
                                <p>Tổng</p>
                            </th>
                        </thead>
                        <tbody>
                            <?php foreach ($orderLines as $stt => $hang) {
                            ?>
                                <tr>
                                    <td><?= $stt + 1; ?></td>
                                    <td><?= $hang['product_id']; ?></td>
                                    <td><?= $hang['quantity']; ?></td>
                                    <td><?= number_format($hang['price'], 0); ?></td>
                                    <td><?= number_format($hang['total'], 0); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    // Output the split orders
                    foreach ($splitOrders as $index => $order) {
                        $totalamount = array_sum(array_column($order, 'total'));
                    ?>

                        <p class="mb-2"><b>Order <?= ($index + 1); ?>:</b> Tổng tiền <span class="btn btn-sm btn-success fw-bold text-12"><?= number_format($totalamount, 0); ?></span></p>
                        <table class="table table-hovered table-bordered">
                            <thead>
                                <th>
                                    <p>ID</p>
                                </th>
                                <th>
                                    <p>Product ID</p>
                                </th>
                                <th>
                                    <p>SL</p>
                                </th>
                                <th>
                                    <p>Giá</p>
                                </th>
                                <th>
                                    <p>Tổng</p>
                                </th>
                            </thead>
                            <tbody>
                                <?php foreach ($order as $id => $row) {
                                ?>
                                    <tr>
                                        <td><?= $id + 1; ?></td>
                                        <td><?= $row['product_id']; ?></td>
                                        <td><?= $row['quantity']; ?></td>
                                        <td><?= number_format($row['price'], 0); ?></td>
                                        <td><?= number_format($row['total'], 0); ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php
                        echo "\n";
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
<?php
}
