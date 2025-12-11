<?php
require 'db.php';
require 'auth.php';

if ($is_logged_in) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = '아이디와 비밀번호를 입력하세요.';
    } else {
        $stmt = $mysqli->prepare(
            "SELECT id, password_hash FROM users WHERE username = ?"
        );
        if (!$stmt) {
            $error = '로그인 중 오류가 발생했습니다. (SQL 준비 실패)';
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($user_id, $password_hash);

            if ($stmt->fetch()) {
                if (password_verify($password, $password_hash)) {
                    $_SESSION['user_id']  = $user_id;
                    $_SESSION['username'] = $username;
                    header('Location: index.php');
                    exit;
                } else {
                    $error = '비밀번호가 올바르지 않습니다.';
                }
            } else {
                $error = '존재하지 않는 아이디입니다.';
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>로그인 - 타자 연습</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-wrapper">
<div class="auth-card">
    <div class="auth-title">로그인</div>
    <div class="auth-subtitle">타자 연습 기록을 랭킹에 저장하려면 로그인하세요.</div>

    <?php if ($error): ?>
        <div class="auth-message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="auth-form-group">
            <label class="auth-label">아이디</label>
            <input type="text" name="username" class="auth-input" required>
        </div>
        <div class="auth-form-group">
            <label class="auth-label">비밀번호</label>
            <input type="password" name="password" class="auth-input" required>
        </div>
        <button type="submit" class="auth-btn-primary">로그인</button>
    </form>

    <div class="auth-links">
        <a href="register.php">아직 계정이 없나요? 회원가입</a>
        <a href="index.php">← 타자 연습으로 돌아가기</a>
    </div>
</div>
</body>
</html>
