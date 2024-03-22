document.addEventListener("DOMContentLoaded", function () {
    const items = document.querySelectorAll('.slide .item');
    const prevBtn = document.querySelector('.button .prev');
    const nextBtn = document.querySelector('.button .next');
    let currentIndex = 0;

    function showCurrentItem() {
        items.forEach((item, index) => {
            if (index === currentIndex) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function showNextItem() {
        currentIndex = (currentIndex + 1) % items.length;
        showCurrentItem();
    }

    function showPreviousItem() {
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        showCurrentItem();
    }

    prevBtn.addEventListener('click', showPreviousItem);
    nextBtn.addEventListener('click', showNextItem);

    // Show the initial item
    showCurrentItem();
});
