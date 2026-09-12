(async () => {
    const ok = await checkAdmin();
    if (!ok) return;
    const data = await api('../controller/AdminController.php?action=dashboard');
    if (!data.ok) return;
    document.getElementById('products').textContent = data.data.products;
    document.getElementById('customers').textContent = data.data.customers;
    document.getElementById('orders').textContent = data.data.orders;
    document.getElementById('pending').textContent = data.data.pending_orders;
})();
