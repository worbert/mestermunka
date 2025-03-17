<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: bejelentkezes.php");
    exit;
}

$host = "127.0.0.1:3307";
$dbname = "yamahasok";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $pdo->prepare("SELECT Vnev, Knev, Email FROM users WHERE id = :id");
    $query->execute(["id" => $_SESSION["user_id"]]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Adatbázis hiba: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilom</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Vissza a Főoldalra</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Profilom</h2>
        <div class="card">
            <div class="card-body">
                <p><strong>Név:</strong> <?php echo htmlspecialchars($user["Vnev"] . " " . $user["Knev"]); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user["Email"]); ?></p>
                <button class="btn btn-danger" onclick="window.location.href='logout.php'">Kijelentkezés</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>