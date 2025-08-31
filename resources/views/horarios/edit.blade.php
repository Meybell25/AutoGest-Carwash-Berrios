@extends('layouts.app')

@section('title', 'Editar Horario')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Editar Horario</h2>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.horarios.update', $horario->id) }}">
            @csrf
            @method('PUT')

            @include('horarios._form', ['horario' => $horario])
        </form>
    </div>
</div>
@endsection

