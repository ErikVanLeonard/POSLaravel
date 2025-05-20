@extends('layouts.app')

@section('title', 'Detalles de la Categoría')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box">
                    <h2 class="title is-4 has-text-info">Detalles de la Categoría</h2>

                    <div class="content">
                        <div class="columns is-mobile mb-2">
                            <div class="column is-3"><strong>ID</strong></div>
                            <div class="column">{{ $category->id }}</div>
                        </div>

                        <div class="columns is-mobile mb-2">
                            <div class="column is-3"><strong>Nombre</strong></div>
                            <div class="column">{{ $category->name }}</div>
                        </div>

                        <div class="columns is-mobile mb-2">
                            <div class="column is-3"><strong>Creado</strong></div>
                            <div class="column">{{ $category->created_at->format('d/m/Y H:i:s') }}</div>
                        </div>

                        <div class="columns is-mobile mb-2">
                            <div class="column is-3"><strong>Actualizado</strong></div>
                            <div class="column">{{ $category->updated_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                    </div>

                    <div class="field is-grouped mt-5">
                        <div class="control">
                            <a href="{{ route('categories.index') }}" class="button is-light">Volver</a>
                        </div>
                        <div class="control">
                            <a href="{{ route('categories.edit', $category) }}" class="button is-warning">Editar</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="is-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button is-danger ml-2" onclick="return confirm('¿Está seguro de eliminar esta categoría?')">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
