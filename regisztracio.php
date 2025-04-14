<?php
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$host = "localhost";
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

        .register-container {
            max-width: 500px;
            margin: 120px auto 50px;
            padding: 30px;
            background: #2a2a2a;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.3);
            text-align: center;
        }
        .register-container h2 {
            color: #ff0000;
            text-transform: uppercase;
            animation: fadeInDown 1s ease;
            margin-bottom: 20px;
        }
        .register-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background: #333;
            color: #fff;
            border: 1px solid #555;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }
        .register-container input:focus {
            border-color: #ff0000;
            outline: none;
            box-shadow: none;
        }
        .register-container button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #ff0000;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .register-container button:hover {
            background-color: #e60000;
            transform: scale(1.05);
        }
        .register-container p {
            color: #ff0000;
            margin-top: 10px;
        }
        .register-container a button {
            background-color: #555;
        }
        .register-container a button:hover {
            background-color: #666;
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
            <p><?php echo htmlspecialchars($message); ?></p>
            <a href="bejelentkezes.php"><button type="button">Bejelentkezés</button></a>
        </form>
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