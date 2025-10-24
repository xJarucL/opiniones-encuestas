document.addEventListener('DOMContentLoaded', function() {
    animateBars();

    // Botón repetir - Redirigir a presentación
    const repeatButton = document.querySelector('.repeat-button');
    if (repeatButton) {
        repeatButton.addEventListener('click', function() {
            // Obtener el preguntaId del URL actual
            const pathArray = window.location.pathname.split('/');
            const preguntaId = pathArray[pathArray.length - 1];
            
            // Redirigir a la pantalla de presentación
            window.location.href = `/presentacion/${preguntaId}`;
        });
    }
});

function animateBars() {
    const progressBars = document.querySelectorAll('.progress-bar');
    
    progressBars.forEach((bar, index) => {
        const percentage = bar.getAttribute('data-percentage');
        
        setTimeout(() => {
            bar.style.setProperty('--percentage', percentage + '%');
            bar.classList.add('animate');
        }, (index + 1) * 200 + 500);
    });
}

function resetAnimation() {
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        bar.classList.remove('animate');
        bar.style.width = '0';
    });
}