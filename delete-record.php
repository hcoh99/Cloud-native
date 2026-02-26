<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$mysqli = new mysqli('pro-mysql', 'php-mysql', '123456', 'php-mysql');

if ($mysqli->connect_error) {
    die(json_encode(['success' => false, 'error' => 'DB 연결 실패: ' . $mysqli->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carNumber = $_POST['carNumber'];

    if (!$carNumber) {
        die(json_encode(['success' => false, 'error' => '유효하지 않은 요청 데이터입니다.']));
    }

    $stmt = $mysqli->prepare("DELETE FROM parking_records WHERE car_number = ?");
    $stmt->bind_param('s', $carNumber);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '주차 기록이 삭제되었습니다.']);
    } else {
        echo json_encode(['success' => false, 'error' => '삭제 중 오류가 발생했습니다.']);
    }
}
?>
