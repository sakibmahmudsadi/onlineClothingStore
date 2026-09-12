async function api(url, options = {}) {
    const response = await fetch(url, options);
    const data = await response.json().catch(() => ({ ok: false, message: 'Invalid server response.' }));
    if (data.redirect) {
        window.location.href = data.redirect;
        return data;
    }
    return data;
}

async function checkAdmin() {
    const data = await api('../controller/AdminController.php?action=check');
    if (!data.ok) window.location.href = 'login.html';
    return data.ok;
}

function showMessage(id, text, success = false) {
    const el = document.getElementById(id);
    if (!el) return;
    el.textContent = text;
    el.className = success ? 'message success' : 'message error';
}
