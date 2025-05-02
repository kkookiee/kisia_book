// 헤더 로드 함수
function loadHeader() {
    fetch('/header.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-container').innerHTML = data;
        })
        .catch(error => console.error('헤더 로드 중 오류 발생:', error));
}

// 페이지 로드 시 헤더 로드
document.addEventListener('DOMContentLoaded', loadHeader); 