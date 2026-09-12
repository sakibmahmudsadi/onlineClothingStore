document.getElementById('loginForm').addEventListener('submit', async e => {
    e.preventDefault();
    const form = new FormData(e.currentTarget);
    const response = await fetch('../controller/LoginController.php', { method: 'POST', body: form });
    const data = await response.json();
    const msg = document.getElementById('message');
    msg.textContent = data.message;
    msg.className = data.ok ? 'message success' : 'message error';
    if (data.ok) window.location.href = data.redirect;
});
