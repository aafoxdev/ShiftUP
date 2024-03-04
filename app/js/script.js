document.addEventListener('DOMContentLoaded', function() {
    var currentIndex = 0;
    var images = ['../img/home/home.jpg', '../img/home/fig1.jpg', '../img/home/fig2.jpg'];
    var titleElement = document.querySelector('.title');

    setInterval(function() {
        titleElement.style.opacity = '0'; // フェードアウト

        setTimeout(function() {
            currentIndex = (currentIndex + 1) % images.length;
            titleElement.style.backgroundImage = 'url(' + images[currentIndex] + ')';
            titleElement.style.opacity = '1'; // フェードイン
        }, 2000); // 1秒後に実行
    }, 6000); // 3秒ごとに実行
});