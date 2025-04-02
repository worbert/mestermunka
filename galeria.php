<?php
session_start();

// Adatbázis kapcsolat localhosttal
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
                    <li class="nav-item dropdown">
                        <?php if (isset($_SESSION["username"])): ?>
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Üdv, <?php echo htmlspecialchars($_SESSION["username"]); ?>!
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profil.php">Profilom</a></li>
                            <li><a class="dropdown-item" href="admin.php">Admin</a></li>
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

    <div class="container mt-5 pt-5" id="galeria">
        <center><h2>Galéria</h2></center>
        <div class="row">
            <?php
            $query = "SELECT k.*, u.Username FROM kepek k LEFT JOIN users u ON k.feltolto_id = u.id";
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>