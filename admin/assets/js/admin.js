/**
 * CONSTRUBÃO - Admin JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    initMobileMenu();
    initConfirmDelete();
    initImageUpload();
    initSlugGenerator();
});

/**
 * Mobile Menu Toggle
 */
function initMobileMenu() {
    const toggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('adminOverlay');

    if (!toggle || !sidebar) return;

    toggle.addEventListener('click', function() {
        sidebar.classList.toggle('open');
        if (overlay) overlay.classList.toggle('active');
    });

    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }
}

/**
 * Confirm Delete
 */
function initConfirmDelete() {
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function(e) {
            const message = this.dataset.confirm || 'Tem certeza que deseja excluir este item?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Image Upload Preview
 */
function initImageUpload() {
    document.querySelectorAll('.image-upload').forEach(upload => {
        const input = upload.querySelector('input[type="file"]');
        const preview = upload.querySelector('.image-preview');
        const placeholder = upload.querySelector('.image-upload-placeholder');

        if (!input) return;

        upload.addEventListener('click', () => input.click());

        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    } else {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'image-preview';
                        upload.appendChild(img);
                    }

                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }

                    upload.classList.add('has-image');
                };

                reader.readAsDataURL(this.files[0]);
            }
        });
    });
}

/**
 * Auto Slug Generator
 */
function initSlugGenerator() {
    const titleInput = document.getElementById('titulo') || document.getElementById('nome');
    const slugInput = document.getElementById('slug');

    if (!titleInput || !slugInput) return;

    // Só gera automaticamente se o slug estiver vazio
    let autoGenerate = !slugInput.value;

    titleInput.addEventListener('input', function() {
        if (autoGenerate) {
            slugInput.value = slugify(this.value);
        }
    });

    slugInput.addEventListener('input', function() {
        // Se o usuário editar manualmente, para de gerar automaticamente
        autoGenerate = false;
    });
}

/**
 * Slugify string
 */
function slugify(text) {
    return text
        .toString()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/--+/g, '-')
        .trim();
}

/**
 * Show Toast
 */
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type}`;
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        animation: slideUp 0.3s ease;
    `;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * AJAX Form Submit
 */
function submitForm(form, callback) {
    const formData = new FormData(form);

    fetch(form.action, {
        method: form.method,
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (callback) callback(data);
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Erro ao processar requisição', 'error');
    });
}

// Export functions
window.AdminUtils = {
    slugify,
    showToast,
    submitForm
};
