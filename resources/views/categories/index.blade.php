@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-10">
                <div class="box">
                    <div class="level">
                        <div class="level-left">
                            <h2 class="title is-4 has-text-info">Categorías</h2>
                        </div>
                        <div class="level-right">
                            <a href="{{ route('categories.create') }}" class="button is-primary">
                                <span class="icon">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span>Nueva Categoría</span>
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="notification is-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-container">
                        <table class="table is-fullwidth is-striped is-hoverable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Creado</th>
                                    <th>Actualizado</th>
                                    <th class="has-text-centered">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>{{ $category->updated_at->format('d/m/Y H:i:s') }}</td>
                                        <td class="has-text-centered">
                                            <div class="buttons is-centered">
                                                <a href="{{ route('categories.show', $category) }}" class="button is-info is-small">
                                                    <span class="icon">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </a>
                                                <a href="{{ route('categories.edit', $category) }}" class="button is-warning is-small">
                                                    <span class="icon">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                </a>
                                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="is-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="button is-danger is-small" onclick="return confirm('¿Está seguro de eliminar esta categoría?')">
                                                        <span class="icon">
                                                            <i class="fas fa-trash"></i>
                                                        </span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="has-text-centered">No hay categorías registradas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
