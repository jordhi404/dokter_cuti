let cutiHariIni = [];
let cutiAkanDatang = [];

// Mengambil data dari API.
async function fetchData() {
  try {
    const response = await fetch('/data/doctors'); // Ganti dengan URL API yang sesuai ('/dokter_cuti/data/doctors')
    const data = await response.json();

    // Menyimpan data dari backend ke variabel
    cutiHariIni = data.cuti_hari_ini || []; // Default ke array kosong jika undefined
    cutiAkanDatang = data.cuti_akan_datang || []; // Default ke array kosong jika undefined

    console.log("Cuti Hari Ini:", cutiHariIni);
    console.log("Cuti Akan Datang:", cutiAkanDatang);
    console.log("Full API response:", data);

    // Tampilkan slider cuti hari ini terlebih dahulu
    displaySlider(cutiHariIni, 'cuti-hari-ini');
  } catch (error) {
    console.error('Error fetching data:', error);
    const sliderContainer = document.getElementById("doctor-slider-container");
    if (sliderContainer) {
      sliderContainer.innerHTML = "<p class='no-data'>Terjadi kesalahan saat memuat data.</p>";
    }
  }
}

// Panggil fungsi untuk mengambil data saat pertama kali
fetchData();

// Fungsi untuk membuat chunk data.
function chunkArray(array, chunkSize) {
    const result = [];
    for (let i = 0; i < array.length; i += chunkSize) {
        result.push(array.slice(i, i + chunkSize));
    }
    return result;
}

// Fungsi untuk membuat slider HTML dari data dokter.
function createSliderHtml(doctorData, leaveType) {
  if (!doctorData || doctorData.length === 0) {
    return `
      <div class="leave-category">
        <p class="no-data">Tidak ada data untuk ditampilkan.</p>
      </div>
    `;
  }

  const chunkedData = chunkArray(doctorData, 10); // Mengelompokkan data menjadi chunk per 10 dokter

  let sliderHtml = `
    <div class="leave-category">
      <div class="slider" id="doctor-slider-${leaveType}">
  `;

  chunkedData.forEach((chunk, chunkIndex) => {
    sliderHtml += `
      <div class="nested-slider">
    `;

    chunk.forEach(doctor => {
      sliderHtml += `
        <div class="card ${leaveType === 'cuti-hari-ini' ? 'on-leave-today' : 'will-on-leave'}">
          <div class="row">
            <div class="card-img">
              <img src="${doctor.kode ? '/profile_picture/png/' + doctor.kode + '.png' : 'profile_icon/profile_pict.png'}" alt="${doctor.nama}"> 
            </div>
            <div class="card-body">
              <h4 class="card-title">${doctor.nama}</h4>
              ${doctor.cuti.map(cuti => `
                <span class="badge on-leave-badge">
                  <strong>
                    ${
                      (new Date(cuti.cuti_start).setHours(0, 0, 0, 0) === new Date(cuti.cuti_end).setHours(0, 0, 0, 0))
                      ? 'CUTI ' + new Date(cuti.cuti_start).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric'})
                      :(new Date(cuti.cuti_start).setHours(0, 0, 0, 0) <= new Date().setHours(0, 0, 0, 0) && new Date(cuti.cuti_end).setHours(0, 0, 0, 0) >= new Date().setHours(0, 0, 0, 0))
                      ? 'CUTI s.d. ' + new Date(cuti.cuti_end).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric'})
                      : (new Date(cuti.cuti_start).setHours(0, 0, 0, 0) >= new Date().setHours(0, 0, 0, 0) && new Date(cuti.cuti_end).setHours(0, 0, 0, 0) > new Date().setHours(0, 0, 0, 0))
                      ? new Date(cuti.cuti_start).toLocaleDateString('id-ID', { day: 'numeric', month: 'long'}) + ' s.d. ' + new Date(cuti.cuti_end).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric'})
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

    sliderHtml += `
      </div>
    `;
  });

  // Indikator slide untuk nested slider
  sliderHtml += `
      </div>
      <div class="indicators">
        ${chunkedData.map((_, index) => `
          <span class="indicator ${index === 0 ? 'active-indicator' : ''}" data-index="${index}"></span>
        `).join('')}
      </div>
    </div>
  `;

  return sliderHtml;
}

let currentSliderState = 'cuti-hari-ini'; // Mulai dengan cuti hari ini
let nestedSliderIndex = 0; // Indeks nested slider saat ini

// Fungsi untuk memulai animasi nested slider
function startNestedSliderAnimation(leaveType) {
  const nestedSliders = document.querySelectorAll(`.leave-category .nested-slider`);
  const totalNestedSliders = nestedSliders.length;

  let nestedIndex = 0; // Indeks nested slider saat ini

  function showNextNestedSlider() {
    console.log(`Starting animation for: ${leaveType}`);
    console.log('Nested sliders found:', nestedSliders.length);
    console.log('Current nestedIndex:', nestedIndex);

    // Hapus kelas "active" dari semua nested slider
    nestedSliders.forEach(slider => slider.classList.remove('active'));

    // Tambahkan kelas "active" ke slider yang sesuai
    if (nestedIndex < totalNestedSliders) {
      nestedSliders[nestedIndex].classList.add('active');

      // Pindahkan indikator aktif sesuai dengan nestedIndex
      const indicators = document.querySelectorAll(`#doctor-slider-${currentSliderState} + .indicators .indicator`);
      indicators.forEach((indicator, i) => {
          indicator.classList.toggle('active-indicator', i === nestedIndex);
      });
      
      nestedIndex++;

      // Tampilkan slide berikutnya setelah 10 detik
      setTimeout(showNextNestedSlider, 10000);
    } else {
      // Jika semua nested slider selesai, pindah ke slider utama berikutnya
      switchMainSlider();
    }
  }

  setTimeout(showNextNestedSlider, 100);
}

// Fungsi untuk menampilkan slider pada halaman.
function displaySlider(data, leaveType) {
  console.log(`Displaying slider for ${leaveType}:`, data);
  
  const sliderContainer = document.getElementById('doctor-slider-container');
  if (!sliderContainer) {
    console.error('Slider container not found');
    return;
  }

  // Buat HTML slider berdasarkan data
  const sliderHtml = createSliderHtml(data, leaveType);
  sliderContainer.innerHTML = sliderHtml; // Update konten slider

  // Mulai animasi untuk nested slider
  startNestedSliderAnimation(leaveType);
}

// Fungsi untuk mengganti slider utama secara otomatis setelah beberapa detik.
function switchMainSlider() {
  const titleText = document.getElementById('title-text');
  const dateText = document.getElementById('date-text');

  if (currentSliderState === 'cuti-hari-ini') {
    displaySlider(cutiAkanDatang, 'cuti-akan-datang');
    currentSliderState = 'cuti-akan-datang';
    titleText.textContent = 'Dokter Cuti Mendatang';
    titleText.style.background = 'linear-gradient(rgba(255, 20, 147, 0.7), rgba(199, 21, 133, 0.7), rgba(199, 21, 140, 0.7))';
    dateText.textContent = new Date().toLocaleDateString('id-ID', {month: 'long'}).toUpperCase();
  } else {
    displaySlider(cutiHariIni, 'cuti-hari-ini');
    currentSliderState = 'cuti-hari-ini';
    titleText.textContent = 'Dokter Cuti Hari Ini';
    titleText.style.background = 'linear-gradient(rgb(0, 124, 248), rgb(9, 93, 178), rgb(0, 93, 185))';
    dateText.textContent = new Date().toLocaleDateString('id-ID', {weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).replace(/\b[a-z]+\b/gi, match => match.toUpperCase());
  }

  console.log("Switching slider. Current state:", currentSliderState);
}