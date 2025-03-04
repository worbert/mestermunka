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

// Üzenet küldése
async function sendMessage() {
    const messageInput = document.getElementById("message");
    const message = messageInput.value;

    if (!message.trim()) return;

    await fetch("backend/send_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            felado_id: userId,
            cimzett_id: recipientId,
            uzenet: message,
        }),
    });

    messageInput.value = "";
    loadMessages();
}

// Üzenetek lekérése
async function loadMessages() {
    const response = await fetch(`backend/get_messages.php?felado_id=${userId}&cimzett_id=${recipientId}`);
    const messages = await response.json();

    const chatBox = document.getElementById("chat-box");
    chatBox.innerHTML = messages
        .map(
            (msg) =>
                `<div class="${msg.felado_id == userId ? 'own' : 'other'}">
                    <strong>${msg.felado_nev}:</strong> ${msg.uzenet} <span>(${msg.kuldes_ido})</span>
                </div>`
        )
        .join("");
}

// Chat frissítése 3 másodpercenként
setInterval(loadMessages, 3000);

document.addEventListener("DOMContentLoaded", async () => {
    const notificationCount = document.getElementById("notification-count");

    async function checkNotifications() {
        const userId = sessionStorage.getItem("loggedInUserId");
        if (!userId) return;

        const response = await fetch(`backend/check_notifications.php?user_id=${userId}`);
        const data = await response.json();

        if (data.unreadCount > 0) {
            notificationCount.textContent = data.unreadCount;
            notificationCount.classList.remove("d-none");
        } else {
            notificationCount.classList.add("d-none");
        }
    }

    // Induláskor ellenőrizzük az értesítéseket
    checkNotifications();

    // Frissítés 10 másodpercenként
    setInterval(checkNotifications, 10000);
});

document.addEventListener("DOMContentLoaded", () => {
    const chatButton = document.getElementById("chat-button");
    const chatContainer = document.getElementById("chat-container");
    const chatBox = document.getElementById("chat-box");
    const closeChat = document.getElementById("close-chat");
    const friendsList = document.getElementById("friends");

    // Dummy barátlista (később PHP adatbázisból töltjük be)
    const friends = [
        { id: 1, name: "Józsi", avatar: "avatar1.jpg" },
        { id: 2, name: "Béla", avatar: "avatar2.jpg" }
    ];

    // Chat gomb kattintás
    chatButton.addEventListener("click", () => {
        chatContainer.style.display = chatContainer.style.display === "flex" ? "none" : "flex";
    });

    // Barátlista betöltése
    function loadFriends() {
        friendsList.innerHTML = "";
        friends.forEach(friend => {
            const li = document.createElement("li");
            li.textContent = friend.name;
            li.addEventListener("click", () => openChat(friend));
            friendsList.appendChild(li);
        });
    }

    // Chat megnyitása
    function openChat(friend) {
        document.getElementById("chat-avatar").src = friend.avatar;
        document.getElementById("chat-name").textContent = friend.name;
        chatBox.classList.remove("hidden");
    }

    // Chat bezárása
    closeChat.addEventListener("click", () => {
        chatBox.classList.add("hidden");
    });

    loadFriends();

    document.addEventListener("DOMContentLoaded", function () {
        // Példa: A felhasználói adatok betöltése localStorage-ból
        const user = JSON.parse(localStorage.getItem("felhasznalok"));
    
        if (user) {
            document.getElementById("username").textContent = user.name;
            document.getElementById("Email").textContent = user.email;
            document.getElementById("joinDate").textContent = user.joinDate;
        } else {
            // Ha nincs bejelentkezett felhasználó, átirányítás a login oldalra
            window.location.href = "login.html";
        }
    });
    
    function logout() {
        // Töröljük a felhasználói adatokat
        localStorage.removeItem("user");
    
        // Átirányítás a bejelentkezési oldalra
        window.location.href = "login.html";
    }
    
    function logout() {
        fetch("logout.php")
            .then(() => {
                window.location.href = "login.html";
            });
    }
    
});
