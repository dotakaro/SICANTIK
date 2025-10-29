<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'message' => 'API test working',
    'php_version' => phpversion(),
    'timestamp' => date('Y-m-d H:i:s')
]);
