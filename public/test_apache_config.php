<?php
header('Content-Type: text/plain');

// Server information
echo "=== Server Information ===\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Not set') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Not set') . "\n";
echo "Script Filename: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'Not set') . "\n";

// Check if mod_rewrite is enabled
echo "\n=== mod_rewrite Check ===\n";
if (in_array('mod_rewrite', apache_get_modules())) {
    echo "mod_rewrite is enabled\n";
} else {
    echo "WARNING: mod_rewrite is NOT enabled\n";
}

// Check for .htaccess file
$htaccess = __DIR__ . '/.htaccess';
echo "\n=== .htaccess Check ===\n";
if (file_exists($htaccess)) {
    echo ".htaccess exists at: " . $htaccess . "\n";
    echo "File permissions: " . substr(sprintf('%o', fileperms($htaccess)), -4) . "\n";
    
    // Check if .htaccess is being read
    if (strpos(implode(' ', apache_request_headers()), '.htaccess') !== false) {
        echo "Status: .htaccess is being read\n";
    } else {
        echo "WARNING: .htaccess might not be read (check AllowOverride in Apache config)\n";
    }
} else {
    echo "ERROR: .htaccess not found in " . __DIR__ . "\n";
}

// Check PHP settings
echo "\n=== PHP Settings ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Display Errors: " . ini_get('display_errors') . "\n";
echo "Error Log: " . ini_get('error_log') . "\n";

// Test file access
echo "\n=== File Access Test ===\n";
$testFile = __DIR__ . '/contact.html';
if (file_exists($testFile)) {
    echo "contact.html exists at: " . $testFile . "\n";
    echo "File size: " . filesize($testFile) . " bytes\n";
    echo "File permissions: " . substr(sprintf('%o', fileperms($testFile)), -4) . "\n";
    
    // Test reading the file
    $content = @file_get_contents($testFile);
    if ($content !== false) {
        echo "File is readable. First 100 chars: " . substr($content, 0, 100) . "...\n";
    } else {
        echo "WARNING: Could not read the file. Check permissions.\n";
    }
} else {
    echo "ERROR: contact.html not found at: " . $testFile . "\n";
}

// Test URL rewriting
echo "\n=== URL Rewriting Test ===\n";
if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())) {
    echo "mod_rewrite is enabled\n";
    
    // Test if .htaccess is being processed
    $testRewrite = __DIR__ . '/test_rewrite';
    if (!file_exists($testRewrite)) {
        file_put_contents($testRewrite . '.html', 'Rewrite test successful!');
    }
    
    echo "Test URL: " . dirname($_SERVER['PHP_SELF']) . "/test_rewrite\n";
} else {
    echo "WARNING: mod_rewrite is not enabled\n";
}
?>
