<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$mysqli = new mysqli('pro-mysql', 'php-mysql', '123456', 'php-mysql');

// DB 연결 상태 점검
if ($mysqli->connect_error) {
    die(json_encode(['success' => false, 'message' => 'DB 연결 실패: ' . $mysqli->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 입력값 검증
    $carNumber = isset($_POST['carNumber']) ? $_POST['carNumber'] : null;
    $hours = isset($_POST['hours']) ? (int)$_POST['hours'] : 0;
    $amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 0;

    if (!$carNumber || $hours <= 0 || $amount <= 0) {
        die(json_encode(['success' => false, 'message' => '유효하지 않은 요청 데이터입니다.']));
    }

    // 차량이 이미 등록되어 있는지 확인
    $stmt = $mysqli->prepare("SELECT registered_hours FROM parking_records WHERE car_number = ?");
    $stmt->bind_param('s', $carNumber);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // 기존 차량의 시간을 업데이트
        $stmt = $mysqli->prepare("UPDATE parking_records SET registered_hours = registered_hours + ?, total_amount = total_amount + ? WHERE car_number = ?");
        $stmt->bind_param('iis', $hours, $amount, $carNumber);
        if ($stmt->execute() === false) {
            die(json_encode(['success' => false, 'message' => '쿼리 실행 실패: ' . $stmt->error]));
        }
        echo json_encode(['success' => true, 'message' => "$hours 시간 추가되었습니다."]);
    } else {
        // 새 차량 등록
        $stmt = $mysqli->prepare("INSERT INTO parking_records (car_number, registered_hours, total_amount) VALUES (?, ?, ?)");
        $stmt->bind_param('sii', $carNumber, $hours, $amount);
        if ($stmt->execute() === false) {
            die(json_encode(['success' => false, 'message' => '쿼리 실행 실패: ' . $stmt->error]));
        }
        echo json_encode(['success' => true, 'message' => '새 차량이 등록되었습니다.']);
    }
}
