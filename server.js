require("dotenv").config();
const express = require("express");
const mysql = require("mysql");
const cors = require("cors");
const app = express();
const cookieParser = require("cookie-parser");
app.use(cors());
app.use(express.json());
app.use(cookieParser());

//MySQL adatbázis kapcsolat
const db = mysql.createConnection({
    host: "localhost",
    user: "root",
    port: "3306",
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

// Sütikbeállítás útvonal
app.get("/set-cookie", (req, res) => {
    res.cookie("test_cookie", "value123", { maxAge: 3600000, httpOnly: true });
    res.send("Süti beállítva!");
});

// Süti lekérdezés
app.get("/get-cookie", (req, res) => {
    res.send(`Süti értéke: ${req.cookies.test_cookie}`);
});

app.listen(3000, () => console.log("Szerver fut a 3000-es porton"));


const PORT = 2222
;
app.listen(PORT, () => console.log(`Szerver fut a ${PORT} porton...`));