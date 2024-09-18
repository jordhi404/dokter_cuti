// Set interval untuk mengganti halaman setiap 3 menit (180000 ms)
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');

const showSlide = (index) => {
    slides.forEach((slide, i) => {
        if (i === index) {
            slide.classList.add('active');
        } else {
            slide.classList.remove('active');
        }
    });
};

showSlide(currentSlide);

const switchSlide = () => {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
};

setInterval(switchSlide, 10000); // 10000 ms = 10 detik