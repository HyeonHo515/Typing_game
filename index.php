<?php
require 'auth.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>타자 연습</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <!-- 상단 로그인 상태 표시 바 -->
    <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:14px;">
        <div>
            <?php if ($is_logged_in): ?>
                안녕하세요, <strong><?= htmlspecialchars($current_username) ?></strong> 님
            <?php else: ?>
                로그인하지 않은 상태입니다.
            <?php endif; ?>
        </div>
        <div>
            <?php if ($is_logged_in): ?>
                <a href="logout.php" style="color:#fff;">로그아웃</a>
            <?php else: ?>
                <a href="login.php" style="color:#fff; margin-right:8px;">로그인</a>
                <a href="register.php" style="color:#fff;">회원가입</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- 여기부터는 기존 타자 연습 화면 구조 -->
    <h1>타자 연습</h1>

    <div class="difficulty">
        <button class="diff-btn active" data-level="easy">쉬움</button>
        <button class="diff-btn" data-level="normal">보통</button>
        <button class="diff-btn" data-level="hard">어려움</button>
    </div>

    <div id="quote" class="quote"></div>

    <textarea id="input" class="input" placeholder="여기에 타이핑하세요..." disabled></textarea>

    <div class="stats">
        <div>시간: <span id="time">0</span>초</div>
        <div>정확도: <span id="accuracy">0</span>%</div>
        <div>WPM: <span id="wpm">0</span></div>
    </div>

    <button id="start-btn">시작하기</button>

    <div id="message"></div>

    <hr>

    <h2>랭킹 Top 10</h2>
    <ul id="ranking-list"></ul>
</div>

<script src="game.js"></script>
</body>
</html>
