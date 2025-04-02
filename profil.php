<?php
session_start();

// Session naplózás
error_log("Session tartalma: " . print_r($_SESSION, true));

// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó, és az user_id érvényes-e
if (!isset($_SESSION["user_id"]) || !is_numeric($_SESSION["user_id"]) || $_SESSION["user_id"] <= 0) {
    error_log("Session user_id nem található vagy érvénytelen: " . print_r($_SESSION, true));
    header("Location: http://localhost/bejelentkezes.php");
    exit;
}

// Adatbázis kapcsolati konstansok
define("DB_HOST", "localhost");
define("DB_NAME", "yamahasok");
define("DB_USER", "root");
define("DB_PASS", "");

try {
    // PDO kapcsolat létrehozása
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("Adatbázis kapcsolat sikeres");
    
    // Felhasználó adatainak lekérdezése
    $query = $pdo->prepare("SELECT Vnev, Knev, Email, Telefon, profil_kep FROM users WHERE id = :id");
    $query->execute(["id" => $_SESSION["user_id"]]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        die("Hiba: Nincs ilyen felhasználó az adatbázisban.");
    }
    
    // Profilkép URL mentése
    $errorMessage = $successMessage = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["profil_kep_url"])) {
        $profilKepUrl = filter_input(INPUT_POST, "profil_kep_url", FILTER_VALIDATE_URL);
        
        // URL validáció
        if (!$profilKepUrl) {
            $errorMessage = "Hiba: Érvénytelen URL formátum. Kérlek, adj meg egy érvényes URL-t (pl. https://example.com/image.jpg).";
        } else {
            try {
                // Profilkép URL mentése az adatbázisba
                $stmt = $pdo->prepare("UPDATE users SET profil_kep = :profil_kep WHERE id = :id");
                $stmt->execute(["profil_kep" => $profilKepUrl, "id" => $_SESSION["user_id"]]);
                error_log("Profilkép URL sikeresen mentve: " . $profilKepUrl);
                $successMessage = "Profilkép URL sikeresen frissítve!";
                header("Location: http://localhost/profil.php");
                exit;
            } catch (PDOException $e) {
                error_log("Adatbázis hiba a profilkép URL mentésekor: " . $e->getMessage());
                $errorMessage = "Hiba: Nem sikerült menteni a profilkép URL-t. Kérlek, próbáld újra.";
            }
        }
    }
} catch (PDOException $e) {
    error_log("Adatbázis hiba: " . $e->getMessage());
    die("Adatbázis hiba: " . $e->getMessage());
}

// Alapértelmezett profilkép kezelése
$profilePic = $user['profil_kep'] ? $user['profil_kep'] : 'https://via.placeholder.com/150?text=Nincs+kép';
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
                    <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profilkép" class="rounded-circle img-fluid" width="150" height="150">
                </div>
                <form action="/profil.php" method="POST">
                    <div class="mb-3">
                        <input type="url" name="profil_kep_url" class="form-control" placeholder="Profilkép URL (pl. https://example.com/image.jpg)" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Profilkép URL mentése</button>
                </form>
                <?php if ($errorMessage): ?>
                    <p class="text-danger mt-3"><?php echo $errorMessage; ?></p>
                <?php endif; ?>
                <?php if ($successMessage): ?>
                    <p class="text-success mt-3"><?php echo $successMessage; ?></p>
                <?php endif; ?>
                <hr>
                <p><strong>Név:</strong> <?php echo htmlspecialchars($user["Vnev"] . " " . $user["Knev"]); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user["Email"]); ?></p>
                <p><strong>Telefonszám:</strong> <?php echo htmlspecialchars($user["Telefon"]); ?></p>
                <button class="btn btn-danger" onclick="if(confirm('Biztosan ki szeretnél jelentkezni?')) window.location.href='logout.php'">Kijelentkezés</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>