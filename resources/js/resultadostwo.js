document.addEventListener('DOMContentLoaded', () => {
    // Mostrar/Ocultar respuestas
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const parent = btn.closest('.pregunta-block');
            const hiddenRows = parent.querySelectorAll('.extra');
            hiddenRows.forEach(row => row.classList.toggle('hidden'));

            btn.textContent = btn.textContent.includes('todas')
                ? 'Ver menos respuestas'
                : 'Ver todas las respuestas';
        });
    });

    // Animación de barras
    const animateBars = () => {
        document.querySelectorAll('.progress-bar').forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    };

    animateBars();

    // Botón repetir animación
    const repeatBtn = document.querySelector('.repeat-button');
    if (repeatBtn) {
        repeatBtn.addEventListener('click', () => {
            const pathParts = window.location.pathname.split('/');
            const encuestaId = pathParts[pathParts.length - 1];
            
            window.location.href = `/presentacionone/${encuestaId}`;
        });
    }
});
