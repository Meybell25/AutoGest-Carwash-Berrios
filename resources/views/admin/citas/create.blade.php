@extends('layouts.app') {{-- Asegúrate de tener una layout base --}}

@section('content')
<div class="container">
    <h1>Crear Nueva Cita</h1>
    <p>Implementa aquí el formulario para crear citas.</p>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        Volver al Dashboard
    </a>
</div>
@endsection