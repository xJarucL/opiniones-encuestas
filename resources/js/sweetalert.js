document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-swal-form]').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const formId = this.dataset.targetForm;
            const form = formId ? document.getElementById(formId) : this.closest('form');

            if (!form) {
                console.warn('Formulario no encontrado para el botón:', this);
                return;
            }

            const title = this.dataset.swalTitle || '¿Estás seguro?';
            const text = this.dataset.swalText || '';
            const icon = this.dataset.swalIcon || 'warning';
            const confirm = this.dataset.swalConfirm || 'Sí';
            const cancel = this.dataset.swalCancel || 'Cancelar';
            const confirmColor = this.dataset.swalColor || '#9130d6ff';

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirm,
                cancelButtonText: cancel
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});

// sweetalert.js
document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-swal-form]');
    if (!btn) return;
    e.preventDefault();

    const formId = btn.getAttribute('data-target-form');
    const form = document.getElementById(formId);
    if (!form) return;

    const title = btn.getAttribute('data-swal-title') || '¿Estás seguro?';
    const text = btn.getAttribute('data-swal-text') || 'Confirma esta acción.';
    const icon = btn.getAttribute('data-swal-icon') || 'warning';
    const confirmButton = btn.getAttribute('data-swal-confirm') || 'Sí';
    const cancelButton = btn.getAttribute('data-swal-cancel') || 'Cancelar';

    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: '#7C3AED',
        cancelButtonColor: '#6B7280',
        confirmButtonText: confirmButton,
        cancelButtonText: cancelButton,
        background: '#fff',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

