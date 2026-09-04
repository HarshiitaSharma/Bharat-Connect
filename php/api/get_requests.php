<?php
/**
 * php/api/get_requests.php
 * -------------------------
 * THIS FILE DID NOT EXIST BEFORE — this was the main bug in the project.
 * Both dashboard.php and js/main.js call fetch('php/api/get_requests.php'),
 * but the only working version of this logic lived at php/get_requests.php
 * (no /api/ folder, and note the folder DID contain a similarly-named but
 * empty file: php/api/get_request.php — singular, missing the "s"). That
 * mismatch meant the dashboard's live data fetch was silently failing.
 *
 * This is now the single, canonical endpoint, at the exact path the
 * front-end already expects, backed by the real database.
 */
header('Content-Type: application/json');
session_start();
require __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Authentication failed.']);
    exit;
}

$user_id   = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

if ($user_role === 'admin') {
    // Admins/officials see every citizen's requests.
    $stmt = $pdo->query('SELECT * FROM requests ORDER BY id DESC');
} else {
    // Citizens see only their own.
    $stmt = $pdo->prepare('SELECT * FROM requests WHERE user_id = ? ORDER BY id DESC');
    $stmt->execute([$user_id]);
}

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stats = ['Submitted' => 0, 'InProgress' => 0, 'Resolved' => 0];
$requests = [];

foreach ($rows as $row) {
    // Field names match what dashboard.php / js/main.js already expect,
    // so no front-end changes are needed.
    $requests[] = [
        'id'              => 'req_' . $row['id'],
        'user_id'         => $row['user_id'],
        'user_name'       => $row['user_name'],
        'status'          => $row['status'],
        'date'            => substr($row['created_at'], 0, 10),
        'category'        => $row['category'],
        'location'        => $row['location'],
        'phone'           => $row['phone'],
        'region'          => $row['region'],
        'areaCode'        => $row['area_code'],
        'description'     => $row['description'],
        'person_aadhar'   => $row['person_aadhar'],
        'relative_aadhar' => $row['relative_aadhar'],
    ];

    if ($row['status'] === 'Submitted') {
        $stats['Submitted']++;
    } elseif ($row['status'] === 'In Progress') {
        $stats['InProgress']++;
    } elseif ($row['status'] === 'Resolved') {
        $stats['Resolved']++;
    }
}

echo json_encode(['requests' => $requests, 'stats' => $stats]);
exit;
