<?php
session_start();

$host = "localhost";
$dbname = "yamahasok";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kapcsolódási hiba: " . $e->getMessage());
}

if (isset($_POST['uploadImage']) && isset($_SESSION['user_id'])) {
    try {
        $imageUrl = $_POST['imageUrl'];
        $date = date('Y-m-d');
        $uploaderId = $_SESSION['user_id']; 

        $query = "INSERT INTO kepek (feltolto_id, Datum, KepURL, approved) VALUES (:uploaderId, :date, :imageUrl, 0)";
        $stmt = $conn->prepare($query);
        $stmt->execute(['uploaderId' => $uploaderId, 'date' => $date, 'imageUrl' => $imageUrl]);
        $message = "Kép feltöltve, admin jóváhagyásra vár!";
    } catch (PDOException $e) {
        $message = "Hiba a kép feltöltése közben: " . $e->getMessage();
    }
} elseif (isset($_POST['uploadImage']) && !isset($_SESSION['user_id'])) {
    $message = "Hiba: Nincs bejelentkezett felhasználó!";
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yamahások - Galéria</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            overflow-x: hidden;
        }

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

        .container {
            padding-top: 120px;
            padding-bottom: 50px;
        }
        h2, h3 {
            color: #ff0000;
            text-transform: uppercase;
            animation: fadeInDown 1s ease;
        }
        .card {
            background: #2a2a2a;
            border: none;
            transition: transform 0.3s ease;
            color:#fff;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-title {
            color: #ff0000;
        }
        .btn-primary {
            background-color: #ff0000;
            border-color: #ff0000;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #e60000;
            border-color: #e60000;
        }
        .form-control {
            background: #333;
            color: #fff;
            border: 1px solid #555;
        }
        .form-control:focus {
            background: #333;
            color: #fff;
            border-color: #ff0000;
            box-shadow: none;
        }
        .alert-success {
            background: #28a745;
            color: #fff;
        }
        .alert-danger {
            background: #dc3545;
            color: #fff;
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
                    <li class="nav-item"><a class="nav-link" href="esemenyek.php">Események</a></li>
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

    <div class="container" id="galeria">
        <center><h2>Galéria</h2></center>

        <?php if (isset($message)): ?>
            <div class="alert <?php echo strpos($message, 'Hiba') === false ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION["username"])): ?>
            <h3>Kép feltöltése</h3>
            <form method="POST" action="galeria.php" class="mb-4">
                <div class="mb-3">
                    <input type="url" id="imageUrl" name="imageUrl" class="form-control" placeholder="Kép URL (pl. https://example.com/kep.jpg)" required>
                </div>
                <button type="submit" name="uploadImage" class="btn btn-primary">Feltöltés</button>
            </form>
        <?php endif; ?>

        <div class="row">
            <?php
            $query = "SELECT k.*, u.Username FROM kepek k LEFT JOIN users u ON k.feltolto_id = u.id WHERE k.approved = 1";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if (!empty($row['KepURL'])): ?>
                            <img src="<?php echo htmlspecialchars($row['KepURL']); ?>" class="card-img-top" alt="Galéria kép" style="max-height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title">Feltöltő: <?php echo htmlspecialchars($row['Username'] ?? 'Ismeretlen'); ?></h5>
                            <p class="card-text">Dátum: <?php echo htmlspecialchars($row['Datum']); ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
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
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-top');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>