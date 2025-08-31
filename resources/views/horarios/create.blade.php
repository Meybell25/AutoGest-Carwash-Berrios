@extends('layouts.app')

@section('title', 'Nuevo Horario')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Nuevo Horario</h2>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.horarios.store') }}">
            @csrf

            @include('horarios._form')
        </form>
    </div>
</div>
@endsection

