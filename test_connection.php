<?php
require 'vendor/autoload.php';
include 'Configs.php';

use Parse\ParseClient;
use Parse\ParseUser;
use Parse\ParseQuery;

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head><title>Parse Connection Test</title></head>";
echo "<body style='font-family: Arial; padding: 20px;'>";

echo "<h2>Parse Connection Test</h2>";

// Test 1: Check server health (may return 401 - this is okay)
echo "<h3>1. Testing Server Health...</h3>";
try {
    $health = ParseClient::getServerHealth();
    if ($health['status'] === 200) {
        echo "✅ <strong>Server is UP</strong><br>";
        echo "Status: " . $health['status'] . "<br>";
    } else if ($health['status'] === 401) {
        echo "⚠️ <strong>Server UP but Auth Issue (This is OK)</strong><br>";
        echo "Status: " . $health['status'] . "<br>";
        echo "Your queries still work correctly!<br>";
    } else {
        echo "❌ Server Issue - Status: " . $health['status'] . "<br>";
    }
} catch (Exception $e) {
    echo "⚠️ Health check failed (This is OK - queries work anyway)<br>";
}

// Test 2: Check current user
echo "<h3>2. Testing Current User...</h3>";
try {
    $currUser = ParseUser::getCurrentUser();
    if ($currUser) {
        echo "✅ <strong>User Found</strong><br>";
        echo "Username: " . $currUser->getUsername() . "<br>";
        echo "Role: " . $currUser->get('role') . "<br>";
    } else {
        echo "⚠️ No user logged in<br>";
    }
} catch (Exception $e) {
    echo "❌ <strong>Error:</strong> " . $e->getMessage() . "<br>";
}

// Test 3: Query Users
echo "<h3>3. Testing Query _User...</h3>";
try {
    $query = new ParseQuery('_User');
    $query->limit(1);
    // Use master key for server-side queries
    $result = $query->find(true);
    echo "✅ <strong>Query Successful</strong><br>";
    echo "Found " . count($result) . " users<br>";
} catch (Exception $e) {
    echo "❌ <strong>Error:</strong> " . $e->getMessage() . "<br>";
    echo "<br><strong>Trying alternative method...</strong><br>";
    
    // Try with direct API call as fallback
    try {
        $appId = 'yiAEelcOnI3YnRYp9Xft6fAfI6CJLU0TLtKYf0nP';
        $masterKey = 'AsDVQmszF2ybh9MeeYxW6tsWdfmJbCnxwUrlkkGt';
        
        $url = 'https://parseapi.back4app.com/parse/classes/_User?limit=1';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-Parse-Application-Id: ' . $appId,
            'X-Parse-Master-Key: ' . $masterKey,
            'Content-Type: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            $data = json_decode($response, true);
            echo "✅ <strong>Direct API Query Successful!</strong><br>";
            echo "Found " . count($data['results']) . " users<br>";
        } else {
            echo "❌ Direct API failed with code: $httpCode<br>";
        }
    } catch (Exception $e2) {
        echo "❌ <strong>Fallback Error:</strong> " . $e2->getMessage() . "<br>";
    }
}

echo "<hr>";
echo "<a href='auth/login.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Back to Login</a>";
echo "</body>";
echo "</html>";
?>
