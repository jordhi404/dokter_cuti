// Set interval untuk mengganti halaman setiap 3 menit (180000 ms)
let currentPage = 1;
const switchPage = () => {
    const page1 = document.getElementById('page1');
    const page2 = document.getElementById('page2');
    
    if (currentPage === 1) {
        page1.classList.remove('active');
        page2.classList.add('active');
        currentPage = 2;
    } else {
        page2.classList.remove('active');
        page1.classList.add('active');
        currentPage = 1;
    }
};

setInterval(switchPage, 180000); // 180000 ms = 3 menit