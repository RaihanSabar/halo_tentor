<?php
include 'admin/includes/koneksi.php'; // Sertakan koneksi ke database

// Ambil nomor WhatsApp dari kolom no_tlp di tabel admin
$stmt = $conn->query("SELECT no_tlp FROM `admin` LIMIT 1");
$whatsappSetting = $stmt->fetch(PDO::FETCH_ASSOC);
$whatsappNumber = $whatsappSetting['no_tlp'];
?>

<!-- Bagian Footer -->
<a href="https://wa.me/<?php echo $whatsappNumber; ?>" class="fixed-chat"> 
    <img src="assets/images/whatsapp-icon.png" alt="Whatsapp Icon"><b>DAFTAR SEKARANG</b>
</a>

<footer class="footer">
    <div class="footer-content">
        <p class="copyright">Copyright © 2024 Halo Tentor</p>
        <div class="social-media">
            <a href="https://www.instagram.com/halotentor" class="social-icon-link" data-link="https://www.instagram.com/halotentor" target="_blank">
                <img src="assets/images/instagram-icon.png" alt="Instagram" class="social-icon">
            </a>
            <div class="link-tooltip"></div>
            <!-- Tambahkan lebih banyak ikon sesuai kebutuhan -->
        </div>
    </div>
</footer>

<style>
.social-icon-link {
    position: relative;
}

.link-tooltip {
    display: none; /* Sembunyikan link secara default */
    position: absolute;
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 5px;
    z-index: 10;
    white-space: nowrap; /* Mencegah teks membungkus */
    pointer-events: none; /* Menghindari interaksi */
}
</style>

<script>
document.querySelectorAll('.social-icon-link').forEach(link => {
    let timer;
    const tooltip = document.querySelector('.link-tooltip');

    link.addEventListener('mouseenter', (event) => {
        timer = setTimeout(() => {
            tooltip.textContent = link.getAttribute('data-link'); // Ambil link
            tooltip.style.display = 'block'; // Tampilkan tooltip

// Posisi tooltip di atas kursor mouse
tooltip.style.left = `${event.pageX}px`;
tooltip.style.top = `${event.pageY - tooltip.offsetHeight}px`; // Di atas kursor
}, 1500);
});

link.addEventListener('mouseleave', () => {
clearTimeout(timer);
tooltip.style.display = 'none'; // Sembunyikan tooltip
});

link.addEventListener('mousemove', (event) => {
// Update posisi tooltip saat mouse bergerak
tooltip.style.left = `${event.pageX}px`;
tooltip.style.top = `${event.pageY - tooltip.offsetHeight}px`; // Di atas kursor
});
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbarLinks = document.querySelectorAll('.navbar-nav .nav-link');

        navbarLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                const targetId = this.getAttribute('href'); // Ambil href dari link
                const targetElement = document.querySelector(targetId); // Ambil elemen target

                if (targetElement) {
                    event.preventDefault(); // Mencegah perilaku default

                    // Scroll ke posisi target dengan offset
                    const offset = 150; // Ubah nilai ini sesuai kebutuhan
                    const elementPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
                    const offsetPosition = elementPosition - offset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth' // Efek scroll halus
                    });
                }
            });
        });
    });
</script>