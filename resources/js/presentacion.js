const envelopeWrapper = document.getElementById('envelopeWrapper');
const nomineesText = document.getElementById('nomineesText');
let hasOpened = false;

// Abrir automáticamente o al hacer clic
setTimeout(() => {
    if (!hasOpened) {
        openEnvelope();
    }
}, 2500);

envelopeWrapper.addEventListener('click', () => {
    if (!hasOpened) {
        openEnvelope();
    }
});

function openEnvelope() {
    hasOpened = true;
    envelopeWrapper.classList.add('opening');
    
    setTimeout(() => {
        envelopeWrapper.classList.add('exit');
        nomineesText.classList.add('show');
    }, 2000);
}