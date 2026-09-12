(async () => {
    if (!(await checkAdmin())) return;
    const data = await api('../controller/UserController.php?action=list');
    if (!data.ok) return showMessage('message', data.message);
    const rows = document.getElementById('userRows');
    rows.innerHTML = data.data.map(u => `<tr><td>${u.id}</td><td>${escapeHtml(u.name)}</td><td>${escapeHtml(u.email)}</td><td>${u.created_at}</td><td><button class="danger small" data-id="${u.id}">Delete</button></td></tr>`).join('');
    rows.querySelectorAll('button').forEach(btn => btn.addEventListener('click', async () => {
        if (!confirm('Delete this customer and their related data?')) return;
        const fd = new FormData(); fd.append('id', btn.dataset.id);
        const result = await api('../controller/UserController.php?action=delete', { method: 'POST', body: fd });
        showMessage('message', result.message, result.ok);
        if (result.ok) btn.closest('tr').remove();
    }));
})();
function escapeHtml(value) { return String(value ?? '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c])); }
