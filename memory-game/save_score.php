<?php
session_start();
require 'db.php';

// รับข้อมูล JSON จาก game.php
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'No data received']);
    exit;
}

$player_name = htmlspecialchars(trim($data['player_name']));
$difficulty  = $data['difficulty'];
$time_taken  = (int)$data['time_taken'];
$moves       = (int)$data['moves'];
$is_win      = $data['is_win'] ? 1 : 0;

// บันทึกลง SQLite
$stmt = $pdo->prepare("INSERT INTO leaderboard 
    (player_name, difficulty, time_taken, moves, is_win) 
    VALUES (?, ?, ?, ?, ?)");

$stmt->execute([$player_name, $difficulty, $time_taken, $moves, $is_win]);

echo json_encode(['success' => true]);
?>