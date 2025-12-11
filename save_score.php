<?php
header('Content-Type: application/json; charset=utf-8');
require 'db.php';
require 'auth.php';  // ★ 추가

if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'DB connection failed']);
    exit;
}

// ★ 로그인 상태면 username 사용, 아니면 Guest
if ($is_logged_in && $current_username) {
    $player_name = $current_username;
} else {
    $player_name = 'Guest';
}

$wpm      = isset($_POST['wpm'])      ? intval($_POST['wpm'])      : 0;
$accuracy = isset($_POST['accuracy']) ? intval($_POST['accuracy']) : 0;
$elapsed  = isset($_POST['elapsed'])  ? intval($_POST['elapsed'])  : 0;

$level = isset($_POST['level']) ? $_POST['level'] : 'normal';
if (!in_array($level, ['easy','normal','hard'], true)) {
    $level = 'normal';
}

$stmt = $mysqli->prepare(
    "INSERT INTO scores (player_name, wpm, accuracy, elapsed_seconds, level)
     VALUES (?, ?, ?, ?, ?)"
);

if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'prepare failed: ' . $mysqli->error]);
    exit;
}

$stmt->bind_param("siiis", $player_name, $wpm, $accuracy, $elapsed, $level);
$ok = $stmt->execute();

echo json_encode(['success' => $ok, 'error' => $ok ? null : $stmt->error]);

$stmt->close();
$mysqli->close();
