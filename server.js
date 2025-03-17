require("dotenv").config();
const express = require("express");
const mysql = require("mysql");
const cors = require("cors");
const app = express();
app.use(cors());
app.use(express.json());

//MySQL adatbázis kapcsolat
const db = mysql.createConnection({
    host: "localhost",
    user: "root",
    port: "3307",
    password: "",  
    database: "yamahasok"
});

db.connect(err => {
    if (err) {
        console.error("MySQL kapcsolódási hiba: ", err);
        return;
    }
    console.log("Sikeres kapcsolat az adatbázishoz!");
});


app.post("/login", (req, res) => {
    const { username, password } = req.body;
    db.query("SELECT * FROM users WHERE username = ? AND password = ?", 
        [username, password], 
        (err, results) => {
            if (err) return res.status(500).json({ error: err.message });
            if (results.length > 0) {
                res.json({ message: "Sikeres bejelentkezés!", user: results[0] });
            } else {
                res.status(401).json({ error: "Hibás felhasználónév vagy jelszó!" });
            }
        }
    );
});


app.post("/register", (req, res) => {
    const { email, username, password } = req.body;
    db.query("INSERT INTO users (email, username, password) VALUES (?, ?, ?)", 
        [email, username, password], 
        (err, result) => {
            if (err) return res.status(500).json({ error: err.message });
            res.json({ message: "Sikeres regisztráció!", id: result.insertId });
        }
    );
});

//admin
const multer = require("multer");
const path = require("path");


// Felhasználók listázása
app.get("/users", (req, res) => {
    db.query("SELECT id, username, email, role FROM users", (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
});


// Felhasználó törlése
app.delete("/users/:id", (req, res) => {
    const userId = req.params.id;
    db.query("DELETE FROM users WHERE id = ?", [userId], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ message: "Felhasználó törölve!" });
    });
});


app.get("/esemenyek", (req, res) => {
    db.query("SELECT * FROM esemenyek", (err, results) => {
        if (err) {
            console.error("Hiba az események lekérdezésekor:", err);
            return res.status(500).json({ error: err.message });
        }
        res.json(results);
    });
});

app.post("/esemenyek", (req, res) => {
    const { Helyszin, Idopont } = req.body;
    db.query("INSERT INTO esemenyek (Helyszin, Idopont) VALUES (?, ?)", [Helyszin, Idopont], (err, result) => {
        if (err) {
            console.error("SQL Hiba:", err);
            return res.status(500).json({ error: err.message });
        }
        res.json({ message: "Esemény sikeresen hozzáadva!" });
    });
});


app.post("/esemenyek", (req, res) => {
    const { Helyszin, date } = req.body;
    db.query("INSERT INTO esemenyek (Helyszin, Idopont) VALUES (?, ?)", [Helyszin, Idopont], (err, result) => {
        if (err) {
            console.error("SQL Hiba:", err);
            return res.status(500).json({ error: err.message });
        }
        res.json({ message: "Esemény sikeresen hozzáadva!" });
    });
});


app.delete("/esemenyek/:id", (req, res) => {
    const eventId = req.params.id;
    db.query("DELETE FROM esemenyek WHERE id = ?", [eventId], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ message: "Esemény törölve!" });
    });
});

//Képfeltöltés beállítása
const storage = multer.diskStorage({
    destination: "./img/",
    filename: (req, file, cb) => {
        cb(null, Date.now() + path.extname(file.originalname));
    }
});
const upload = multer({ storage });

app.post("/upload", upload.single("image"), (req, res) => {
    if (!req.file) return res.status(400).json({ error: "Nincs feltöltött fájl!" });
    res.json({ message: "Kép sikeresen feltöltve!", filePath: `/img/${req.file.filename}` });
});

const PORT = 3301;
app.listen(PORT, () => console.log(`Szerver fut a ${PORT} porton...`));