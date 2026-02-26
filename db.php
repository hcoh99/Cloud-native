<?php


// MySQL 연결 정보
$mysql_host = "pro-mysql"; // Docker Compose에서 지정된 MySQL 컨테이너 이름
$mysql_user = "php-mysql"; // MySQL 사용자
$mysql_password = "123456"; // MySQL 비밀번호
$mysql_db = "php-mysql"; // 데이터베이스 이름

// MySQL 연결
$connection = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);

// 연결 오류 처리
if (!$connection) {
    die(json_encode([
        "error" => "DB 연결 실패: " . mysqli_connect_error()
    ]));
}
?>
