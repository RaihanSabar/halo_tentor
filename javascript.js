let currentIndex = 0; // Menyimpan indeks gambar saat ini
const images = document.querySelectorAll('.image-container img'); // Mengambil semua gambar
const totalImages = images.length; // Total gambar

// Menampilkan gambar pertama
images[currentIndex].classList.add('active');

function updateSlide() {
    // Sembunyikan gambar saat ini
    images[currentIndex].classList.remove('active');
    
    // Update indeks untuk gambar berikutnya
    currentIndex = (currentIndex + 1) % totalImages; // Kembali ke 0 jika sudah mencapai akhir
    
    // Tampilkan gambar berikutnya
    images[currentIndex].classList.add('active');
}

// Mengatur interval untuk mengganti gambar setiap 5 detik
setInterval(updateSlide, 5000)