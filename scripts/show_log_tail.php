<?php
$f = __DIR__ . '/../storage/logs/laravel.log';
if (!file_exists($f)) {
    echo "log missing\n";
    exit(0);
}
$lines = file($f);
$tail = array_slice($lines, -200);
echo implode('', $tail);
