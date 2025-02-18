<?php
$host = "localhost";  // Szerver címe
$dbname = "yamahasok"; // Az adatbázis neve
$username = "root";  // Ha van más felhasználó, állítsd be
$password = "";  // Ha van jelszó, add meg

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Hiba a kapcsolat létrehozásakor: " . $e->getMessage());
}

require_once "../db.php";

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$username = $data["username"];
$password = $data["password"];

$query = $pdo->prepare("SELECT * FROM felhasznalok WHERE Username = :username");
$query->execute(["username" => $username]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["Jelszo"])) {
    echo json_encode(["success" => true, "message" => "Sikeres bejelentkezés!"]);
} else {
    echo json_encode(["success" => false, "message" => "Hibás felhasználónév vagy jelszó!"]);
}

require_once "../db.php";

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"];
$username = $data["username"];
$password = password_hash($data["password"], PASSWORD_DEFAULT);

$checkQuery = $pdo->prepare("SELECT * FROM felhasznalok WHERE Username = :username OR Email = :email");
$checkQuery->execute(["username" => $username, "email" => $email]);

if ($checkQuery->rowCount() > 0) {
    echo json_encode(["success" => false, "message" => "Ez a felhasználónév vagy email már létezik!"]);
    exit;
}

$query = $pdo->prepare("INSERT INTO felhasznalok (Vnev, Knev, Username, Email, Telefon, Jelszo, Jogosultsag) VALUES ('', '', :username, :email, '', :password, 0)");
$query->execute(["username" => $username, "email" => $email, "password" => $password]);

echo json_encode(["success" => true, "message" => "Sikeres regisztráció!"]);
?>