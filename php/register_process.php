<?php
/**
 * register_process.php
 * ---------------------
 * Previously appended new users to $_SESSION['users'], which meant every
 * registered citizen disappeared the moment their session ended. Now
 * inserts into the real users table so accounts persist.
 */
session_start();
require __DIR__ . '/includes/db_connect.php';

$name             = trim($_POST['name'] ?? '');
$email            = trim($_POST['email'] ?? '');
$password         = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($name) || empty($email) || empty($password)) {
    header('Location: /register.html?error=' . urlencode('All fields are required'));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /register.html?error=' . urlencode('Please enter a valid email address'));
    exit;
}

if ($password !== $confirm_password) {
    header('Location: /register.html?error=' . urlencode('Passwords do not match'));
    exit;
}

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    header('Location: /register.html?error=' . urlencode('Email already in use'));
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$insert = $pdo->prepare(
    'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)'
);
// All public self-registrations become citizens. Admin/official accounts
// are provisioned separately (see the seed step in db_connect.php).
$insert->execute([$name, $email, $password_hash, 'citizen']);

header('Location: /login.html?success=' . urlencode('Registration successful! Please login.'));
exit;
