const envelopeWrapper = document.getElementById('envelopeWrapper');
const nomineesText = document.getElementById('nomineesText');
let hasOpened = false;

setTimeout(() => {
    if (!hasOpened) {
        openEnvelope();
    }
}, 2000);

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

        setTimeout(() => {
            const pathArray = window.location.pathname.split('/');
            const encuestaId = pathArray[pathArray.length - 1];
            window.location.href = `/presentaciontwo/${encuestaId}/0`;
        }, 3500);
    }, 2000);
}