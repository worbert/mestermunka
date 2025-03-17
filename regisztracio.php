<?php
session_start();

// Ha már be van jelentkezve, átirányít
if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Adatbázis kapcsolat
$host = "127.0.0.1:3306";
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
    $vnev = trim($_POST["vnev"]);
    $knev = trim($_POST["knev"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $telefon = trim($_POST["telefon"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if ($password !== $confirm_password) {
        $message = "A jelszavak nem egyeznek!";
    } else {
        $check = $pdo->prepare("SELECT * FROM users WHERE Username = :username OR Email = :email");
        $check->execute(["username" => $username, "email" => $email]);
        if ($check->rowCount() > 0) {
            $message = "A felhasználónév vagy email már foglalt!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = $pdo->prepare("INSERT INTO users (Vnev, Knev, Username, Email, Telefon, Password, Role) VALUES (:vnev, :knev, :username, :email, :telefon, :password, 0)");
            $query->execute([
                "vnev" => $vnev,
                "knev" => $knev,
                "username" => $username,
                "email" => $email,
                "telefon" => $telefon,
                "password" => $hashed_password
            ]);
            $message = "Sikeres regisztráció! Jelentkezz be!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yamahások - Regisztráció</title>
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
    <div class="register-container">
        <h2>Regisztráció</h2>
        <form method="POST" action="">
            <input type="text" name="vnev" placeholder="Vezetéknév" required>
            <input type="text" name="knev" placeholder="Keresztnév" required>
            <input type="text" name="username" placeholder="Felhasználónév" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="telefon" placeholder="Telefonszám" required>
            <input type="password" name="password" placeholder="Jelszó" required>
            <input type="password" name="confirm_password" placeholder="Jelszó megerősítése" required>
            <button type="submit">Regisztráció</button>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
            <a href="bejelentkezes.php"><button type="button">Bejelentkezés</button></a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>