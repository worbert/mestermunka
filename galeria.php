<?php
session_start();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yamahások - Galéria</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION["username"])): ?>
                        <li class="nav-item">
                            <span class="nav-link">Üdv, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profil.php">Profilom</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Kijelentkezés</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="bejelentkezes.php">Bejelentkezés</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5 pt-5" id="galeria">
        <center><h2>Galéria</h2></center>
        <a href="https://fb.me/e/3ItbF66GW">
            <img src="img/kakucs2024.jpg" width="40%" height="40%">
        </a>
        <a href="https://fb.me/e/1Kd1ing1F">
            <img src="img/piknik202404.jpg" width="40%" height="50%" style="margin-left: 250px;">
        </a>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-bottom">
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

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>