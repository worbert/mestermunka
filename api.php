<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

// Adatbázis kapcsolat
$host = "localhost";
$dbname = "yamahasok";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["success" => false, "message" => "Hiba az adatbázis kapcsolatban: " . $e->getMessage()]));
}

// JSON adatok beolvasása
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["username"], $data["email"], $data["password"])) {
    echo json_encode(["success" => false, "message" => "Hiányzó adatok"]);
    exit;
}

$username = trim($data["username"]);
$email = trim($data["email"]);
$password = password_hash($data["password"], PASSWORD_DEFAULT);

// Ellenőrizzük, hogy létezik-e már a felhasználó
$checkQuery = $pdo->prepare("SELECT * FROM felhasznalok WHERE Username = :username OR Email = :email");
$checkQuery->execute(["username" => $username, "email" => $email]);

if ($checkQuery->rowCount() > 0) {
    echo json_encode(["success" => false, "message" => "Ez a felhasználónév vagy email már létezik!"]);
    exit;
}

// Adatok beszúrása
$query = $pdo->prepare("INSERT INTO felhasznalok (Username, Email, Jelszo) VALUES (:username, :email, :password)");
$success = $query->execute(["username" => $username, "email" => $email, "password" => $password]);

if ($success) {
    echo json_encode(["success" => true, "message" => "Sikeres regisztráció!"]);
} else {
    echo json_encode(["success" => false, "message" => "Hiba történt a regisztráció során!"]);
}

session_start();
header("Content-Type: application/json");

// Adatbázis kapcsolat
$host = "localhost";
$dbname = "yamahasok";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["success" => false, "message" => "Hiba az adatbázis kapcsolatban: " . $e->getMessage()]));
}

// JSON adatok beolvasása
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["username"], $data["password"])) {
    echo json_encode(["success" => false, "message" => "Hiányzó adatok"]);
    exit;
}

$username = trim($data["username"]);
$password = trim($data["password"]);

// Felhasználó keresése
$query = $pdo->prepare("SELECT * FROM felhasznalok WHERE Username = :username");
$query->execute(["username" => $username]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["Jelszo"])) {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["Username"];

    echo json_encode(["success" => true, "message" => "Sikeres bejelentkezés!", "username" => $user["Username"]]);
} else {
    echo json_encode(["success" => false, "message" => "Hibás felhasználónév vagy jelszó!"]);
}

?>
