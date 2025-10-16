<?php
$dir = __DIR__ . '/../storage/framework/sessions';
echo "Checking sessions directory: $dir\n";
if (!file_exists($dir)) {
    echo "Directory does not exist.\n";
    exit(1);
}
echo "Is writable: " . (is_writable($dir) ? 'yes' : 'no') . "\n";
$files = array_slice(scandir($dir, SCANDIR_SORT_DESCENDING), 0, 20);
if (!$files) {
    echo "No files found in sessions directory.\n";
    exit(0);
}
echo "Recent session files (up to 20):\n";
foreach ($files as $f) {
    if ($f === '.' || $f === '..') continue;
    $path = $dir . '/' . $f;
    $size = filesize($path);
    $mtime = date('Y-m-d H:i:s', filemtime($path));
    echo "$f\t$size bytes\t$mtime\n";
}
