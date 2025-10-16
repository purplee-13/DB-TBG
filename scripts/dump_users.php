<?php
require __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/../config/database.php';
$default = $config['default'] ?? 'mysql';
$c = $config['connections'][$default];
$driver = $c['driver'] ?? 'mysql';
if ($driver !== 'mysql') {
    echo "Driver is $driver, script supports mysql only\n";
    exit(1);
}
$host = $c['host'] ?? '127.0.0.1';
$port = $c['port'] ?? 3306;
$db = $c['database'] ?? '';
$user = $c['username'] ?? '';
$pass = $c['password'] ?? '';
$charset = $c['charset'] ?? 'utf8mb4';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query("SELECT id, username, name, role, password FROM users LIMIT 100");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) {
        echo "No users found\n";
        exit(0);
    }
    foreach ($rows as $r) {
        echo "{$r['id']}\t{$r['username']}\t{$r['name']}\t{$r['role']}\t{$r['password']}\n";
    }
} catch (PDOException $e) {
    echo "PDO error: " . $e->getMessage() . "\n";
    exit(1);
}
