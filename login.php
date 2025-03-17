<?php
session_start();
header("Content-Type: application/json");

// Adatbázis kapcsolat
$host = "3306";
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
$query = $pdo->prepare("SELECT * FROM users WHERE Username = :username");
$query->execute(["username" => $username]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["Password"])) {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["Username"];
    $_SESSION["role"] = $user["Role"];
    echo json_encode([
        "success" => true,
        "message" => "Sikeres bejelentkezés!",
        "username" => $user["Username"],
        "role" => $user["Role"]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Hibás felhasználónév vagy jelszó!"]);
}
?>