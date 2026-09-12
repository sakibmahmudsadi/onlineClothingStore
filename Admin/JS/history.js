(async () => {
    if (!(await checkAdmin())) return;
    const data = await api('../controller/OrderController.php?action=history');
    if (!data.ok) return;
    document.getElementById('historyRows').innerHTML = data.data.map(r => `<tr><td>#${r.order_id}</td><td>${escapeHtml(r.customer_name)}</td><td>${escapeHtml(r.email)}</td><td>${escapeHtml(r.product_name)}</td><td>${r.quantity}</td><td>${Number(r.unit_price).toFixed(2)}</td><td>${Number(r.item_total).toFixed(2)}</td><td>${Number(r.total_amount).toFixed(2)}</td><td><span class="status ${r.status}">${r.status}</span></td><td>${r.created_at}</td></tr>`).join('');
})();
function escapeHtml(value) { return String(value ?? '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c])); }
