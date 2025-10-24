// Redirigir a resultados después de ver el podio
setTimeout(() => {
    const pathArray = window.location.pathname.split('/');
    const preguntaId = pathArray[pathArray.length - 1];
    window.location.href = `/resultados/${preguntaId}`;
}, 6000); // 6 segundos después de mostrar el podio