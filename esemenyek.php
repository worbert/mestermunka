<?php
session_start();


$host = "localhost";
$dbname = "yamahasok";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kapcsolódási hiba: " . $e->getMessage());
}


if (isset($_POST['register']) && isset($_SESSION['username'])) {
    $eventId = $_POST['eventId'];
    $userQuery = "SELECT id FROM users WHERE Username = :username";
    $userStmt = $conn->prepare($userQuery);
    $userStmt->execute(['username' => $_SESSION['username']]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
    $userId = $user['id'];

    $checkQuery = "SELECT * FROM esemeny_resztvevok WHERE esemeny_id = :eventId AND felhasznalo_id = :userId";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->execute(['eventId' => $eventId, 'userId' => $userId]);

    if ($checkStmt->rowCount() == 0) {
        $insertQuery = "INSERT INTO esemeny_resztvevok (esemeny_id, felhasznalo_id) VALUES (:eventId, :userId)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->execute(['eventId' => $eventId, 'userId' => $userId]);
    }

    header("Location: esemenyek.php?msg=Sikeres+jelentkezés!");
    exit;
}


if (isset($_POST['unregister']) && isset($_SESSION['username'])) {
    $eventId = $_POST['eventId'];
    $userQuery = "SELECT id FROM users WHERE Username = :username";
    $userStmt = $conn->prepare($userQuery);
    $userStmt->execute(['username' => $_SESSION['username']]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
    $userId = $user['id'];

    $deleteQuery = "DELETE FROM esemeny_resztvevok WHERE esemeny_id = :eventId AND felhasznalo_id = :userId";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->execute(['eventId' => $eventId, 'userId' => $userId]);

    header("Location: esemenyek.php?msg=Sikeres+lemondás!");
    exit;
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yamahások - Események</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="img/yamahasok_logo.jpg" width="90px" height="60px" class="me-2" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="rolunk.php">Rólunk</a></li>
                    <li class="nav-item"><a class="nav-link" href="alapitonk.php">Alapítónk</a></li>
                    <li class="nav-item"><a class="nav-link active" href="esemenyek.php">Események</a></li>
                    <li class="nav-item"><a class="nav-link" href="galeria.php">Galéria</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <?php if (isset($_SESSION["username"])): ?>
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Üdv, <?php echo htmlspecialchars($_SESSION["username"]); ?>!
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profilom</a></li>
                        <li><a class="dropdown-item" href="admin.php">Admin</a></li>
                        <li><a class="dropdown-item" href="logout.php">Kijelentkezés</a></li>
                    </ul>
                    <?php else: ?>
                        <a class="nav-link" href="bejelentkezes.php">Bejelentkezés</a>
                    <?php endif; ?>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5" id="esemenyek">
        <h2 class="text-center">Események</h2>
        <div class="row">
            <?php
            $query = "SELECT * FROM esemenyek";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $eventId = $row['id'];
                $isRegistered = false;

                if (isset($_SESSION['username'])) {
                    $userQuery = "SELECT id FROM users WHERE Username = :username";
                    $userStmt = $conn->prepare($userQuery);
                    $userStmt->execute(['username' => $_SESSION['username']]);
                    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
                    $userId = $user['id'];

                    $checkQuery = "SELECT * FROM esemeny_resztvevok WHERE esemeny_id = :eventId AND felhasznalo_id = :userId";
                    $checkStmt = $conn->prepare($checkQuery);
                    $checkStmt->execute(['eventId' => $eventId, 'userId' => $userId]);
                    $isRegistered = $checkStmt->rowCount() > 0;
                }
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if (!empty($row['KepURL'])): ?>
                            <img src="<?php echo htmlspecialchars($row['KepURL']); ?>" class="card-img-top" alt="Esemény képe" style="max-height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['Helyszin']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['Idopont']); ?></p>
                            <?php if (isset($_SESSION['username'])): ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
                                    <?php if ($isRegistered): ?>
                                        <button type="submit" name="unregister" class="btn btn-danger">Lemondás</button>
                                    <?php else: ?>
                                        <button type="submit" name="register" class="btn btn-primary">Jelentkezés</button>
                                    <?php endif; ?>
                                </form>
                            <?php else: ?>
                                <p><a href="bejelentkezes.php">Jelentkezz be a részvételhez!</a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php if (isset($_GET['msg'])): ?>
                <script>alert('<?php echo htmlspecialchars($_GET['msg']); ?>');</script>
            <?php endif; ?>
        </div>
    </div>

    <nav class="navbar navbar-dark bg-primary fixed-bottom custom-navbar">
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
</body>
</html>