<?php
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