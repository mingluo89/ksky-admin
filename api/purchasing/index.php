<?php
include __DIR__ . "/../../lib/connect.php";

// Check the connection
if ($connect->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $connect->connect_error]);
    exit;
}

// Handle HTTP GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Fetch records with LEFT JOIN
        $query = 'SELECT t1.log_date, t1.nhap_id, t1.product_id, t2.name, t1.qty, t1.unit, t1.price, t1.total, t1.qty_base, t1.unit_base, t1.price_base, t1.factor, t1.ref_id FROM ops_purchasing_detail t1 LEFT JOIN products t2 ON t1.product_id = t2.id ORDER BY t1.nhap_id';
        $stmt = $connect->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch data as an associative array
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Return results as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database query failed: ' . $e->getMessage()]);
    }
} else {
    // Handle unsupported HTTP methods
    http_response_code(405);
    header('Allow: GET');
    echo json_encode(['error' => 'Method not allowed. Only GET is supported.']);
}

// Close the connection
$connect->close();
