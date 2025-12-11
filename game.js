// ===== 난이도별 문장 목록 =====
const sentencePools = {
    easy: [
        "바람이 분다.",
        "오늘은 좋다.",
        "코딩은 재미있다.",
        "밥은 먹었니?",
        "책을 읽자."
    ],
    normal: [
        "바람이 분다. 살아야겠다.",
        "오늘은 새로운 시작을 해보자.",
        "프로그래밍은 문제를 해결하는 기술이다.",
        "천천히 꾸준하게 하는 것이 가장 강하다.",
        "햇빛이 잘 드는 오후였다."
    ],
    hard: [
        "프로그래밍은 논리와 창의력을 동시에 요구하는 복합적인 활동이다.",
        "꾸준한 연습과 성실한 습관은 단기간의 집중보다 훨씬 강력한 힘을 발휘한다.",
        "작은 오류 하나가 전체 시스템을 멈추게 만들 수 있기 때문에 세심한 주의가 필요하다.",
        "목표를 명확히 하고 나면, 남는 일은 그 목표를 향해 한 걸음씩 나아가는 것뿐이다."
    ]
};

// ===== DOM 요소 =====
const quoteEl      = document.getElementById("quote");
const inputEl      = document.getElementById("input");
const startBtn     = document.getElementById("start-btn");
const timeEl       = document.getElementById("time");
const accuracyEl   = document.getElementById("accuracy");
const wpmEl        = document.getElementById("wpm");
const messageEl    = document.getElementById("message");
const rankingList  = document.getElementById("ranking-list");
const diffButtons  = document.querySelectorAll(".diff-btn");

// ===== 상태 =====
let currentLevel = "normal";   // 기본 난이도
let quote = "";
let startTime = null;
let timer = null;
let finished = false;

// ===== 난이도별 테마 적용 =====
function applyLevelTheme(level) {
    const body = document.body;
    body.classList.remove("level-easy", "level-normal", "level-hard");

    if (level === "easy") {
        body.classList.add("level-easy");
    } else if (level === "hard") {
        body.classList.add("level-hard");
    } else {
        body.classList.add("level-normal");
    }
}

// ===== 문장 랜덤 선택 =====
function getRandomSentence(level = currentLevel) {
    const pool = sentencePools[level] || sentencePools.normal;
    const index = Math.floor(Math.random() * pool.length);
    return pool[index];
}

// ===== 문장 렌더링 =====
function renderQuote(text) {
    quoteEl.innerHTML = text
        .split("")
        .map(ch => `<span>${ch}</span>`)
        .join("");
}

// ===== 화면/상태 리셋 =====
function resetGameView() {
    if (timer) {
        clearInterval(timer);
        timer = null;
    }

    finished = false;
    startTime = null;

    inputEl.value = "";
    inputEl.disabled = true;

    timeEl.textContent = "0";
    accuracyEl.textContent = "0";
    wpmEl.textContent = "0";

    messageEl.textContent = "";

    // 기존 문자 색상 초기화
    const spans = quoteEl.querySelectorAll("span");
    spans.forEach(span => {
        span.classList.remove("correct", "incorrect");
    });

    startBtn.textContent = "시작하기";
    startBtn.disabled = false;
}

// ===== 게임 시작 =====
function startGame() {
    // 여러 번 눌러도 항상 깨끗이 시작될 수 있도록 리셋
    resetGameView();

    // 새 문장 설정
    quote = getRandomSentence(currentLevel);
    renderQuote(quote);

    inputEl.disabled = false;
    inputEl.focus();

    startTime = Date.now();
    finished = false;
    startBtn.disabled = true;
    startBtn.textContent = "진행 중...";

    timer = setInterval(updateStats, 100);
    console.log("게임 시작, 난이도:", currentLevel);
}

// ===== 통계 갱신 =====
function updateStats() {
    if (!startTime) return;

    const elapsed = Math.floor((Date.now() - startTime) / 1000);
    timeEl.textContent = elapsed;

    const spans = quoteEl.querySelectorAll("span");
    const input = inputEl.value.split("");

    let correctCount = 0;

    spans.forEach((span, i) => {
        const char = input[i];

        if (char == null) {
            span.classList.remove("correct", "incorrect");
        } else if (char === span.textContent) {
            span.classList.add("correct");
            span.classList.remove("incorrect");
            correctCount++;
        } else {
            span.classList.add("incorrect");
            span.classList.remove("correct");
        }
    });

    const accuracy = Math.floor((correctCount / spans.length) * 100);
    accuracyEl.textContent = isFinite(accuracy) ? accuracy : 0;

    const wpm = Math.floor((input.length / 5) / (elapsed / 60 || 1));
    wpmEl.textContent = isFinite(wpm) ? wpm : 0;

    // 문장 길이만큼 입력 완료 시
    if (!finished && input.length === quote.length) {
        finishGame();
    }
}

// ===== 게임 종료 =====
function finishGame() {
    if (finished) return; // 중복 종료 방지
    finished = true;

    if (timer) {
        clearInterval(timer);
        timer = null;
    }

    inputEl.disabled = true;
    startBtn.disabled = false;
    startBtn.textContent = "다시 시작하기";

    const elapsed = parseInt(timeEl.textContent, 10) || 0;
    const wpm     = parseInt(wpmEl.textContent, 10) || 0;
    const acc     = parseInt(accuracyEl.textContent, 10) || 0;

    messageEl.textContent =
        `완료! 시간: ${elapsed}초, WPM: ${wpm}, 정확도: ${acc}%`;

    // 닉네임 입력 없이 바로 저장
    saveScore("", wpm, acc, elapsed);
}

// ===== 점수 저장 (save_score.php) =====
function saveScore(playerName, wpm, accuracy, elapsed) {
    fetch("save_score.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
        },
        body: new URLSearchParams({
            player_name: playerName,
            wpm: String(wpm),
            accuracy: String(accuracy),
            elapsed: String(elapsed),
            level: currentLevel
        })
    })
        .then(res => res.json())
        .then(data => {
            console.log("saveScore:", data);

            if (!data.success) {
                console.error("점수 저장 실패:", data.error);
                messageEl.textContent = "점수 저장 중 오류가 발생했습니다.";
                return;
            }

            loadRanking();   // 저장 성공 시 현재 난이도 랭킹 다시 로드
        })
        .catch(err => {
            console.error("saveScore error:", err);
            messageEl.textContent = "점수 저장 중 통신 오류가 발생했습니다.";
        });
}


// ===== 랭킹 로드 (get_scores.php) =====
function loadRanking() {
    fetch("get_scores.php?level=" + encodeURIComponent(currentLevel))
        .then(res => res.json())
        .then(list => {
            rankingList.innerHTML = "";

            if (!Array.isArray(list) || list.length === 0) {
                const li = document.createElement("li");
                li.textContent = "아직 기록이 없습니다.";
                rankingList.appendChild(li);
                return;
            }

            list.forEach(row => {
                const li = document.createElement("li");
                li.textContent =
                    `${row.player_name} - [${row.level}] WPM: ${row.wpm}, 정확도: ${row.accuracy}% (시간: ${row.elapsed_seconds}s)`;
                rankingList.appendChild(li);
            });
        })
        .catch(err => {
            console.error("ranking load error:", err);
            rankingList.innerHTML =
                "<li>랭킹을 불러오는 중 오류가 발생했습니다.</li>";
        });
}

// ===== 난이도 버튼 처리 =====
diffButtons.forEach(btn => {
    btn.addEventListener("click", () => {
        currentLevel = btn.dataset.level;

        diffButtons.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        applyLevelTheme(currentLevel);

        // 게임 중이 아니면 프리뷰만 변경
        if (!startBtn.disabled) {
            const preview = getRandomSentence(currentLevel);
            renderQuote(preview);
            resetGameView();   // 입력/시간도 초기화
            messageEl.textContent = `난이도: ${btn.textContent}`;
        }
        console.log("난이도 변경:", currentLevel);

        // 난이도 바뀔 때마다 해당 난이도 랭킹 로드
        loadRanking();
    });
});

// ===== 이벤트 바인딩 =====
startBtn.addEventListener("click", startGame);

// 입력 중일 때 실시간으로 통계 갱신
inputEl.addEventListener("input", () => {
    if (!finished) updateStats();
});

// 페이지 최초 로드 시
window.addEventListener("load", () => {
    applyLevelTheme(currentLevel);
    const preview = getRandomSentence(currentLevel);
    renderQuote(preview);
    resetGameView();
    loadRanking();
});
