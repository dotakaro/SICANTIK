<?php
// Simple API wrapper for SICANTIK
define('BASEPATH', TRUE);
require_once('/var/www/html/backoffice/www/config/database.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conn = new mysqli(
    $db['default']['hostname'],
    $db['default']['username'],
    $db['default']['password'],
    $db['default']['database']
);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

switch ($endpoint) {
    case 'listpermohonanterbit':
        $query = "SELECT
            a.pendaftaran_id,
            c.n_pemohon,
            i.n_perizinan,
            tmsk.no_surat,
            a.d_terima_berkas
        FROM
            tmpermohonan AS a
            INNER JOIN tmpemohon_tmpermohonan AS b ON a.id = b.tmpermohonan_id
            INNER JOIN tmpemohon AS c ON c.id = b.tmpemohon_id
            INNER JOIN tmpermohonan_trperizinan AS h ON h.tmpermohonan_id = a.id
            INNER JOIN trperizinan AS i ON i.id = h.trperizinan_id
            INNER JOIN tmpermohonan_tmsk ON tmpermohonan_tmsk.tmpermohonan_id = a.id
            INNER JOIN tmsk ON tmsk.id = tmpermohonan_tmsk.tmsk_id
        WHERE
            a.c_izin_selesai = 1
        ORDER BY a.d_terima_berkas DESC 
        LIMIT {$offset}, {$limit}";
        
        $result = $conn->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;
        
    case 'jenisperizinanlist':
        $query = "SELECT id, n_perizinan, c_perizinan 
                  FROM trperizinan 
                  ORDER BY n_perizinan 
                  LIMIT {$offset}, {$limit}";
        $result = $conn->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;
        
    case 'jumlahPerizinan':
        $query = "SELECT COUNT(*) as total FROM tmpermohonan WHERE c_izin_selesai = 1";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        echo json_encode($row);
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid endpoint', 'available' => ['listpermohonanterbit', 'jenisperizinanlist', 'jumlahPerizinan']]);
}

$conn->close();
