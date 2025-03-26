<?php
// Start session or include necessary files (e.g., database connection)
session_start();
// Example: include 'config.php'; // For database connection
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

    <nav class="navbar">
        <a href="index.php">Főoldal</a>
    </nav>

    <h1>Adminisztrációs felület</h1>

    <section>
        <h2>Felhasználók kezelése</h2>
        <!-- Felhasználók listázása gomb -->
        <button id="fetchUsersButton">Felhasználók listázása</button>
        <ul id="userList">
            <?php
            // Placeholder for fetching users from a database
            /*
            $query = "SELECT * FROM users";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>{$row['username']}</li>";
            }
            */
            ?>
        </ul>
    </section>
    
    <section>
        <h2>Események kezelése</h2>
        <!-- Események listázása gomb -->
        <button id="fetchEventsButton">Események listázása</button>
        <ul id="eventList">
            <?php
            // Placeholder for fetching events from a database
            /*
            $query = "SELECT * FROM events";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>{$row['location']} - {$row['date']}</li>";
            }
            */
            ?>
        </ul>
    
        <h3>Új esemény hozzáadása</h3>
        <form method="POST" action="">
            <input type="file" id="imageUpload" name="imageUpload" accept="image/*" required>
            <input type="text" id="eventLocation" name="eventLocation" placeholder="Helyszín" required>
            <input type="date" id="eventDate" name="eventDate" required>
            <button type="submit" name="addEvent">Hozzáadás</button>
        </form>

        <?php
        // Handle event addition
        if (isset($_POST['addEvent'])) {
            $location = $_POST['eventLocation'];
            $date = $_POST['eventDate'];
            // Placeholder for inserting into database
            /*
            $query = "INSERT INTO events (location, date) VALUES ('$location', '$date')";
            mysqli_query($conn, $query);
            echo "<p>Esemény hozzáadva!</p>";
            */
            echo "<p>Esemény: $location - $date (Ez egy teszt üzenet, adatbázis nélkül)</p>";
        }
        ?>
    </section>
    
    <section>
        <h2>Galéria képfeltöltés</h2>
        <form method="POST" action="" enctype="multipart/form-data">
        <label for="propertyImage">Kép URL:</label>
        <input type="text" id="propertyImage" name="propertyImage" required>
            <button type="submit" name="uploadImage">Feltöltés</button>
        </form>
        <p id="uploadMessage">
            <?php
            // Handle image upload
            if (isset($_POST['uploadImage'])) {
                if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] == 0) {
                    $fileName = $_FILES['imageUpload']['name'];
                    $fileTmp = $_FILES['imageUpload']['tmp_name'];
                    $uploadDir = 'uploads/'; // Ensure this directory exists and is writable
                    move_uploaded_file($fileTmp, $uploadDir . $fileName);
                    echo "Kép feltöltve: $fileName";
                } else {
                    echo "Hiba a képfeltöltés során!";
                }
            }
            ?>
        </p>
    </section>    

    <script src="script.js" defer></script>
</body>
</html>