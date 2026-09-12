(async () => {
    if (!(await checkAdmin())) return;
    const data = await api('../controller/ProductController.php?action=list');
    if (!data.ok) return showMessage('message', data.message);
    const rows = document.getElementById('productRows');
    rows.innerHTML = data.data.map(p => `
        <tr>
            <td>${p.proID}</td>
            <td>${p.proImg ? `<img class="thumb" src="../${escapeHtml(p.proImg)}">` : ''}</td>
            <td>${escapeHtml(p.proName)}</td>
            <td>${escapeHtml(p.proGender || '')}</td>
            <td>${escapeHtml(p.proCategory)}</td>
            <td>${Number(p.proPrice).toFixed(2)}</td>
            <td>${p.proQuantity}</td>
            <td><a class="button small" href="edit-product.html?id=${p.proID}">Edit</a> <button class="danger small" data-id="${p.proID}">Delete</button></td>
        </tr>`).join('');
    rows.querySelectorAll('button').forEach(btn => btn.addEventListener('click', async () => {
        if (!confirm('Delete this product?')) return;
        const fd = new FormData(); fd.append('id', btn.dataset.id);
        const result = await api('../controller/ProductController.php?action=delete', { method: 'POST', body: fd });
        showMessage('message', result.message, result.ok);
        if (result.ok) location.reload();
    }));
})();
function escapeHtml(value) { return String(value ?? '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c])); }
