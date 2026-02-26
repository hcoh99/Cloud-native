<?php

$mysqli = new mysqli('pro-mysql', 'php-mysql', '123456', 'php-mysql'); // 호스트 수정

if ($mysqli->connect_error) {
    die(json_encode(['error' => 'DB 연결 실패: ' . $mysqli->connect_error]));
}

$result = $mysqli->query("SELECT SUM(total_amount) AS total FROM parking_records");

if (!$result) {
    die(json_encode(['error' => '쿼리 실행 실패: ' . $mysqli->error]));
}

$row = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode(['total' => $row['total']]);

$mysqli->close(); // 연결 닫기
?>
