document.addEventListener('DOMContentLoaded', () => {
    const nextButton = document.querySelector('.next-button');

    nextButton.addEventListener('click', () => {
        if (hayMasPreguntas) {
            const siguiente = preguntaIndex + 1;
            window.location.href = `/presentaciontwo/${encuestaId}/${siguiente}`;
        } else {
            window.location.href = `/resultadostwo/${encuestaId}`;
        }
    });
});