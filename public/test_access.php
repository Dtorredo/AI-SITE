<?php
// Test PHP execution
header('Content-Type: text/plain');
echo "PHP is working!\n\n";

// Test file access
$contactFile = __DIR__ . '/contact.html';
if (file_exists($contactFile)) {
    echo "contact.html exists at: " . $contactFile . "\n";
    echo "File size: " . filesize($contactFile) . " bytes\n";
    echo "File permissions: " . substr(sprintf('%o', fileperms($contactFile)), -4) . "\n";
} else {
    echo "Error: contact.html not found at: " . $contactFile . "\n";
}

// Test directory listing
$dir = __DIR__;
echo "\nDirectory listing of " . $dir . ":\n";
$files = scandir($dir);
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        $type = is_dir($file) ? 'DIR ' : 'FILE';
        echo "[$type] $file\n";
    }
}
?>
