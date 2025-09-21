<style>
    .success {
        background-color: green;
        color: white;
        padding: 10px;
        border-radius: 5px;
    }

    .error {
        background-color: red;
        color: white;
        padding: 10px;
        border-radius: 5px;
    }
</style>

@if (session('success'))
    <div id="mensaje" class="success mb-4 text-sm">
        {{ session('success') }}
    </div>
@elseif (session('error'))
    <div id="mensaje" class="error mb-4 text-sm">
        {{ session('error') }}
    </div>
@else
    <div id="mensaje" class="hidden mb-4 text-sm"></div>
@endif

<!-- Esto es sólo para las funciones que no pasan por el ajax (cómo el eliminar o restaurar usuario)
 Nota: No afecta al ajax -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mensaje = document.getElementById('mensaje');
        if (mensaje && !mensaje.classList.contains('hidden')) {
            setTimeout(() => {
                mensaje.style.display = 'none';
            }, 4000);
        }
    });
</script>
