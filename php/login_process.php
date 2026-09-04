<?php
/**
 * login_process.php
 * ------------------
 * Previously checked a hardcoded admin array plus a $_SESSION['users']
 * list that only existed for the current session. Now checks the real
 * users table (both citizens and the seeded admin account live there).
 */
session_start();
require __DIR__ . '/includes/db_connect.php';

$email     = trim($_POST['email'] ?? '');
$password  = $_POST['password'] ?? '';
$user_type = $_POST['user_type'] ?? 'citizen';

$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// A citizen can't log in through the "Official / Admin" radio button
// and vice versa, even if they somehow know the right password.
if ($user && $user['role'] !== $user_type) {
    $user = null;
}

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);

    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];

    header('Location: /dashboard.php');
    exit;
}

header('Location: /login.html?error=' . urlencode('Invalid email or password'));
exit;
