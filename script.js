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

const userId = sessionStorage.getItem("loggedInUserId"); // Bejelentkezett felhasználó
const recipientId = 2; // Ideiglenesen egy másik felhasználó ID-ja (választható a felületen)

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
});
