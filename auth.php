<?php
// auth.php : 모든 페이지에서 공통으로 세션 + 로그인 상태 확인

session_start();

$is_logged_in = isset($_SESSION['user_id']);
$current_user = null;
$current_username = null;

if ($is_logged_in) {
    $current_user = [
        'id'       => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
    ];
    $current_username = $_SESSION['username'];
}
?>
