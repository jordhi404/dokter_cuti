// Inisialisasi slider dan AJAX untuk mengambil data JSON
document.addEventListener('DOMContentLoaded', function() {
    fetchDoctorOffDates();
});

// Fungsi untuk mengambil data menggunakan AJAX
function fetchDoctorOffDates() {
    // Menggunakan AJAX untuk mengambil data JSON
    fetch('/data/doctors')  // Ganti dengan URL yang sesuai untuk mengakses endpoint
        .then(response => response.json())
        .then(data => {
            console.log("Data received:", data); // Debugging untuk melihat data JSON
            displayDoctorOffDates(data); // Panggil fungsi untuk menampilkan data
        })
        .catch(error => {
            console.error("Error fetching data:", error);
            document.getElementById("doctor-slider-container").innerHTML = "<p class='no-data'>Terjadi kesalahan saat memuat data.</p>";
        });
}

// Fungsi untuk menampilkan data dalam slider
function displayDoctorOffDates(data) {
    const container = document.getElementById('doctor-slider-container');

    // Pastikan data memiliki properti yang diharapkan dan filter berdasarkan status cuti
    const cutiHariIni = data.cuti_hari_ini || [];
    const cutiAkanDatang = data.cuti_akan_datang || [];

    // Membuat tampilan slider untuk dokter yang sedang cuti hari ini
    if (cutiHariIni.length > 0) {
        container.innerHTML += createSliderHtml(cutiHariIni, 'cuti-hari-ini');
    } else {
        container.innerHTML += "<p class='no-data'>Tidak ada dokter yang sedang cuti hari ini.</p>";
    }

    // Membuat tampilan slider untuk dokter yang akan cuti
    if (cutiAkanDatang.length > 0) {
        container.innerHTML += createSliderHtml(cutiAkanDatang, 'cuti-akan-datang');
    } else {
        container.innerHTML += "<p class='no-data'>Tidak ada dokter yang akan cuti.</p>";
    }

    // Inisialisasi slider setelah data dimuat
    initializeSlider('doctor-slider-cuti-hari-ini', 10000);
    initializeSlider('doctor-slider-cuti-akan-datang', 10000);
}


// Fungsi untuk membuat HTML untuk slider berdasarkan data dokter
function createSliderHtml(doctorData, leaveType) {
    const chunkedData = chunkArray(doctorData, 8); // Mengelompokkan data menjadi chunk per 10 dokter
    let sliderHtml = `
        <div class="leave-category">
            <h3>${leaveType === 'cuti-hari-ini' ? 'Cuti Hari Ini' : 'Cuti yang Akan Datang'}</h3>
            <div class="slider" id="doctor-slider-${leaveType}">
    `;

    chunkedData.forEach((chunk, index) => {
        sliderHtml += `<div class="slide ${index === 0 ? 'active' : ''}">
            <div class="doctor-cards">`;

        chunk.forEach(doctor => {
            sliderHtml += `
                <div class="card ${leaveType === 'cuti-hari-ini' ? 'on-leave-today' : 'will-on-leave'}">
                    <div class="row">
                        <div class="card-img">
                            <img src="${doctor.kode ? '/profile_picture/' + doctor.kode + '.jpg' : 'profile_icon/profile_pict.png'}" alt="${doctor.nama}">
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">${doctor.nama}</h4>
                            ${doctor.cuti.map(cuti => `
                                <span class="badge on-leave-badge">
                                    <strong>
                                        ${
                                            (new Date(cuti.cuti_start).setHours(0, 0, 0, 0) <= new Date().setHours(0, 0, 0, 0) && new Date(cuti.cuti_end).setHours(0, 0, 0, 0) >= new Date().setHours(0, 0, 0, 0))
                                            ? 'CUTI s/d ' + new Date(cuti.cuti_end).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric'})
                                            : (cuti.cuti_start === cuti.cuti_end || (new Date(cuti.cuti_start).setHours(0, 0, 0, 0) >= new Date().setHours(0, 0, 0, 0) && new Date(cuti.cuti_end).setHours(0, 0, 0, 0) > new Date().setHours(0, 0, 0, 0)))
                                            ? 'CUTI pada tgl ' + new Date(cuti.cuti_start).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric'})
                                            : 'CUTI'
                                        }
                                    </strong>
                                </span>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
        });

        sliderHtml += `</div></div>`;
    });

    // Indikator slide
    sliderHtml += 
        `</div>
            <div class="indicators">
                ${chunkedData.map((_, index) => `
                    <span class="indicator ${index === 0 ? 'active-indicator' : ''}" data-index="${index}"></span>                           
                `).join('')}
            </div>
        </div>`;
    return sliderHtml;
}

// Fungsi untuk membagi data menjadi chunk (dalam hal ini per 10 dokter)
function chunkArray(arr, chunkSize) {
    const result = [];
    for (let i = 0; i < arr.length; i += chunkSize) {
        result.push(arr.slice(i, i + chunkSize));
    }
    return result;
}

// Fungsi untuk menginisialisasi slider
function initializeSlider(sliderID, interval = 10000) {
    let currentSlide = 0;
    const slides = document.querySelectorAll(`#${sliderID} .slide`);
    const indicators = document.querySelectorAll(`#${sliderID} + .indicators .indicator`);

    const totalSlides = slides.length;

    if (totalSlides === 0) return; // Jika tidak ada slide, jangan lakukan apa-apa

    const showSlide = (index) => {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });

        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('active-indicator', i === index);
        });
    };

    showSlide(currentSlide);

    const switchSlide = () => {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    };

    setInterval(switchSlide, interval); // Slide otomatis setiap 10 detik.
}
