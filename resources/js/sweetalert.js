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
