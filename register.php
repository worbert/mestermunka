<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

// Adatbázis kapcsolat
$host = "127.0.0.1:3306"; // A te phpMyAdmin konfigurációd alapján
$dbname = "yamahasok";
$username = "root"; // Cseréld le, ha nem root-ot használsz
$password = ""; // Add meg a jelszót, ha van

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["success" => false, "message" => "Hiba az adatbázis kapcsolatban: " . $e->getMessage()]));
}

// JSON adatok beolvasása
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["vnev"], $data["knev"], $data["username"], $data["email"], $data["telefon"], $data["password"])) {
    echo json_encode(["success" => false, "message" => "Hiányzó adatok"]);
    exit;
}

$vnev = trim($data["vnev"]);
$knev = trim($data["knev"]);
$username = trim($data["username"]);
$email = trim($data["email"]);
$telefon = trim($data["telefon"]);
$password = password_hash($data["password"], PASSWORD_DEFAULT);
$role = 0; // Alapértelmezett szerepkör (nem admin)

// Ellenőrizzük, hogy létezik-e már a felhasználónév vagy email
$checkQuery = $pdo->prepare("SELECT * FROM users WHERE Username = :username OR Email = :email");
$checkQuery->execute(["username" => $username, "email" => $email]);

if ($checkQuery->rowCount() > 0) {
    echo json_encode(["success" => false, "message" => "Ez a felhasználónév vagy email már létezik!"]);
    exit;
}

// Adatok beszúrása
$query = $pdo->prepare("INSERT INTO users (Vnev, Knev, Username, Email, Telefon, Password, Role) VALUES (:vnev, :knev, :username, :email, :telefon, :password, :role)");
$success = $query->execute([
    "vnev" => $vnev,
    "knev" => $knev,
    "username" => $username,
    "email" => $email,
    "telefon" => $telefon,
    "password" => $password,
    "role" => $role
]);

if ($success) {
    echo json_encode(["success" => true, "message" => "Sikeres regisztráció!"]);
} else {
    echo json_encode(["success" => false, "message" => "Hiba történt a regisztráció során!"]);
}
?>