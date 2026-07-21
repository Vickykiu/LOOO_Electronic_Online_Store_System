<?php
// Simple syntax test for main page.php
echo "Testing PHP syntax...\n";

// Test basic PHP functionality
$test = "Hello World";
echo "Basic test: " . $test . "\n";

// Test session functionality
session_start();
echo "Session started successfully\n";

// Test array operations
$testArray = array("test1", "test2");
echo "Array test: " . $testArray[0] . "\n";

// Test string functions
$testString = "John Doe";
$parts = explode(' ', $testString);
echo "String split test: " . $parts[0] . "\n";

// Test conditional statements
if (true) {
    echo "Conditional test: PASSED\n";
} else {
    echo "Conditional test: FAILED\n";
}

// Test htmlspecialchars function
if (function_exists('htmlspecialchars')) {
    echo "htmlspecialchars function: AVAILABLE\n";
} else {
    echo "htmlspecialchars function: NOT AVAILABLE\n";
}

echo "All syntax tests completed successfully!\n";
echo "Your PHP version: " . PHP_VERSION . "\n";
?>
