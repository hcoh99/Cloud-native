
$(document).ready(function () {
    const apiUrl = '/task/';
 // Nginx Proxy Manager에 따라 이 경로를 조정해야 합니다.

    // 페이지 로드 시 기록 및 총 금액 가져오기
    fetchRecords();
    fetchTotalAmount();

    // 추가 버튼 클릭 이벤트
    $('#add-1-hour').click(() => addParkingTime(1, 3000));
    $('#add-2-hour').click(() => addParkingTime(2, 6000));
    $('#add-3-hour').click(() => addParkingTime(3, 9000));

    // 주차 시간 추가 함수
    function addParkingTime(hours, amount) {
        const carNumber = $('#carNumber').val().trim();
        if (!carNumber) {
            alert('차량 번호를 입력하세요.');
            return;
        }

        $.post(apiUrl + 'process-parking.php', { carNumber, hours, amount })
            .done((response) => {
                alert(response.message || '시간이 추가되었습니다.');
                fetchRecords();
                fetchTotalAmount();
                $('#carNumber').val('');
            })
            .fail(() => alert('서버 오류가 발생했습니다.'));
    }

    // 기록 삭제 함수
    function deleteRecord(carNumber) {
        if (!confirm(`${carNumber} 기록을 삭제하시겠습니까?`)) return;

        $.post(apiUrl + 'delete-record.php', { carNumber })
            .done((response) => {
                alert(response.message || '기록이 삭제되었습니다.');
                fetchRecords();
                fetchTotalAmount();
            })
            .fail(() => alert('삭제 중 오류가 발생했습니다.'));
    }

    // 주차 기록 가져오기
    function fetchRecords() {
        $.get(apiUrl + 'get-records.php', (data) => {
            let template = '';
            data.forEach((record) => {
                template += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>차량 번호:</strong> ${record.car_number}<br>
                            <strong>등록 시간:</strong> ${record.registered_hours} 시간<br>
                            <strong>결제 금액:</strong> ${record.total_amount} 원
                        </div>
                        <button class="btn btn-danger btn-sm" onclick="deleteRecord('${record.car_number}')">삭제</button>
                    </li>`;
            });
            $('#parking-list').html(template);
        }).fail(() => alert('주차 기록을 불러오는 중 오류가 발생했습니다.'));
    }

    // 총 금액 가져오기
    function fetchTotalAmount() {
        $.get(apiUrl + 'get-total-amount.php', (data) => {
            $('#total-amount').text(`총 금액: ${data.total || 0}원`);
        }).fail(() => alert('총 금액을 불러오는 중 오류가 발생했습니다.'));
    }

    // 전역 삭제 함수 등록 (동적으로 생성된 버튼 이벤트)
    window.deleteRecord = deleteRecord;
});
