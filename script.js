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

//bejelentkezés
async function login() {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const message = document.getElementById("message");

    const response = await fetch("backend/login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username, password }),
    });

    const result = await response.json();
    message.style.color = result.success ? "green" : "red";
    message.textContent = result.message;

    if (result.success) {
        sessionStorage.setItem("loggedInUser", username);
        sessionStorage.setItem("justLoggedIn", "true");

        setTimeout(() => {
            window.location.href = "index.html";
        }, 2000);
    }
}


//regisztáció
async function register() {
    const email = document.getElementById("email").value;
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const message = document.getElementById("message");

    if (!email || !username || !password) {
        message.textContent = "Minden mezőt ki kell tölteni!";
        return;
    }

    const response = await fetch("backend/register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, username, password }),
    });

    const result = await response.json();
    message.style.color = result.success ? "green" : "red";
    message.textContent = result.message;

    if (result.success) {
        setTimeout(() => {
            window.location.href = "bejelentkezes.html";
        }, 2000);
    }
}

//loginbox
document.addEventListener("DOMContentLoaded", () => {
    const justLoggedIn = sessionStorage.getItem("justLoggedIn");
    const loggedInUser = sessionStorage.getItem("loggedInUser");

    if (justLoggedIn === "true" && loggedInUser) {
        alert(`Üdvözlünk, ${loggedInUser}!`);
        sessionStorage.removeItem("justLoggedIn");
    }
});

// Ellenőrizzük, hogy van-e bejelentkezett felhasználó
const userLoggedIn = localStorage.getItem('userLoggedIn') === 'true';

const userSection = document.getElementById('user-section');
const loginLink = document.getElementById('login-link');

if (userLoggedIn) {
    loginLink.style.display = 'none';

    const profileDropdown = document.createElement('li');
    profileDropdown.className = 'nav-item dropdown';
    profileDropdown.innerHTML = `
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle"></i> Profil
        </a>
        <ul class="dropdown-menu" aria-labelledby="profileDropdown">
            <li><a class="dropdown-item" href="profil.html">Profilom</a></li>
            <li><a class="dropdown-item" href="#">Beállítások</a></li>
            <li><a class="dropdown-item" href="#" onclick="logout()">Kijelentkezés</a></li>
        </ul>
    `;
    userSection.appendChild(profileDropdown);
}

function logout() {
    alert('Kijelentkeztél!');
    localStorage.removeItem('userLoggedIn');
    window.location.href = 'index.html';
}

// Példa: Bejelentkezés után állítsd be a localStorage értékét
function login() {
    localStorage.setItem('userLoggedIn', 'true');
    window.location.href = 'index.html';
}