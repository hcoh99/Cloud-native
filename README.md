# Cloud-native

# 🚗 주차 예약 시스템 (Parking Reservation System)

PHP + MySQL + Docker 기반의 주차 시간 등록 및 관리 웹 애플리케이션입니다.

---

## 📌 주요 기능

- 차량 번호 기반 주차 시간 등록 (1시간 / 2시간 / 3시간)
- 기존 등록 차량 시간 누적 추가
- 주차 기록 전체 조회 및 삭제
- 전체 결제 금액 실시간 집계

---

## 🛠 기술 스택

| 분류 | 기술 |
|------|------|
| Frontend | HTML5, jQuery 3.6, Bootstrap (Bootswatch Lux) |
| Backend | PHP 8.0, Apache |
| Database | MySQL 8.0 |
| Infra | Docker, Docker Compose |
| Proxy | Nginx Proxy Manager |

---

## 🏗 아키텍처

```
[pro-front : 8011]
  HTML / JS / CSS
       │
       ▼
[myproxy : 80]
  Nginx Proxy Manager
       │
       ▼
[mytaskapi : 8012]
  PHP 8.0 + Apache
       │
       ▼
[pro-mysql : 33306]
  MySQL 8.0
```

### 컨테이너 구성

| 컨테이너 | 역할 | 포트 |
|---------|------|------|
| `pro-front` | 정적 프론트엔드 서빙 | 8011 |
| `myproxy` | 리버스 프록시 (Nginx Proxy Manager) | 80, 443, 8181 |
| `mytaskapi` | PHP REST API 서버 | 8012 |
| `pro-mysql` | MySQL 데이터베이스 | 33306 |

---

## 📁 디렉토리 구조

```
parking-system/
├── .env.example            # 환경변수 예시
├── .gitignore
├── docker-compose.yaml     # 컨테이너 오케스트레이션
├── Dockerfile              # PHP + Apache 이미지
├── 000-default.conf        # Apache VirtualHost 설정
├── README.md
│
├── pro-html/               # 프론트엔드
│   ├── index.html
│   ├── app.js
│   └── styles.css
│
└── pro-php/
    └── task/               # PHP REST API
        ├── db.php
        ├── process-parking.php
        ├── get-records.php
        ├── get-total-amount.php
        └── delete-record.php
```

---

## ⚙️ 실행 방법

### 1. 환경변수 설정

```bash
cp .env.example .env
```

`.env` 파일을 열어 DB 비밀번호 등을 수정하세요.

```env
MYSQL_ROOT_PASSWORD=your_root_password
MYSQL_DATABASE=php-mysql
MYSQL_USER=php-mysql
MYSQL_PASSWORD=your_password
```

### 2. 컨테이너 실행

```bash
docker-compose up -d
```

### 3. 데이터베이스 테이블 생성

MySQL 컨테이너에 접속하여 테이블을 생성합니다.

```bash
docker exec -it pro-mysql mysql -u php-mysql -p php-mysql
```

```sql
CREATE TABLE parking_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_number VARCHAR(20) NOT NULL UNIQUE,
    registered_hours INT DEFAULT 0,
    total_amount INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 4. 접속

| 서비스 | URL |
|--------|-----|
| 웹 프론트엔드 | http://localhost:8011 |
| PHP API | http://localhost:8012 |
| Nginx Proxy Manager 관리 | http://localhost:8181 |

---

## 🔌 API 명세

Base URL: `/task/`

| Method | Endpoint | 설명 | 파라미터 |
|--------|----------|------|---------|
| `POST` | `process-parking.php` | 차량 등록 / 시간 추가 | `carNumber`, `hours`, `amount` |
| `POST` | `delete-record.php` | 차량 기록 삭제 | `carNumber` |
| `GET` | `get-records.php` | 전체 주차 기록 조회 | - |
| `GET` | `get-total-amount.php` | 전체 결제 금액 합계 | - |

### 응답 예시

**GET** `/task/get-records.php`
```json
[
  {
    "car_number": "12가3456",
    "registered_hours": 3,
    "total_amount": 9000
  }
]
```

**GET** `/task/get-total-amount.php`
```json
{
  "total": 15000
}
```

---

## 💡 요금 정책

| 시간 | 금액 |
|------|------|
| 1시간 | 3,000원 |
| 2시간 | 6,000원 |
| 3시간 | 9,000원 |

- 동일 차량 번호 재등록 시 시간 및 금액이 **누적** 합산됩니다.

---

## 📝 주의사항

- `.env` 파일은 절대 GitHub에 커밋하지 마세요. (`.gitignore`에 포함됨)
- 실제 운영 환경에서는 강력한 DB 비밀번호와 API 인증을 추가하세요.
