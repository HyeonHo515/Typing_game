# ⌨️ 타자 연습 웹사이트 (Typing Practice Web App)

PHP + MySQL 기반으로 제작된 타자 연습 웹사이트입니다.  
로그인, 회원가입, 난이도 선택, 실시간 타자 속도 계산(WPM), 정확도 계산,  
그리고 랭킹 저장 기능을 포함하고 있습니다.

---

## ⭐ 주요 기능 (Features)

### 🔐 회원 시스템
- 회원가입 / 로그인 / 로그아웃 지원
- 비밀번호는 `password_hash()`로 암호화 저장
- 로그인된 사용자 기록은 자동으로 DB에 저장됨

### 🎮 타자 연습 기능
- **난이도 선택**: 쉬움 / 보통 / 어려움
- 난이도별로 **문장 길이, 배경 색상, UI** 변경
- 실시간 WPM(타자 속도) 계산
- 실시간 정확도 계산
- 경과 시간 표시

### 🏆 랭킹 시스템
- DB에 최고 기록 자동 저장
- 난이도별 랭킹 Top 10 출력
- 기록 항목:
  - 사용자명
  - 난이도
  - WPM
  - 정확도
  - 걸린 시간

---

## 🗂️ 기술 스택 (Tech Stack)

| Category | Technology |
|---------|------------|
| Frontend | HTML, CSS, JavaScript |
| Backend | PHP 8+ |
| Database | MySQL / MariaDB |
| Hosting | Local (XAMPP / Apache) |

---

## 📁 프로젝트 구조 (Project Structure)

/typing_gm
├── index.php # 메인 타자 연습 페이지
├── login.php # 로그인 페이지
├── register.php # 회원가입 페이지
├── logout.php # 로그아웃 처리
├── auth.php # 로그인 세션 처리
├── save_score.php # 기록 저장 API
├── get_scores.php # 기록 조회 API
├── game.js # 타자 게임 로직
├── style.css # UI 스타일
├── db.php # DB 연결 (Git에 업로드되지 않음)
├── db.sample.php # DB 연결 예시 파일
└── .gitignore # 민감 정보 / IDE 파일 제거

---

## 🛠️ 설치 및 실행 (Installation)

### 저장소 클론

```bash
git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
$host = 'host_number';
$user = 'YOUR_DB_USER';
$pass = 'YOUR_DB_PASSWORD';
$db   = 'typing_game';
$port = 'port_number'; //만약 localhost라면 없어도 되는 부분 

 MySQL에서 아래 테이블 생성
📌 users 테이블
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

📌 scores 테이블
CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(50) NOT NULL,
    wpm INT NOT NULL,
    accuracy INT NOT NULL,
    elapsed_seconds INT NOT NULL,
    level ENUM('easy','normal','hard') DEFAULT 'normal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

🚀 사용 방법 (Usage)

브라우저에서 아래 주소 접속:

http://localhost/typing_gm/index.php

원하는 난이도 선택

표시된 문장을 정확하게 입력

완료 시 기록이 자동 저장 (로그인 사용자만)

📜 라이선스 (License)

This project is for educational purposes.

✨ 만든 사람 (Author)

현호 (HyeonHo515)
타자 연습 기능 + 회원 시스템이 모두 포함된 PHP 웹 프로젝트입니다.
더 발전시키고 싶다면 언제든 기능 요청하세요!
