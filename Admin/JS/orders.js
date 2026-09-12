(async () => {
    if (!(await checkAdmin())) return;
    await loadOrders();
})();

async function loadOrders() {
    const data = await api('../controller/OrderController.php?action=list');
    if (!data.ok) return showMessage('message', data.message);
    const rows = document.getElementById('orderRows');
    rows.innerHTML = data.data.map(o => {
        const actions = o.status === 'pending' ? `<button class="confirm small" data-id="${o.id}" data-status="confirmed">Confirm</button> <button class="danger small" data-id="${o.id}" data-status="rejected">Reject</button>` : '-';
        return `<tr><td>#${o.id}</td><td>${escapeHtml(o.customer_name)}</td><td>${Number(o.total_amount).toFixed(2)}</td><td><span class="status ${o.status}">${o.status}</span></td><td>${o.created_at}</td><td>${actions}</td></tr>`;
    }).join('');
    rows.querySelectorAll('button').forEach(btn => btn.addEventListener('click', updateStatus));
}

async function updateStatus() {
    const fd = new FormData(); fd.append('id', this.dataset.id); fd.append('status', this.dataset.status);
    const result = await api('../controller/OrderController.php?action=status', { method: 'POST', body: fd });
    showMessage('message', result.message, result.ok);
    if (result.ok) await loadOrders();
}
function escapeHtml(value) { return String(value ?? '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c])); }
