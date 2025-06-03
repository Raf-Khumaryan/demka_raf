// Модальное окно для отзывов
const reviewModal = document.getElementById('reviewModal');
const reviewBtns = document.querySelectorAll('.review-btn');
const closeBtn = document.querySelector('.close');

reviewBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('request_id').value = btn.dataset.id;
        reviewModal.style.display = 'block';
    });
});

closeBtn.addEventListener('click', () => {
    reviewModal.style.display = 'none';
});

// Редактирование профиля
document.getElementById('editProfileBtn').addEventListener('click', () => {
    window.location.href = 'profile.php';
});

// Отправка отзыва
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const requestId = document.getElementById('request_id').value;
    const reviewText = document.getElementById('reviewText').value;
    
    fetch('save_review.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ request_id: requestId, review: reviewText })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Отзыв сохранен!');
            reviewModal.style.display = 'none';
        }
    });
});

// Обновление статуса в админ-панели
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function() {
        const requestId = this.dataset.id;
        const status = this.value;
        
        fetch('update_status.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ request_id: requestId, status: status })
        });
    });
});