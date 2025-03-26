<?php
session_start();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yamahások</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="img/yamahasok_logo.jpg" width="90px" height="60px" class="me-2" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="rolunk.php">Rólunk</a></li>
                    <li class="nav-item"><a class="nav-link" href="alapitonk.php">Alapítónk</a></li>
                    <li class="nav-item"><a class="nav-link" href="esemenyek.php">Események</a></li>
                    <li class="nav-item"><a class="nav-link active" href="galeria.php">Galéria</a></li>
                    <li classnav-item><a class="nav-link" href="admin.php">admin</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <?php if (isset($_SESSION["username"])): ?>
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Üdv, <?php echo htmlspecialchars($_SESSION["username"]); ?>!
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profil.php">Profilom</a></li>
                            <li><a class="dropdown-item" href="logout.php">Kijelentkezés</a></li>
                        </ul>
                        <?php else: ?>
                            <a class="nav-link" href="bejelentkezes.php">Bejelentkezés</a>
                        <?php endif; ?>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5 position-relative" style="max-width: 1000px; height: 500px; overflow: hidden;">
        <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner" style="height: 80%;">
                <div class="carousel-item active">
                    <a href="rolunk.php">
                        <img src="img/rolunk_img.png" class="d-block w-100" style="height: 60%; object-fit: contain;" alt="Kép 1">
                    </a>
                </div>
                <div class="carousel-item">
                    <img src="img/kakucs2024.jpg" class="d-block w-100" style="height: 20%; object-fit: contain;" alt="Kép 2">
                </div>
                <div class="carousel-item">
                    <img src="img/piknik202404.jpg" class="d-block w-100" style="height: 60%; object-fit: contain;" alt="Kép 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev" style="top: 50%; transform: translateY(-50%);">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Előző</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next" style="top: 50%; transform: translateY(-50%);">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Következő</span>
            </button>
        </div>
    </div>


    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-bottom">
        <div class="container-fluid d-flex justify-content-left align-items-left">
            <a class="text-white me3" href="adatvédelmi nyilatkozat.pdf">Adatvédelmi nyilatkozat</a>
        </div>
        <div class="container-fluid d-flex justify-content-center align-items-center">
            <span class="text-white me-3">Elérhetőségek:</span>
            <a href="https://www.facebook.com/groups/662406200502336" target="_blank" class="text-white me-3">
                <i class="bi bi-facebook" style="font-size: 1.5rem;"></i>
            </a>
            <a href="mailto:yamahasok@gmail.com" target="_blank" class="text-white">
                <i class="bi bi-envelope-fill" style="font-size: 1.5rem;"></i>
            </a>
        </div>
    </nav>

    <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="auth.js"></script>
    <script src="script.js"></script>
</body>
</html>