@extends('layouts.app')

@section('title', 'Detalles del Cliente')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box">
                    <h2 class="title is-4 has-text-info">
                        <span class="icon">
                            <i class="fas fa-user"></i>
                        </span>
                        <span>Detalles del Cliente</span>
                    </h2>

                    @if(session('success'))
                        <div class="notification is-success is-light">
                            <button class="delete"></button>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="content">
                        <div class="field">
                            <label class="label">Nombre</label>
                            <p>{{ $client->name }}</p>
                        </div>

                        <div class="field">
                            <label class="label">Empresa</label>
                            <p>{{ $client->company ?? 'No especificada' }}</p>
                        </div>

                        <div class="field">
                            <label class="label">RFC</label>
                            <p>{{ $client->rfc ?? 'No especificado' }}</p>
                        </div>

                        <div class="field">
                            <label class="label">Dirección</label>
                            <p>{{ $client->address ?? 'No especificada' }}</p>
                        </div>

                        <div class="field">
                            <label class="label">Teléfono</label>
                            <p>{{ $client->phone }}</p>
                        </div>

                        <div class="field">
                            <label class="label">Correo Electrónico</label>
                            <p>{{ $client->email ?? 'No especificado' }}</p>
                        </div>

                        <div class="field is-grouped mt-5">
                            <div class="control">
                                <a href="{{ route('clients.edit', $client) }}" class="button is-warning">
                                    <span class="icon">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <span>Editar</span>
                                </a>
                            </div>
                            <div class="control">
                                <a href="{{ route('clients.index') }}" class="button is-link">
                                    <span class="icon">
                                        <i class="fas fa-arrow-left"></i>
                                    </span>
                                    <span>Volver</span>
                                </a>
                            </div>
                            <div class="control">
                                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="is-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button is-danger" onclick="return confirm('¿Estás seguro de eliminar este cliente?')">
                                        <span class="icon">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <span>Eliminar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Cerrar notificaciones
    document.addEventListener('DOMContentLoaded', () => {
        (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
            const $notification = $delete.parentNode;
            $delete.addEventListener('click', () => {
                $notification.parentNode.removeChild($notification);
            });
        });
    });
</script>
@endpush
@endsection
