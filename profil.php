<?php
session_start();

// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
if (!isset($_SESSION["user_id"])) {
    header("Location: bejelentkezes.php");
    exit;
}

// Adatbázis kapcsolati adatok
$host = "localhost";
$dbname = "yamahasok";
$username = "root";
$password = "";

try {
    // PDO kapcsolat létrehozása
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Felhasználó adatainak lekérdezése
    $query = $pdo->prepare("SELECT Vnev, Knev, Email, Telefon, profile_pic FROM users WHERE id = :id");
    $query->execute(["id" => $_SESSION["user_id"]]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        die("Hiba: Nincs ilyen felhasználó az adatbázisban.");
    }
    
    // Profilkép feltöltés feldolgozása
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_pic"])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $fileName = "profile_" . $_SESSION["user_id"] . "." . strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));
        $targetFilePath = $targetDir . $fileName;
        
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), $allowedTypes)) {
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFilePath)) {
                $stmt = $pdo->prepare("UPDATE users SET profile_pic = :profile_pic WHERE id = :id");
                $stmt->execute(["profile_pic" => $fileName, "id" => $_SESSION["user_id"]]);
                header("Location: profile.php");
                exit;
            } else {
                echo "<p class='text-danger'>Hiba: Nem sikerült feltölteni a fájlt.</p>";
            }
        } else {
            echo "<p class='text-danger'>Hiba: Csak JPG, JPEG, PNG és GIF fájlokat tölthetsz fel.</p>";
        }
    }
} catch (PDOException $e) {
    die("Adatbázis hiba: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilom - Yamahások</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Vissza a Főoldalra</a>
            <?php if (isset($_SESSION["username"])): ?>
                <span class="navbar-text text-white me-3">
                    Üdv, <?php echo htmlspecialchars($_SESSION["username"]); ?>!
                </span>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Profilom</h2>
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <img src="uploads/<?php echo htmlspecialchars($user['profile_pic'] ?? 'default.png'); ?>" alt="Profilkép" class="rounded-circle" width="150" height="150">
                </div>
                <form action="profile.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="profile_pic" class="form-control mb-3" accept="image/*" required>
                    <button type="submit" class="btn btn-primary">Profilkép feltöltése</button>
                </form>
                <hr>
                <p><strong>Név:</strong> <?php echo htmlspecialchars($user["Vnev"] . " " . $user["Knev"]); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user["Email"]); ?></p>
                <p><strong>Telefonszám:</strong> <?php echo htmlspecialchars($user["Telefon"]); ?></p>
                <button class="btn btn-danger" onclick="window.location.href='logout.php'">Kijelentkezés</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>