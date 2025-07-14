<?php

// Simple test script for async question generation
// Run this with: php test-async-generation.php

$materialId = 24; // Use material ID 24 that exists
$baseUrl = 'http://localhost:8000';

// Test data
$testData = [
    'question_count' => 5,
    'difficulty' => 'menengah',
    'auto_generate' => true
];

echo "=== Testing Async Question Generation ===\n";
echo "Material ID: {$materialId}\n";
echo "Test Data: " . json_encode($testData, JSON_PRETTY_PRINT) . "\n";

// Get CSRF token first
echo "\n1. Getting CSRF token...\n";
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Accept: text/html\r\n"
    ]
]);

$homePageContent = file_get_contents("{$baseUrl}/materials/{$materialId}", false, $context);
if ($homePageContent === false) {
    die("Error: Could not fetch material page\n");
}

// Extract CSRF token
preg_match('/name="csrf-token" content="([^"]+)"/', $homePageContent, $matches);
if (!isset($matches[1])) {
    die("Error: Could not extract CSRF token\n");
}
$csrfToken = $matches[1];
echo "CSRF Token: {$csrfToken}\n";

// Test async generation
echo "\n2. Testing async generation...\n";
$postData = json_encode($testData);
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 
            "Content-Type: application/json\r\n" .
            "X-CSRF-TOKEN: {$csrfToken}\r\n" .
            "Accept: application/json\r\n" .
            "Content-Length: " . strlen($postData) . "\r\n",
        'content' => $postData,
        'timeout' => 10 // Only 10 seconds timeout for this test
    ]
]);

$startTime = microtime(true);
$response = file_get_contents("{$baseUrl}/materials/{$materialId}/generate-questions-async", false, $context);
$endTime = microtime(true);
$executionTime = round(($endTime - $startTime) * 1000, 2); // in milliseconds

if ($response === false) {
    echo "Error: HTTP request failed\n";
    echo "HTTP Error: " . error_get_last()['message'] . "\n";
} else {
    echo "Success! Response received in {$executionTime}ms\n";
    echo "Response: " . $response . "\n";
    
    // Test questions count API
    echo "\n3. Testing questions count API...\n";
    $countResponse = file_get_contents("{$baseUrl}/api/materials/{$materialId}/questions/count");
    if ($countResponse !== false) {
        echo "Questions count: {$countResponse}\n";
    } else {
        echo "Error: Could not get questions count\n";
    }
}

echo "\n=== Test Complete ===\n";

?>
