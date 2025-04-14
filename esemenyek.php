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

if (isset($_POST['register']) && isset($_SESSION['username'])) {
    $eventId = $_POST['eventId'];
    $userQuery = "SELECT id FROM users WHERE Username = :username";
    $userStmt = $conn->prepare($userQuery);
    $userStmt->execute(['username' => $_SESSION['username']]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
    $userId = $user['id'];

    $checkQuery = "SELECT * FROM esemeny_resztvevok WHERE esemeny_id = :eventId AND felhasznalo_id = :userId";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->execute(['eventId' => $eventId, 'userId' => $userId]);

    if ($checkStmt->rowCount() == 0) {
        $insertQuery = "INSERT INTO esemeny_resztvevok (esemeny_id, felhasznalo_id) VALUES (:eventId, :userId)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->execute(['eventId' => $eventId, 'userId' => $userId]);
    }

    header("Location: esemenyek.php?msg=Sikeres+jelentkezés!");
    exit;
}

if (isset($_POST['unregister']) && isset($_SESSION['username'])) {
    $eventId = $_POST['eventId'];
    $userQuery = "SELECT id FROM users WHERE Username = :username";
    $userStmt = $conn->prepare($userQuery);
    $userStmt->execute(['username' => $_SESSION['username']]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
    $userId = $user['id'];

    $deleteQuery = "DELETE FROM esemeny_resztvevok WHERE esemeny_id = :eventId AND felhasznalo_id = :userId";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->execute(['eventId' => $eventId, 'userId' => $userId]);

    header("Location: esemenyek.php?msg=Sikeres+lemondás!");
    exit;
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yamahások - Események</title>
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
        h2 {
            color: #ff0000;
            text-transform: uppercase;
            animation: fadeInDown 1s ease;
        }
        
            .card {
                background: #b0b0b0; 
    border: none;
    transition: transform 0.3s ease;
    color: #333;
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
        .btn-danger {
            transition: transform 0.3s ease;
        }
        .btn-danger:hover {
            transform: scale(1.05);
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
                    <li class="nav-item"><a class="nav-link active" href="esemenyek.php">Események</a></li>
                    <li class="nav-item"><a class="nav-link" href="galeria.php">Galéria</a></li>
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

    <div class="container" id="esemenyek">
        <h2 class="text-center">Események</h2>
        <div class="row">
            <?php
            $currentDateTime = date('Y-m-d H:i:s'); 
            $query = "SELECT * FROM esemenyek";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $eventId = $row['id'];
                $isRegistered = false;
                $eventDateTime = $row['Idopont']; 
                $isExpired = ($eventDateTime < $currentDateTime);

                if (isset($_SESSION['username'])) {
                    $userQuery = "SELECT id FROM users WHERE Username = :username";
                    $userStmt = $conn->prepare($userQuery);
                    $userStmt->execute(['username' => $_SESSION['username']]);
                    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
                    $userId = $user['id'];

                    $checkQuery = "SELECT * FROM esemeny_resztvevok WHERE esemeny_id = :eventId AND felhasznalo_id = :userId";
                    $checkStmt = $conn->prepare($checkQuery);
                    $checkStmt->execute(['eventId' => $eventId, 'userId' => $userId]);
                    $isRegistered = $checkStmt->rowCount() > 0;
                }
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if (!empty($row['KepURL'])): ?>
                            <img src="<?php echo htmlspecialchars($row['KepURL']); ?>" class="card-img-top" alt="Esemény képe" style="max-height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['Helyszin']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['Idopont']); ?></p>
                            <?php if (isset($_SESSION['username'])): ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
                                    <?php if ($isRegistered): ?>
                                        <button type="submit" name="unregister" class="btn btn-danger">Lemondás</button>
                                    <?php elseif (!$isExpired): ?>
                                        <button type="submit" name="register" class="btn btn-primary">Jelentkezés</button>
                                    <?php else: ?>
                                        <p class="text-muted">Az esemény lejárt</p>
                                    <?php endif; ?>
                                </form>
                            <?php else: ?>
                                <p><a href="bejelentkezes.php">Jelentkezz be a részvételhez!</a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php if (isset($_GET['msg'])): ?>
                <script>alert('<?php echo htmlspecialchars($_GET['msg']); ?>');</script>
            <?php endif; ?>
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