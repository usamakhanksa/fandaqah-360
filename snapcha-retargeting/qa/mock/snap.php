<?php
$b = file_get_contents('php://input');
file_put_contents(__DIR__ . '/snap.log', json_encode(['uri' => $_SERVER['REQUEST_URI'], 'body' => json_decode($b, true)]) . "\n", FILE_APPEND);
header('Content-Type: application/json'); echo json_encode(['status' => 'VALID', 'reason' => 'Events have been processed successfully.']);
