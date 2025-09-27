<style>
#mensaje {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%) translateY(-100%);
    z-index: 9999;
    min-width: 250px;
    max-width: 90%;
    text-align: center;
    padding: 12px 20px;
    border-radius: 8px;
    color: white;
    font-size: 0.875rem;
    font-weight: 500;
    box-shadow: 0 4px 6px rgba(0,0,0,0.2);
    opacity: 0;
    animation: slideDown 0.5s forwards;
}

.success {
    background-color: #16a34a;
}
.error {
    background-color: #dc2626;
}

@keyframes slideDown {
    from {
        transform: translateX(-50%) translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    to {
        transform: translateX(-50%) translateY(-100%);
        opacity: 0;
    }
}
</style>

@if (session('success'))
    <div id="mensaje" class="success">
        {{ session('success') }}
    </div>
@elseif (session('error'))
    <div id="mensaje" class="error">
        {{ session('error') }}
    </div>
@else
    <div id="mensaje" class="hidden"></div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mensaje = document.getElementById('mensaje');
    if (mensaje && !mensaje.classList.contains('hidden')) {
        setTimeout(() => {
            mensaje.style.animation = 'slideUp 0.5s forwards';
            setTimeout(() => {
                mensaje.style.display = 'none';
            }, 500);
        }, 4000);
    }
});
</script>
