@php
  $isAdmin = auth()->check() && (auth()->user()->fk_tipo_user == 1);
  $autor = ($comentario->anonimo && !$isAdmin) ? 'Anónimo' : ($comentario->autor->username ?? 'Usuario');
@endphp

<article class="border rounded-lg p-4 @if($comentario->estatus!=='visible') bg-yellow-50 @endif">
  <header class="text-sm text-gray-600">
    <strong>{{ $autor }}</strong> • {{ $comentario->fecha_creacion->diffForHumans() }}
    @if($comentario->estatus!=='visible') <em>({{ $comentario->estatus }})</em> @endif
  </header>

  <p class="mt-2">{{ $comentario->contenido }}</p>

  <div class="mt-3 flex gap-3 text-sm">
    <a href="#" data-reply-toggle="#reply-{{ $comentario->pk_comentario }}" class="text-purple-700">Responder</a>

    @can('moderate-comments')
      @if($comentario->estatus==='visible')
        <form method="POST" action="{{ route('comentarios.hide',$comentario) }}">@csrf @method('PATCH')
          <button class="text-amber-700">Ocultar</button>
        </form>
      @else
        <form method="POST" action="{{ route('comentarios.show',$comentario) }}">@csrf @method('PATCH')
          <button class="text-green-700">Mostrar</button>
        </form>
      @endif
    @endcan

    @can('delete',$comentario)
      <form id="delete-form-{{ $comentario->pk_comentario }}" method="POST" action="{{ route('comentarios.destroy',$comentario) }}">
        @csrf
        @method('DELETE')
        <button type="submit"
                data-swal-form
                data-target-form="delete-form-{{ $comentario->pk_comentario }}"
                data-swal-title="Eliminar comentario"
                data-swal-text="¿Deseas eliminar este comentario? Esta acción no se puede deshacer."
                data-swal-icon="warning"
                data-swal-confirm="Sí, eliminar"
                data-swal-cancel="Cancelar"
                class="text-red-700 hover:underline">
          Eliminar
        </button>
      </form>
    @endcan
  </div>

  {{-- Caja de respuesta (oculta hasta click) --}}
  <div id="reply-{{ $comentario->pk_comentario }}" class="hidden mt-3">
    <form method="POST" action="{{ route('comentarios.reply', $comentario) }}" class="flex items-center gap-2">
      @csrf
      <input type="text" name="contenido" required maxlength="1000"
             class="flex-1 border rounded p-2" placeholder="Escribe una respuesta...">
      <button type="submit" class="px-3 py-2 rounded bg-purple-600 text-white">Enviar</button>
    </form>
  </div>

  @if($comentario->respuestas->count())
    <div class="mt-3 pl-4 border-l space-y-3">
      @foreach($comentario->respuestas as $r)
        @include('comentarios._comentario', ['comentario' => $r])
      @endforeach
    </div>
  @endif
</article>

@pushOnce('scripts')
<script>
document.addEventListener('click', function(e){
  const btn = e.target.closest('[data-reply-toggle]');
  if(!btn) return;
  e.preventDefault();
  const sel = btn.getAttribute('data-reply-toggle');
  const box = document.querySelector(sel);
  if (box) {
    box.classList.toggle('hidden');
    const input = box.querySelector('input[name="contenido"]');
    if (input) input.focus();
  }
});
</script>
@endPushOnce
