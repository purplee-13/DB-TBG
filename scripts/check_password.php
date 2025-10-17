<?php
// Usage: php check_password.php master01 Password_123
if ($argc < 3) {
    echo "Usage: php check_password.php <username> <password>\n";
    exit(1);
}
$username = $argv[1];
$password = $argv[2];
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
    if ((substr($v,0,1) === '"' && substr($v,-1) === '"') || (substr($v,0,1) === "'" && substr($v,-1) === "'")) {
        $v = substr($v,1,-1);
    }
    $vars[$k] = $v;
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
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :u LIMIT 1");
    $stmt->execute(['u' => $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        echo "User not found: $username\n";
        exit(1);
    }
    $hash = $row['password'];
    if (password_verify($password, $hash)) {
        echo "Password matches for $username\n";
        exit(0);
    } else {
        echo "Password DOES NOT match for $username\n";
        exit(2);
    }
} catch (PDOException $e) {
    echo "PDO error: " . $e->getMessage() . "\n";
    exit(1);
}
