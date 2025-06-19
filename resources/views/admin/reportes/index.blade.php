@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reportes</h1>
    <p>Implementa aquí tus reportes.</p>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        Volver al Dashboard
    </a>
</div>
@endsection