@extends('layouts.app')

@section('title', 'Nuevo horario')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="mb-0">Crear horario</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.horarios.store') }}" method="POST">
            @csrf
            @include('HorariosViews._form')
        </form>
    </div>
</div>
@endsection

