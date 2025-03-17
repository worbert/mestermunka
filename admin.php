<?php

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
        <a href="index.html">Főoldal</a>
    </nav>

    <h1>Adminisztrációs felület</h1>

    <section>
        <h2>Felhasználók kezelése</h2>
        <!-- Felhasználók listázása gomb -->
        <button id="fetchUsersButton">Felhasználók listázása</button>
        <ul id="userList"></ul>
    </section>
    
    <section>
        <h2>Események kezelése</h2>
        <!-- Események listázása gomb -->
        <button id="fetchEventsButton">Események listázása</button>
        <ul id="eventList"></ul>
    
        <h3>Új esemény hozzáadása</h3>
        <input type="text" id="eventLocation" placeholder="Helyszín">
        <input type="date" id="eventDate">
        <button onclick="addEvent()">Hozzáadás</button>
    </section>
    
    <section>
        <h2>Galéria képfeltöltés</h2>
        <input type="file" id="imageUpload">
        <button onclick="uploadImage()">Feltöltés</button>
        <p id="uploadMessage"></p>
    </section>    

    <script src="script.js" defer></script>
</body>
</html>
