@extends('layouts.app')

@section('title', 'Detalles de la Categoría')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box">
                    <div class="level">
                        <div class="level-left">
                            <h2 class="title is-4 has-text-info">
                                <span class="icon">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <span>Detalles de la Categoría</span>
                            </h2>
                        </div>
                        <div class="level-right">
                            <div class="tags has-addons">
                                <span class="tag is-dark">ID</span>
                                <span class="tag is-info">#{{ $category->id }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="box has-background-light">
                            <div class="columns is-vcentered">
                                <div class="column is-3 has-text-weight-semibold">
                                    <span class="icon">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <span>Nombre:</span>
                                </div>
                                <div class="column">
                                    <span class="tag is-medium is-info is-light">
                                        {{ $category->name }}
                                    </span>
                                </div>
                            </div>

                            <hr class="my-2">

                            <div class="columns is-vcentered">
                                <div class="column is-3 has-text-weight-semibold">
                                    <span class="icon">
                                        <i class="fas fa-calendar-plus"></i>
                                    </span>
                                    <span>Creado:</span>
                                </div>
                                <div class="column">
                                    <time datetime="{{ $category->created_at->toIso8601String() }}" 
                                          title="{{ $category->created_at->diffForHumans() }}">
                                        {{ $category->created_at->format('d/m/Y H:i:s') }}
                                    </time>
                                </div>
                            </div>

                            <div class="columns is-vcentered">
                                <div class="column is-3 has-text-weight-semibold">
                                    <span class="icon">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <span>Actualizado:</span>
                                </div>
                                <div class="column">
                                    <time datetime="{{ $category->updated_at->toIso8601String() }}" 
                                          title="{{ $category->updated_at->diffForHumans() }}">
                                        {{ $category->updated_at->format('d/m/Y H:i:s') }}
                                    </time>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field is-grouped is-grouped-right mt-5">
                        <div class="control">
                            <a href="{{ route('categories.index') }}" class="button is-light">
                                <span class="icon">
                                    <i class="fas fa-arrow-left"></i>
                                </span>
                                <span>Volver al listado</span>
                            </a>
                        </div>
                        <div class="control">
                            <a href="{{ route('categories.edit', $category) }}" class="button is-warning">
                                <span class="icon">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <span>Editar</span>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="is-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="button is-danger ml-2" 
                                        onclick="return confirm('¿Está seguro de eliminar esta categoría? Esta acción no se puede deshacer.')">
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
</section>

@push('scripts')
<script>
    // Agregar tooltips a los elementos de tiempo
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips de Bulma
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-tooltip]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
