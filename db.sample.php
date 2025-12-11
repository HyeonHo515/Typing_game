<?php
// db.php

// 개발 중 디버깅 편하려고 에러는 일단 보이게 둠
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_OFF);

// ★ 여기 네 환경에 맞게 설정
$host = 'host_number';
$user = 'YOUR_DB_USER';
$pass = 'YOUR_DB_PASSWORD';
$db   = 'typing_game';
$port = 'port_number';               // XAMPP에서 MySQL 포트를 3307로 바꿨다면 꼭 3307

// ★ 중요: 포트를 5번째 인자로 반드시 넘긴다!
$mysqli = new mysqli($host, $user, $pass, $db, $port);

if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'DB connection failed: ' . $mysqli->connect_error
    ]);
    exit;
}

$mysqli->set_charset('utf8mb4');

