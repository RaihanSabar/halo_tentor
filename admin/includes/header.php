<?php
// Mulai sesi hanya jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<header class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" onclick="toggleSidebar()">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="ml-auto profile-container">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $_SESSION['username']; ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="profile.php">Profil Saya</a>
                            <a class="dropdown-item" href="setting_confirm.php">Pengaturan</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>