<?php
/**
 * Test script for unified N8N status endpoint
 * Tests success, error, and progress states
 */

echo "Testing Unified N8N Status Endpoint:\n\n";

$materialId = 1;
$baseUrl = 'http://192.168.13.193:8000/api/n8n/completion';

// Test 1: Clear any existing status
echo "1. Clearing existing status:\n";
$clearUrl = "http://192.168.13.193:8000/api/questions/cache/$materialId";
$context = stream_context_create([
    'http' => [
        'method' => 'DELETE',
        'timeout' => 5,
        'ignore_errors' => true
    ]
]);
$response = file_get_contents($clearUrl, false, $context);
echo "   Clear response: " . ($response ? $response : "No response") . "\n\n";

// Test 2: Check initial status (should be processing)
echo "2. Checking initial status:\n";
$response = file_get_contents("$baseUrl/$materialId", false, stream_context_create([
    'http' => ['method' => 'GET', 'timeout' => 5, 'ignore_errors' => true]
]));
echo "   GET Response: " . ($response ? $response : "No response") . "\n";
echo "   Expected: status='processing', completed=false\n\n";

// Test 3: Simulate N8N progress update
echo "3. Simulating N8N progress update:\n";
$progressData = json_encode([
    'material_id' => $materialId,
    'status' => 'progress',
    'message' => 'Processing PDF content...'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $progressData,
        'timeout' => 10,
        'ignore_errors' => true
    ]
]);

$response = file_get_contents($baseUrl, false, $context);
echo "   POST Data: $progressData\n";
echo "   POST Response: " . ($response ? $response : "No response") . "\n\n";

// Test 4: Check progress status
echo "4. Checking progress status:\n";
$response = file_get_contents("$baseUrl/$materialId", false, stream_context_create([
    'http' => ['method' => 'GET', 'timeout' => 5, 'ignore_errors' => true]
]));
echo "   GET Response: " . ($response ? $response : "No response") . "\n";
echo "   Expected: status='progress', progress=true\n\n";

// Test 5: Simulate N8N error
echo "5. Simulating N8N error:\n";
$errorData = json_encode([
    'material_id' => $materialId,
    'status' => 'error',
    'message' => 'Failed to process PDF',
    'error_details' => 'PDF file is corrupted or unreadable'
]);

$response = file_get_contents($baseUrl, false, stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $errorData,
        'timeout' => 10,
        'ignore_errors' => true
    ]
]));
echo "   POST Data: $errorData\n";
echo "   POST Response: " . ($response ? $response : "No response") . "\n\n";

// Test 6: Check error status
echo "6. Checking error status:\n";
$response = file_get_contents("$baseUrl/$materialId", false, stream_context_create([
    'http' => ['method' => 'GET', 'timeout' => 5, 'ignore_errors' => true]
]));
echo "   GET Response: " . ($response ? $response : "No response") . "\n";
echo "   Expected: status='error', error=true\n\n";

// Test 7: Simulate N8N success completion
echo "7. Simulating N8N success completion:\n";
$successData = json_encode([
    'material_id' => $materialId,
    'status' => 'completed',
    'message' => 'Flow has finished successfully!'
]);

$response = file_get_contents($baseUrl, false, stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $successData,
        'timeout' => 10,
        'ignore_errors' => true
    ]
]));
echo "   POST Data: $successData\n";
echo "   POST Response: " . ($response ? $response : "No response") . "\n\n";

// Test 8: Check completion status
echo "8. Checking completion status:\n";
$response = file_get_contents("$baseUrl/$materialId", false, stream_context_create([
    'http' => ['method' => 'GET', 'timeout' => 5, 'ignore_errors' => true]
]));
echo "   GET Response: " . ($response ? $response : "No response") . "\n";
echo "   Expected: status='completed', completed=true\n\n";

echo "Summary of Unified Endpoint:\n";
echo "✓ Single endpoint: /api/n8n/completion\n";
echo "✓ Handles: POST (n8n updates) and GET (frontend polling)\n";
echo "✓ Status types: 'processing', 'progress', 'completed', 'error'\n";
echo "✓ Frontend gets: completed=true/false, error=true/false, progress=true/false\n";
echo "✓ N8N can send: success, progress updates, and errors\n";
echo "✓ All loading states handled by one endpoint\n";
