@extends('layouts.app')

@section('title', 'Editar horario')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Editar horario</h2>
            <form action="{{ route('admin.horarios.destroy', $horario) }}" method="POST" onsubmit="return confirm('¿Eliminar este horario?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Eliminar</button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.horarios.update', $horario) }}" method="POST">
            @csrf
            @method('PUT')
            @include('HorariosViews._form')
        </form>
    </div>
</div>
@endsection
