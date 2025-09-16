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
        $query = 'SELECT * FROM products ORDER BY type,cat1';
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
