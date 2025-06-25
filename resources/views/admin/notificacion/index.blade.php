@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Notificaciones de {{ $usuario->nombre }}</h4>
                    <div>
                        <span class="badge bg-danger" id="unread-count">{{ $noLeidas }}</span>
                        <button class="btn btn-sm btn-success ms-2" id="mark-all-read">
                            Marcar todas como leídas
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="list-group">
                        @forelse ($notificaciones as $notificacion)
                            <div class="list-group-item list-group-item-action flex-column align-items-start 
                                {{ $notificacion->leido ? '' : 'bg-light' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $notificacion->titulo }}</h5>
                                    <small>{{ $notificacion->fecha_envio->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $notificacion->mensaje }}</p>
                                @if(!$notificacion->leido)
                                    <button class="btn btn-sm btn-outline-primary mark-read" 
                                        data-id="{{ $notificacion->id }}">
                                        Marcar como leída
                                    </button>
                                @endif
                            </div>
                        @empty
                            <div class="list-group-item">
                                No hay notificaciones para mostrar.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-3">
                        {{ $notificaciones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Marcar una notificación como leída
    $('.mark-read').click(function() {
        const notificacionId = $(this).data('id');
        const button = $(this);
        
        $.ajax({
            url: `/notificaciones/${notificacionId}/marcar-leida`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                button.parent().removeClass('bg-light');
                button.remove();
                updateUnreadCount();
            }
        });
    });

    // Marcar todas como leídas
    $('#mark-all-read').click(function() {
        const userId = '{{ $usuario->id }}';
        
        $.ajax({
            url: `/notificaciones/${userId}/marcar-todas-leidas`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('.list-group-item').removeClass('bg-light');
                $('.mark-read').remove();
                updateUnreadCount();
            }
        });
    });

    // Actualizar contador de no leídas
    function updateUnreadCount() {
        const userId = '{{ $usuario->id }}';
        
        $.get(`/notificaciones/${userId}/contar-no-leidas`, function(response) {
            $('#unread-count').text(response.no_leidas);
        });
    }
});
</script>
@endsection