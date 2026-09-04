<?php
/**
 * submit_request.php
 * -------------------
 * Previously pushed onto $_SESSION['requests']. Now inserts into the
 * real requests table so submissions survive a server restart and are
 * visible to admins from any browser/device, not just the submitter's
 * own session.
 */
header('Content-Type: application/json');
session_start();
require __DIR__ . '/includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Authentication error. Please login again.',
    ]);
    exit;
}

if (empty($_POST['category']) || empty($_POST['location'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: Category and Location are required fields.',
    ]);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO requests
        (user_id, user_name, category, location, phone, region, area_code,
         description, person_aadhar, relative_aadhar, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Submitted')
");

$stmt->execute([
    $_SESSION['user_id'],
    $_SESSION['user_name'],
    $_POST['category'],
    $_POST['location'],
    $_POST['phone'] ?? '',
    $_POST['region'] ?? '',
    $_POST['areaCode'] ?? '',
    $_POST['description'] ?? '',
    $_POST['person_aadhar'] ?? '',
    $_POST['relative_aadhar'] ?? '',
]);

echo json_encode([
    'success' => true,
    'message' => 'Request submitted successfully!',
]);
exit;
