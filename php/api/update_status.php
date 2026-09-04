<?php
/**
 * php/api/update_status.php
 * ---------------------------
 * Was an empty stub — admins had no way to actually move a request from
 * Submitted -> In Progress -> Resolved. Implemented now, admin-only.
 *
 * Expects a POST with:
 *   id     - the request id (accepts either "5" or "req_5")
 *   status - one of "Submitted", "In Progress", "Resolved"
 */
header('Content-Type: application/json');
session_start();
require __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Authentication failed.']);
    exit;
}

if (($_SESSION['user_role'] ?? '') !== 'admin') {
    echo json_encode(['success' => false, 'message' => "Only officials can update a request's status."]);
    exit;
}

$requestId = (string) ($_POST['id'] ?? '');
$newStatus = $_POST['status'] ?? '';

// The front-end refers to requests as "req_<id>" — accept either form.
$requestId = str_replace('req_', '', $requestId);

$allowedStatuses = ['Submitted', 'In Progress', 'Resolved'];
if ($requestId === '' || !ctype_digit($requestId) || !in_array($newStatus, $allowedStatuses, true)) {
    echo json_encode(['success' => false, 'message' => 'Invalid request id or status.']);
    exit;
}

$stmt = $pdo->prepare('UPDATE requests SET status = ? WHERE id = ?');
$stmt->execute([$newStatus, $requestId]);

if ($stmt->rowCount() === 0) {
    echo json_encode(['success' => false, 'message' => 'Request not found.']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
exit;
