<?php

$mysqli = new mysqli('pro-mysql', 'php-mysql', '123456', 'php-mysql');

if ($mysqli->connect_error) {
    die(json_encode(['error' => 'DB 연결 실패: ' . $mysqli->connect_error]));
}

// 데이터 조회 쿼리 실행
$result = $mysqli->query("SELECT car_number, registered_hours, total_amount FROM parking_records");

if (!$result) {
    // 쿼리 실패 시 에러 메시지 반환
    die(json_encode(['error' => '쿼리 실행 실패: ' . $mysqli->error]));
}

$records = [];

// 결과를 배열로 변환
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

// JSON으로 응답
header('Content-Type: application/json');
echo json_encode($records);

$mysqli->close(); // 연결 닫기
?>
