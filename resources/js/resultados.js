document.addEventListener('DOMContentLoaded', function() {
    animateBars();

    // Botón repetir
    const repeatButton = document.querySelector('.repeat-button');
    if (repeatButton) {
        repeatButton.addEventListener('click', function() {
            resetAnimation();
            setTimeout(() => animateBars(), 100);
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