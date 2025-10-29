<?php
// Test database connection
define('BASEPATH', TRUE);
require_once('/var/www/html/backoffice/www/config/database.php');

header('Content-Type: application/json');

try {
    $conn = new mysqli(
        $db['default']['hostname'],
        $db['default']['username'],
        $db['default']['password'],
        $db['default']['database']
    );
    
    if ($conn->connect_error) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Connection failed: ' . $conn->connect_error
        ]);
        exit;
    }
    
    // Test query
    $result = $conn->query('SELECT COUNT(*) as total FROM tmpermohonan');
    $row = $result->fetch_assoc();
    
    echo json_encode([
        'status' => 'success',
        'database' => $db['default']['database'],
        'host' => $db['default']['hostname'],
        'total_permohonan' => $row['total']
    ]);
    
    $conn->close();
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
