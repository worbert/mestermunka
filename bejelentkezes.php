<?php
session_start();

// Ha már be van jelentkezve, átirányít
if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Adatbázis kapcsolat
$host = "localhost"; // Ellenőrizd a portot!
$dbname = "yamahasok";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Adatbázis hiba: " . $e->getMessage());
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $query = $pdo->prepare("SELECT * FROM users WHERE Username = :username");
    $query->execute(["username" => $username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["Password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["Username"];
        $_SESSION["role"] = $user["Role"];
        header("Location: index.php");
        exit;
    } else {
        $message = "Hibás felhasználónév vagy jelszó!";
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yamahások - Bejelentkezés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="img/yamahasok_logo.jpg" width="90px" height="60px" class="me-2" alt="Logo">
            </a>
        </div>
    </nav>
    <div class="login-container">
        <h2>Bejelentkezés</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Felhasználónév" required>
            <input type="password" name="password" placeholder="Jelszó" required>
            <button type="submit">Bejelentkezés</button>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
            <a href="regisztracio.php"><button type="button">Regisztráció</button></a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>