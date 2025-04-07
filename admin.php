<?php
session_start();


if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('Location: index.php');
    exit;
}


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


if (isset($_POST['deleteEvent'])) {
    $eventId = $_POST['eventId'];
    $deleteQuery = "DELETE FROM esemenyek WHERE id = :eventId";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->execute(['eventId' => $eventId]);
    header("Location: admin.php?msg=Esemény+törölve!");
    exit;
}


if (isset($_POST['updateEvent'])) {
    $eventId = $_POST['eventId'];
    $location = $_POST['eventLocation'];
    $date = $_POST['eventDate'];
    $imageUrl = !empty($_POST['eventImage']) ? $_POST['eventImage'] : null;

    $updateQuery = "UPDATE esemenyek SET Helyszin = :location, Idopont = :date, KepURL = :imageUrl WHERE id = :eventId";
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute(['location' => $location, 'date' => $date, 'imageUrl' => $imageUrl, 'eventId' => $eventId]);
    header("Location: admin.php?msg=Esemény+frissítve!");
    exit;
}


if (isset($_POST['uploadImage'])) {
    $imageUrl = $_POST['imageUrl'];
    $date = date('Y-m-d');
    $uploaderId = isset($_SESSION['id']) ? $_SESSION['id'] : 1;

    $query = "INSERT INTO kepek (feltolto_id, Datum, KepURL, approved) VALUES (:uploaderId, :date, :imageUrl, 0)";
    $stmt = $conn->prepare($query);
    $stmt->execute(['uploaderId' => $uploaderId, 'date' => $date, 'imageUrl' => $imageUrl]);
    header("Location: admin.php?msg=Kép+feltöltve!");
    exit;
}


if (isset($_POST['deleteImage'])) {
    $imageId = $_POST['imageId'];
    $deleteQuery = "DELETE FROM kepek WHERE id = :imageId";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->execute(['imageId' => $imageId]);
    header("Location: admin.php?msg=Kép+törölve!");
    exit;
}


if (isset($_POST['updateImage'])) {
    $imageId = $_POST['imageId'];
    $imageUrl = $_POST['imageUrl'];

    $updateQuery = "UPDATE kepek SET KepURL = :imageUrl WHERE id = :imageId";
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute(['imageUrl' => $imageUrl, 'imageId' => $imageId]);
    header("Location: admin.php?msg=Kép+frissítve!");
    exit;
}


if (isset($_POST['approveImage'])) {
    $imageId = $_POST['imageId'];
    $approveQuery = "UPDATE kepek SET approved = 1 WHERE id = :imageId";
    $stmt = $conn->prepare($approveQuery);
    $stmt->execute(['imageId' => $imageId]);
    header("Location: admin.php?msg=Kép+jóváhagyva!");
    exit;
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="admin.css">
    <style>
        .event-actions, .image-actions { display: flex; gap: 10px; }
        .edit-form { margin-top: 20px; padding: 15px; border: 1px solid #ccc; }
        .user-list, .gallery-list { margin-top: 20px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Főoldal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
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
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        <h1>Adminisztrációs felület</h1>

        <section>
            <h2>Események kezelése</h2>
            <button id="fetchEventsButton" class="btn btn-primary">Események listázása</button>
            <ul id="eventList" class="list-unstyled mt-3">
                <?php
                $query = "SELECT * FROM esemenyek";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $kep = $row['KepURL'] ? "<img src='{$row['KepURL']}' style='max-width: 100px;'>" : "Nincs kép";
                    echo "<li class='mb-2'>";
                    echo "{$row['Helyszin']} - {$row['Idopont']} - $kep";
                    echo "<div class='event-actions'>";
                    echo "<form method='POST' action='' onsubmit='return confirm(\"Biztosan törlöd?\");'>";
                    echo "<input type='hidden' name='eventId' value='{$row['id']}'>";
                    echo "<button type='submit' name='deleteEvent' class='btn btn-danger btn-sm'>Törlés</button>";
                    echo "</form>";
                    echo "<form method='GET' action=''>";
                    echo "<input type='hidden' name='editEventId' value='{$row['id']}'>";
                    echo "<button type='submit' class='btn btn-warning btn-sm'>Szerkesztés</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</li>";
                }
                ?>
            </ul>

            <h3>Új esemény hozzáadása</h3>
            <form method="POST" action="" class="mb-4">
                <div class="mb-3">
                    <input type="text" id="eventLocation" name="eventLocation" class="form-control" placeholder="Helyszín" required>
                </div>
                <div class="mb-3">
                    <input type="date" id="eventDate" name="eventDate" class="form-control" required>
                </div>
                <div class="mb-3">
                    <input type="url" id="eventImage" name="eventImage" class="form-control" placeholder="Kép URL (pl. https://example.com/kep.jpg)">
                </div>
                <button type="submit" name="addEvent" class="btn btn-success">Hozzáadás</button>
            </form>

            <?php
            if (isset($_POST['addEvent'])) {
                $location = $_POST['eventLocation'];
                $date = $_POST['eventDate'];
                $imageUrl = !empty($_POST['eventImage']) ? $_POST['eventImage'] : null;

                $query = "INSERT INTO esemenyek (Helyszin, Idopont, KepURL) VALUES (:location, :date, :imageUrl)";
                $stmt = $conn->prepare($query);
                $stmt->execute(['location' => $location, 'date' => $date, 'imageUrl' => $imageUrl]);

                echo "<div class='alert alert-success'>Esemény hozzáadva: $location - $date" . ($imageUrl ? " (Kép: $imageUrl)" : "") . "</div>";
            }
            ?>

            <?php if (isset($_GET['editEventId'])): ?>
                <?php
                $editEventId = $_GET['editEventId'];
                $editQuery = "SELECT * FROM esemenyek WHERE id = :eventId";
                $editStmt = $conn->prepare($editQuery);
                $editStmt->execute(['eventId' => $editEventId]);
                $event = $editStmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <div class="edit-form">
                    <h3>Esemény szerkesztése</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="eventId" value="<?php echo $event['id']; ?>">
                        <div class="mb-3">
                            <input type="text" name="eventLocation" class="form-control" value="<?php echo htmlspecialchars($event['Helyszin']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <input type="date" name="eventDate" class="form-control" value="<?php echo $event['Idopont']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <input type="url" name="eventImage" class="form-control" value="<?php echo htmlspecialchars($event['KepURL'] ?? ''); ?>" placeholder="Kép URL">
                        </div>
                        <button type="submit" name="updateEvent" class="btn btn-success">Mentés</button>
                    </form>
                </div>
            <?php endif; ?>

            <h2>Felhasználók kezelése</h2>
            <form method="GET" action="">
                <button type="submit" name="listUsers" class="btn btn-primary">Felhasználók listázása</button>
            </form>
            <?php if (isset($_GET['listUsers'])): ?>
                <div class="user-list">
                    <h3>Felhasználók</h3>
                    <ul class="list-unstyled">
                        <?php
                        $userQuery = "SELECT Username, Email, Role FROM users";
                        $userStmt = $conn->prepare($userQuery);
                        $userStmt->execute();
                        while ($user = $userStmt->fetch(PDO::FETCH_ASSOC)) {
                            $roleText = $user['Role'] == 1 ? "Admin" : "Felhasználó";
                            echo "<li>{$user['Username']} - {$user['Email']} - Szerepkör: $roleText</li>";
                        }
                        ?>
                    </ul>
                </div>
            <?php endif; ?>

            <h2>Galéria kezelése</h2>
            <h3>Kép feltöltése</h3>
            <form method="POST" action="" class="mb-4">
                <div class="mb-3">
                    <input type="url" id="imageUrl" name="imageUrl" class="form-control" placeholder="Kép URL (pl. https://example.com/kep.jpg)" required>
                </div>
                <button type="submit" name="uploadImage" class="btn btn-primary">Feltöltés</button>
            </form>

            <form method="GET" action="">
                <button type="submit" name="listImages" class="btn btn-primary mb-3">Képek listázása</button>
            </form>
            <?php if (isset($_GET['listImages'])): ?>
                <div class="gallery-list">
                    <h3>Galéria képek</h3>
                    <ul class="list-unstyled">
                        <?php
                        $imageQuery = "SELECT k.*, u.Username FROM kepek k LEFT JOIN users u ON k.feltolto_id = u.id";
                        $imageStmt = $conn->prepare($imageQuery);
                        $imageStmt->execute();
                        while ($image = $imageStmt->fetch(PDO::FETCH_ASSOC)) {
                            $kep = $image['KepURL'] ? "<img src='{$image['KepURL']}' style='max-width: 100px;'>" : "Nincs kép";
                            $status = $image['approved'] ? "Jóváhagyva" : "Jóváhagyásra vár";
                            echo "<li class='mb-2'>";
                            echo "Feltöltő: {$image['Username']} - Dátum: {$image['Datum']} - $kep - Állapot: $status";
                            echo "<div class='image-actions'>";
                            if (!$image['approved']) {
                                echo "<form method='POST' action=''>";
                                echo "<input type='hidden' name='imageId' value='{$image['id']}'>";
                                echo "<button type='submit' name='approveImage' class='btn btn-success btn-sm'>Jóváhagyás</button>";
                                echo "</form>";
                            }
                            echo "<form method='POST' action='' onsubmit='return confirm(\"Biztosan törlöd?\");'>";
                            echo "<input type='hidden' name='imageId' value='{$image['id']}'>";
                            echo "<button type='submit' name='deleteImage' class='btn btn-danger btn-sm'>Törlés</button>";
                            echo "</form>";
                            echo "<form method='GET' action=''>";
                            echo "<input type='hidden' name='editImageId' value='{$image['id']}'>";
                            echo "<button type='submit' class='btn btn-warning btn-sm'>Szerkesztés</button>";
                            echo "</form>";
                            echo "</div>";
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['editImageId'])): ?>
                <?php
                $editImageId = $_GET['editImageId'];
                $editQuery = "SELECT * FROM kepek WHERE id = :imageId";
                $editStmt = $conn->prepare($editQuery);
                $editStmt->execute(['imageId' => $editImageId]);
                $image = $editStmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <div class="edit-form">
                    <h3>Kép szerkesztése</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="imageId" value="<?php echo $image['id']; ?>">
                        <div class="mb-3">
                            <input type="url" name="imageUrl" class="form-control" value="<?php echo htmlspecialchars($image['KepURL'] ?? ''); ?>" placeholder="Kép URL" required>
                        </div>
                        <button type="submit" name="updateImage" class="btn btn-success">Mentés</button>
                    </form>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['msg'])): ?>
                <script>alert('<?php echo htmlspecialchars($_GET['msg']); ?>');</script>
            <?php endif; ?>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js" defer></script>
</body>
</html>