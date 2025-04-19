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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        /* Alapvető testreszabások */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            overflow-x: hidden;
        }

        /* Felső navigációs sáv */
        .navbar-top {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            transition: background 0.3s ease;
        }
        .navbar-top.scrolled {
            background: #000;
        }
        .navbar-brand img {
            width: 100px;
            height: 70px;
            transition: transform 0.3s ease;
        }
        .navbar-brand img:hover {
            transform: rotate(5deg);
        }
        .navbar-nav .nav-link {
            font-weight: 600;
            color: #fff;
            margin: 0 15px;
            position: relative;
            transition: color 0.3s ease;
        }
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: #ff0000;
            bottom: -5px;
            left: 0;
            transition: width 0.3s ease;
        }
        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }
        .navbar-nav .nav-link:hover {
            color: #ff0000;
        }

        /* Carousel */
        .carousel-container {
    position: relative;
    height: 100vh;
    overflow: hidden;
}
.carousel-inner, .carousel-item {
    height: 100%;
    width: 100%;
}
.carousel-item img {
    height: 100%;
    width: 100%;
    object-fit: cover;
    object-position: center;
    filter: brightness(70%);
    transform: scale(1.1);
    transition: transform 5s ease;
}
.carousel-item.active img {
    transform: scale(1);
}
.carousel-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7));
}
.carousel-overlay h1 {
    font-size: 4rem;
    font-weight: 800;
    text-transform: uppercase;
    color: #fff;
    text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.7);
    animation: fadeInDown 1s ease;
}
.carousel-overlay h1 span {
    color: #ff0000;
}
.custom-carousel-control {
    width: auto; /* Minimális szélesség, csak a nyíl köré */
    padding: 0 15px; /* Kisméretű padding a nyíl körül */
    background: none !important; /* Nincs háttérszín */
    transition: none; /* Nincs háttérátmenet */
}
.custom-carousel-control:hover {
    background: none !important; /* Hover esetén se legyen háttér */
}
.carousel-control-prev-icon, .carousel-control-next-icon {
    background-image: none;
    font-family: 'bootstrap-icons';
    color: #fff;
    font-size: 2rem;
}
.carousel-control-prev-icon::before {
    content: '\F284'; /* Bootstrap Icons: chevron-left */
}
.carousel-control-next-icon::before {
    content: '\F285'; /* Bootstrap Icons: chevron-right */
}

        .navbar-bottom {
            background: #000;
            padding: 20px 30px;
            position: relative;
            overflow: hidden;
        }
        .navbar-bottom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            opacity: 0.2;
        }
        .navbar-bottom .navbar-text a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .navbar-bottom .navbar-text a:hover {
            color: #ff0000;
        }
        .navbar-bottom .navbar-text i {
            font-size: 2rem;
            margin: 0 10px;
            transition: transform 0.3s ease, color 0.3s ease;
        }
        .navbar-bottom .navbar-text i:hover {
            transform: scale(1.2);
            color: #ff0000;
        }
        a {
    text-decoration: none;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="img/yamahasok_logo.jpg" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="rolunk.php">Rólunk</a></li>
                <li class="nav-item"><a class="nav-link" href="alapitonk.php">Alapítónk</a></li>
                <li class zoom="nav-item"><a class="nav-link" href="esemenyek.php">Események</a></li>
                <li class="nav-item"><a class="nav-link active" href="galeria.php">Galéria</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <?php if (isset($_SESSION["username"])): ?>
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Üdv, <?php echo htmlspecialchars($_SESSION["username"]); ?>!
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php">Profilom</a></li>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                                    <li><a class="dropdown-item" href="admin.php">Admin</a></li>
                                <?php endif; ?>
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

<div class="container-fluid carousel-container position-relative">
    <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <a href="rolunk.php">
                    <img src="img/rolunk_img.jpg" class="d-block" alt="Kép 1">
                    <div class="carousel-overlay">
                        <h1>Rólunk <span>Yamahások</span></h1>
                    </div>
                </a>
            </div>
            <div class="carousel-item">
                <img src="img/alapito.jpg" class="d-block" alt="Kép 2">
                <div class="carousel-overlay">
                    <h1>Alapitónk <span>Dávid János</span></h1>
                </div>
            </div>
            <div class="carousel-item">
                <a href="esemenyek.php">
                    <img src="img/piknik202404.jpg" class="d-block" alt="Események kép">
                    <div class="carousel-overlay">
                        <h1>Esem<span>ények</span></h1>
                    </div>
                </a>
            </div>

            <div class="carousel-item">
                <a href="galeria.php">
                    <img src="img/galeria.jpg" class="d-block" alt="Galéria kép">
                    <div class="carousel-overlay">
                        <h1>Galé<span>ria</span></h1>
                    </div>
                </a>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Előző</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Következő</span>
        </button>
    </div>
</div>

<nav class="navbar navbar-black bg-black fixed-bottom custom-navbar">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex justify-content-start">
            <a class="text-white me-3 text-size" href="adatvédelmi nyilatkozat.pdf">Adatvédelmi nyilatkozat</a>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <span class="text-white me-3 text-size">Elérhetőségek:</span>
            <a href="https://www.facebook.com/groups/662406200502336" target="_blank" rel="noopener noreferrer" class="text-white me-3">
                <i class="bi bi-facebook icon-size"></i>
            </a>
            <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&to=yamahasok@gmail.com" target="_blank" rel="noopener noreferrer" class="text-white">
                <i class="bi bi-envelope-fill icon-size"></i>
            </a>
        </div>
        <div class="d-flex justify-content-end" style="width: 150px;"></div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>