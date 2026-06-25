<?php
require_once 'wp-load.php';
$options = get_option('hrl_jwt_bridge_options', []);
echo "JWT Secret: " . (empty($options['jwt_secret']) ? '[empty]' : '[set]') . "\n";

$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6InRlc3RAdGVzdC5jb20iLCJ1aWQiOiIxMjMiLCJyb2xlIjoiYWRtaW4iLCJleHAiOjE3ODIzOTkzNzB9.1b0c37e3da45a3941e9f8e6d2ed4bece87dd57e33f9d58bf8c1839d5de70e7b0';
$parts = explode('.', $token);
echo "Parts: " . count($parts) . "\n";

$secret = $options['jwt_secret'] ?? '';
$expected = hash_hmac('sha256', $parts[0] . '.' . $parts[1], $secret);
echo "Expected: " . $expected . "\n";
echo "Got: " . $parts[2] . "\n";
echo "Match: " . (hash_equals($expected, $parts[2]) ? 'yes' : 'no') . "\n";