document.addEventListener("DOMContentLoaded", function () {
  const slides = document.querySelectorAll(".slide .item");
  const prevButton = document.querySelector(".prev");
  const nextButton = document.querySelector(".next");
  let currentSlide = 0;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.style.display = i === index ? "block" : "none";
    });
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  }

  function prevSlide() {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
  }

  // Show the first slide initially
  showSlide(currentSlide);

  // Set up interval for automatic slide change
  const slideInterval = setInterval(nextSlide, 5000);

  // Set up event listeners for manual navigation
  nextButton.addEventListener("click", () => {
    nextSlide();
    clearInterval(slideInterval); // Clear interval to prevent conflict with manual navigation
    setTimeout(() => (slideInterval = setInterval(nextSlide, 5000)), 5000); // Restart interval after manual navigation
  });

  prevButton.addEventListener("click", () => {
    prevSlide();
    clearInterval(slideInterval); // Clear interval to prevent conflict with manual navigation
    setTimeout(() => (slideInterval = setInterval(nextSlide, 5000)), 5000); // Restart interval after manual navigation
  });
});
