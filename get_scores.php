<?php
// get_scores.php
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode([]);
    exit;
}

// ★ 쿼리스트링으로 level 받기 (없으면 normal)
$level = isset($_GET['level']) ? $_GET['level'] : 'normal';
if (!in_array($level, ['easy', 'normal', 'hard'], true)) {
    $level = 'normal';
}

$sql = "SELECT player_name, wpm, accuracy, elapsed_seconds, level, created_at
        FROM scores
        WHERE level = ?
        ORDER BY wpm DESC
        LIMIT 10";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $level);
$stmt->execute();
$result = $stmt->get_result();

$list = [];
while ($row = $result->fetch_assoc()) {
    $list[] = $row;
}

echo json_encode($list);

$stmt->close();
$mysqli->close();
