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

require_once "../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$felado_id = $data["felado_id"];
$cimzett_id = $data["cimzett_id"];
$uzenet = $data["uzenet"];

if (empty($felado_id) || empty($cimzett_id) || empty($uzenet)) {
    echo json_encode(["success" => false, "message" => "Minden mezőt ki kell tölteni!"]);
    exit;
}

$query = $pdo->prepare("INSERT INTO uzenetek (felado_id, cimzett_id, uzenet) VALUES (:felado_id, :cimzett_id, :uzenet)");
$query->execute(["felado_id" => $felado_id, "cimzett_id" => $cimzett_id, "uzenet" => $uzenet]);

echo json_encode(["success" => true, "message" => "Üzenet elküldve!"]);

require_once "../db.php";
header("Content-Type: application/json");

$felado_id = $_GET["felado_id"];
$cimzett_id = $_GET["cimzett_id"];

$query = $pdo->prepare("
    SELECT uzenetek.*, felhasznalok.Username AS felado_nev 
    FROM uzenetek
    JOIN felhasznalok ON uzenetek.felado_id = felhasznalok.id
    WHERE (felado_id = :felado_id AND cimzett_id = :cimzett_id)
       OR (felado_id = :cimzett_id AND cimzett_id = :felado_id)
    ORDER BY kuldes_ido ASC
");
$query->execute(["felado_id" => $felado_id, "cimzett_id" => $cimzett_id]);
$messages = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($messages);

require_once "../db.php";
header("Content-Type: application/json");

$user_id = $_GET["user_id"];

$query = $pdo->prepare("SELECT COUNT(*) AS unreadCount FROM esemenyek WHERE id NOT IN (SELECT esemeny_id FROM olvasott_ertesitesek WHERE user_id = :user_id)");
$query->execute(["user_id" => $user_id]);
$result = $query->fetch(PDO::FETCH_ASSOC);

echo json_encode(["unreadCount" => $result["unreadCount"]]);

require_once "../db.php";
header("Content-Type: application/json");

$user_id = $_POST["user_id"];

$query = $pdo->prepare("INSERT INTO olvasott_ertesitesek (user_id, esemeny_id) SELECT :user_id, id FROM esemenyek WHERE id NOT IN (SELECT esemeny_id FROM olvasott_ertesitesek WHERE user_id = :user_id)");
$query->execute(["user_id" => $user_id]);

echo json_encode(["success" => true]);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}
catch (PDOException $e) {
    die("Hiba az adatbáziskapcsolatban: " . $e->getMessage());
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
?>