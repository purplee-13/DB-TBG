<?php
$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath)) {
    echo ".env not found\n";
    exit(1);
}
$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$vars = [];
foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#') continue;
    if (strpos($line, '=') === false) continue;
    [$k,$v] = explode('=', $line, 2);
    $k = trim($k);
    $v = trim($v);
    // remove surrounding quotes
    if ((substr($v,0,1) === '"' && substr($v,-1) === '"') || (substr($v,0,1) === "'" && substr($v,-1) === "'")) {
        $v = substr($v,1,-1);
    }
    $vars[$k] = $v;
}
$driver = $vars['DB_CONNECTION'] ?? 'mysql';
if ($driver !== 'mysql') {
    echo "DB connection is $driver, script supports mysql only\n";
    exit(1);
}
$host = $vars['DB_HOST'] ?? '127.0.0.1';
$port = $vars['DB_PORT'] ?? '3306';
$db = $vars['DB_DATABASE'] ?? '';
$user = $vars['DB_USERNAME'] ?? '';
$pass = $vars['DB_PASSWORD'] ?? '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query("SELECT id, username, name, role, password FROM users LIMIT 100");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) {
        echo "No users found\n";
        exit(0);
    }
    echo "id\tusername\tname\trole\tpassword_hash\n";
    foreach ($rows as $r) {
        echo "{$r['id']}\t{$r['username']}\t{$r['name']}\t{$r['role']}\t{$r['password']}\n";
    }
} catch (PDOException $e) {
    echo "PDO error: " . $e->getMessage() . "\n";
    exit(1);
}
