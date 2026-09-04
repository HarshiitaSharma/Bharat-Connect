<?php
/**
 * db_connect.php
 * ---------------
 * This file was an empty stub in the original prototype — nothing was
 * persisted anywhere, so all users/requests lived only in $_SESSION and
 * vanished on every restart.
 *
 * This version opens a real, persistent database using SQLite (via PDO).
 * SQLite was chosen so the project keeps running with zero setup — no
 * MySQL server to install or configure, just PHP. If you later want to
 * move to MySQL/MariaDB for a production deployment, only this file needs
 * to change: swap the DSN below for something like
 *   new PDO('mysql:host=localhost;dbname=bharat_connect;charset=utf8mb4', $user, $pass)
 * every other file talks to $pdo through plain PDO calls, so nothing else
 * needs to change.
 */

$dbDir = __DIR__ . '/../data';
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0775, true);
}
$dbPath = $dbDir . '/bharat_connect.sqlite';

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('PRAGMA foreign_keys = ON');
} catch (PDOException $e) {
    http_response_code(500);
    die('Database connection failed: ' . $e->getMessage());
}

// --- Schema ---------------------------------------------------------
// CREATE TABLE IF NOT EXISTS makes this safe to run on every request.
$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'citizen',
        created_at TEXT NOT NULL DEFAULT (datetime('now'))
    )
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS requests (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        user_name TEXT NOT NULL,
        category TEXT NOT NULL,
        location TEXT NOT NULL,
        phone TEXT,
        region TEXT,
        area_code TEXT,
        description TEXT,
        person_aadhar TEXT,
        relative_aadhar TEXT,
        status TEXT NOT NULL DEFAULT 'Submitted',
        created_at TEXT NOT NULL DEFAULT (datetime('now')),
        FOREIGN KEY (user_id) REFERENCES users(id)
    )
");

// --- Seed a default admin/official account (only inserts once) ------
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute(['admin@example.com']);
if (!$stmt->fetch()) {
    $seed = $pdo->prepare(
        'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)'
    );
    $seed->execute([
        'Admin Officer',
        'admin@example.com',
        password_hash('admin123', PASSWORD_DEFAULT),
        'admin',
    ]);
}

// NOTE: person_aadhar / relative_aadhar are still stored as plain text.
// Before any real deployment, encrypt these columns at rest (e.g. with
// libsodium) and make sure the whole site is served over HTTPS.
