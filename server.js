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


const PORT = 3301;
app.listen(PORT, () => console.log(`Szerver fut a ${PORT} porton...`));