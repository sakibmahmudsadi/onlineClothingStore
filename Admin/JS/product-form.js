const categories = {
    Men: ['T-Shirt', 'Shirt', 'Pants', 'Jeans', 'Jacket', 'Hoodie'],
    Women: ['T-Shirt', 'Top', 'Dress', 'Skirt', 'Jeans', 'Jacket', 'Saree']
};

const form = document.getElementById('productForm');
const gender = document.getElementById('gender');
const category = document.getElementById('category');

function fillCategories(value, selected = '') {
    category.innerHTML = '<option value="">Select category</option>';
    if (!categories[value]) { category.disabled = true; return; }
    category.disabled = false;
    categories[value].forEach(item => {
        const option = document.createElement('option');
        option.value = item;
        option.textContent = item;
        option.selected = item === selected;
        category.appendChild(option);
    });
}

gender.addEventListener('change', () => fillCategories(gender.value));

function validate() {
    const price = Number(form.price.value);
    const stock = Number(form.stock.value);
    const image = form.image.files[0];
    if (form.name.value.trim().length < 2) return 'Product name must be at least 2 characters.';
    if (form.description.value.trim().length < 5) return 'Description must be at least 5 characters.';
    if (!Number.isFinite(price) || price <= 0) return 'Price must be greater than 0.';
    if (!Number.isInteger(stock) || stock < 0) return 'Stock must be a whole number 0 or greater.';
    if (!gender.value || !category.value) return 'Select gender and category.';
    if (image) {
        if (!['image/jpeg', 'image/png'].includes(image.type)) return 'Only JPEG and PNG images are allowed.';
        if (image.size > 2 * 1024 * 1024) return 'Image must be 2MB or smaller.';
    } else if (!new URLSearchParams(location.search).get('id')) {
        return 'Product image is required.';
    }
    const size = form.size_chart.value.trim();
    if (size && (size.startsWith('{') || size.startsWith('['))) {
        try { JSON.parse(size); } catch { return 'Size chart contains invalid JSON.'; }
    }
    return '';
}

(async () => {
    if (!(await checkAdmin())) return;
    const id = new URLSearchParams(location.search).get('id');
    if (!id) return;
    const data = await api(`../controller/ProductController.php?action=get&id=${id}`);
    if (!data.ok) return showMessage('message', data.message);
    const p = data.data;
    form.id.value = p.proID;
    form.name.value = p.proName;
    form.description.value = p.proDesc || '';
    form.price.value = p.proPrice;
    form.stock.value = p.proQuantity;
    form.size_chart.value = p.proSize || '';
    gender.value = p.proGender || '';
    fillCategories(gender.value, p.proCategory);
    document.getElementById('currentImage').textContent = p.proImg ? `Current image: ${p.proImg}` : '';
})();

form.addEventListener('submit', async e => {
    e.preventDefault();
    const error = validate();
    if (error) return showMessage('message', error);
    const id = new URLSearchParams(location.search).get('id');
    const action = id ? 'update' : 'create';
    const result = await api(`../controller/ProductController.php?action=${action}`, { method: 'POST', body: new FormData(form) });
    showMessage('message', result.message, result.ok);
    if (result.ok) setTimeout(() => location.href = 'products.html', 700);
});
