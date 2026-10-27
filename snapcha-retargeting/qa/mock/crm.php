<?php
$b = file_get_contents('php://input');
file_put_contents(__DIR__ . '/crm.log', json_encode(['key' => $_SERVER['HTTP_X_API_KEY'] ?? null, 'body' => json_decode($b, true)]) . "\n", FILE_APPEND);
header('Content-Type: application/json'); echo json_encode(['ok' => true, 'id' => 123]);
