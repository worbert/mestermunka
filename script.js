document.addEventListener("DOMContentLoaded", function () {
    const sections = document.querySelectorAll("div[id]");
    const navLinks = document.querySelectorAll(".navbar-nav .nav-link");

    window.addEventListener("scroll", function () {
        let current = "";
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (window.scrollY >= sectionTop - 50) {
                current = section.getAttribute("id");
            }
        });

        navLinks.forEach(link => {
            link.classList.remove("active");
            if (link.getAttribute("href").includes(current)) {
                link.classList.add("active");
            }
        });
    });
});

//Bejelentkezés
function login() {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const rememberMe = document.getElementById("rememberMe").checked;
    
    fetch("http://localhost:3301/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username, password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            alert("Sikeres bejelentkezés!");
            if (rememberMe) {
                localStorage.setItem("loggedInUser", JSON.stringify(data.user));
            } else {
                sessionStorage.setItem("loggedInUser", JSON.stringify(data.user));
            }
            window.location.href = data.user.role === "admin" ? "esemenyek.html" : "index.html";
        }
    })
    .catch(error => console.error("Hiba:", error));
}


//Regisztráció
function register() {
    const email = document.getElementById("email").value.trim();
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirmPassword = document.getElementById("confirmPassword").value.trim();
    const message = document.getElementById("message");

    // Ellenőrizzük, hogy minden mező ki van-e töltve
    if (!email || !username || !password || !confirmPassword) {
        message.style.color = "red";
        message.textContent = "Minden mezőt ki kell tölteni!";
        return;
    }

    // Ellenőrizzük az e-mail formátumát
    if (!isValidEmail(email)) {
        message.style.color = "red";
        message.textContent = "Az e-mail cím formátuma nem megfelelő!";
        return;
    }

    // Ellenőrizzük, hogy a jelszó megfelel-e a biztonsági követelményeknek
    if (!isValidPassword(password)) {
        message.style.color = "red";
        message.textContent = "A jelszónak legalább 8 karakter hosszúnak kell lennie, és tartalmaznia kell egy nagybetűt és egy számot!";
        return;
    }

    // Ellenőrizzük, hogy a két jelszó megegyezik-e
    if (password !== confirmPassword) {
        message.style.color = "red";
        message.textContent = "A két jelszó nem egyezik!";
        return;
    }

    // Küldés a szervernek
    fetch("http://localhost:3301/register", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, username, password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            message.style.color = "red";
            message.textContent = data.error;
        } else {
            message.style.color = "green";
            message.textContent = "Sikeres regisztráció!";
            setTimeout(() => window.location.href = "bejelentkezes.html", 2000);
        }
    })
    .catch(error => console.error("Hiba:", error));
}

// E-mail validálás funkció
function isValidEmail(email) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email);
}

// Jelszó validálás funkció
function isValidPassword(password) {
    const passwordPattern = /^(?=.*[A-Z])(?=.*\d).{8,}$/;
    return passwordPattern.test(password);
}

//Bejelentkezési értesítés
document.addEventListener("DOMContentLoaded", () => {
    const justLoggedIn = sessionStorage.getItem("justLoggedIn");
    const loggedInUser = sessionStorage.getItem("loggedInUser");

    if (justLoggedIn === "true" && loggedInUser) {
        alert(`Üdvözlünk, ${loggedInUser}!`);
        sessionStorage.removeItem("justLoggedIn");
    }
});

//ki-be jelentkezes gomb
document.addEventListener("DOMContentLoaded", () => {
    const loginLogoutBtn = document.getElementById("loginLogoutBtn");
    const loggedInUser = JSON.parse(localStorage.getItem("loggedInUser")) || JSON.parse(sessionStorage.getItem("loggedInUser"));

    if (!loginLogoutBtn) {
        console.error("HIBA: A loginLogoutBtn elem nem található a DOM-ban!");
        return;
    }

    if (loggedInUser) {
        console.log(`Bejelentkezett felhasználó: ${loggedInUser.username}`);
        loginLogoutBtn.textContent = "Kijelentkezés";
        loginLogoutBtn.href = "#";

        loginLogoutBtn.addEventListener("click", (e) => {
            e.preventDefault();
            console.log("Kijelentkezés gombra kattintottál!");

            sessionStorage.clear();
            localStorage.removeItem("loggedInUser");

            alert("Sikeresen kijelentkeztél!");
            window.location.href = "index.html";
        });

    } else {
        console.log("Nincs bejelentkezve felhasználó.");
        loginLogoutBtn.textContent = "Bejelentkezés";
        loginLogoutBtn.href = "bejelentkezes.html";
    }
});

document.addEventListener("DOMContentLoaded", function () {
    fetch("api.php") // Kérdezzük le a bejelentkezési állapotot
        .then(response => response.json())
        .then(data => {
            const userSection = document.getElementById("user-section");
            if (data.loggedIn) {
                // Ha be van jelentkezve, cseréljük le a bejelentkezési gombot a profilmenüre
                userSection.innerHTML = `
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            ${data.username}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profil.html">Profilom</a></li>
                            <li><a class="dropdown-item" href="logout.php">Kijelentkezés</a></li>
                        </ul>
                    </li>
                `;
            } else {
                // Ha nincs bejelentkezve, marad a bejelentkezés gomb
                userSection.innerHTML = `
                    <li class="nav-item">
                        <a class="nav-link" href="bejelentkezes.html">Bejelentkezés</a>
                    </li>
                `;
            }
        })
        .catch(error => console.error("Hiba a bejelentkezési állapot lekérdezésekor:", error));
});