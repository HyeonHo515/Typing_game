<?php
require 'db.php';
require 'auth.php';

if ($is_logged_in) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username'] ?? '');
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($username === '' || $password === '' || $password2 === '') {
        $error = '모든 칸을 입력하세요.';
    } elseif ($password !== $password2) {
        $error = '비밀번호가 서로 다릅니다.';
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        if (!$stmt) {
            $error = '회원가입 중 오류가 발생했습니다. (SQL 준비 실패)';
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = '이미 존재하는 아이디입니다.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt2 = $mysqli->prepare(
                    "INSERT INTO users (username, password_hash) VALUES (?, ?)"
                );
                if (!$stmt2) {
                    $error = '회원가입 중 오류가 발생했습니다. (INSERT 준비 실패)';
                } else {
                    $stmt2->bind_param("ss", $username, $hash);
                    if ($stmt2->execute()) {
                        $success = '회원가입 성공! 이제 로그인해주세요.';
                    } else {
                        $error = '회원가입 중 오류가 발생했습니다.';
                    }
                    $stmt2->close();
                }
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
    <title>회원가입 - 타자 연습</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-wrapper">
<div class="auth-card">
    <div class="auth-title">회원가입</div>
    <div class="auth-subtitle">아이디는 랭킹에 표시될 닉네임으로 사용됩니다.</div>

    <?php if ($error): ?>
        <div class="auth-message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="auth-message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="auth-form-group">
            <label class="auth-label">아이디(닉네임)</label>
            <input type="text" name="username" class="auth-input" required>
        </div>
        <div class="auth-form-group">
            <label class="auth-label">비밀번호</label>
            <input type="password" name="password" class="auth-input" required>
        </div>
        <div class="auth-form-group">
            <label class="auth-label">비밀번호 확인</label>
            <input type="password" name="password2" class="auth-input" required>
        </div>
        <button type="submit" class="auth-btn-primary">회원가입</button>
    </form>

    <div class="auth-links">
        <a href="login.php">이미 계정이 있나요?</a>
        <a href="index.php">← 타자 연습으로 돌아가기</a>
    </div>
</div>
</body>
</html>