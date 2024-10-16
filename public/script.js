// // Set interval untuk mengganti halaman setiap 3 menit (180000 ms)
// let currentSlide = 0;
// const slides = document.querySelectorAll('.slide');

// const showSlide = (index) => {
//     slides.forEach((slide, i) => {
//         if (i === index) {
//             slide.classList.add('active');
//         } else {
//             slide.classList.remove('active');
//         }
//     });
// };

// showSlide(currentSlide);

// const switchSlide = () => {
//     currentSlide = (currentSlide + 1) % slides.length;
//     showSlide(currentSlide);
// };

// setInterval(switchSlide, 10000); // 10000 ms = 10 detik

// Fungsi untuk menginisialisasi slider
function initializeSlider(sliderId, interval = 10000) {
    let currentSlide = 0;
    const slides = document.querySelectorAll(`#${sliderId} .slide`);
    const totalSlides = slides.length;

    if (totalSlides === 0) return; // Jika tidak ada slide, jangan lakukan apa-apa

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
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    };

    setInterval(switchSlide, 10000); // Slide otomatis setiap 10 detik.
}

// Inisialisasi semua slider setelah DOM siap
document.addEventListener('DOMContentLoaded', () => {
    initializeSlider('doctor-slider-cuti-hari-ini');
    initializeSlider('doctor-slider-cuti-akan-datang');
    initializeSlider('doctor-slider-not-on-leave');
});
