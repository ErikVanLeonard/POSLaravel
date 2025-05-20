@extends('layouts.app')

@section('title', 'Crear Producto')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box">
                    <h2 class="title is-4 has-text-info">Crear Nuevo Producto</h2>

                    @if ($errors->any())
                        <div class="notification is-danger is-light">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="field">
                            <label class="label">Código de Barras</label>
                            <div class="control">
                                <input type="text" name="barcode" class="input @error('barcode') is-danger @enderror" value="{{ old('barcode') }}" required>
                            </div>
                            @error('barcode')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label class="label">Nombre del Producto</label>
                            <div class="control">
                                <input type="text" name="nombre" class="input @error('nombre') is-danger @enderror" value="{{ old('nombre') }}" required>
                            </div>
                            @error('nombre')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="columns">
                            <div class="column">
                                <div class="field">
                                    <label class="label">Precio Menudeo</label>
                                    <div class="control has-icons-left">
                                        <input type="number" step="0.01" name="precio_menudeo" class="input @error('precio_menudeo') is-danger @enderror" value="{{ old('precio_menudeo') }}" required>
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-dollar-sign"></i>
                                        </span>
                                    </div>
                                    @error('precio_menudeo')
                                        <p class="help is-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="column">
                                <div class="field">
                                    <label class="label">Precio Mayoreo</label>
                                    <div class="control has-icons-left">
                                        <input type="number" step="0.01" name="precio_mayoreo" class="input @error('precio_mayoreo') is-danger @enderror" value="{{ old('precio_mayoreo') }}" required>
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-dollar-sign"></i>
                                        </span>
                                    </div>
                                    @error('precio_mayoreo')
                                        <p class="help is-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Stock Inicial</label>
                            <div class="control">
                                <input type="number" name="stock_actual" class="input @error('stock_actual') is-danger @enderror" value="{{ old('stock_actual', 0) }}" required>
                            </div>
                            @error('stock_actual')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label class="label">Categoría</label>
                            <div class="control">
                                <div class="select is-fullwidth @error('category_id') is-danger @enderror">
                                    <select name="category_id" required>
                                        <option value="">Selecciona una categoría</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('category_id')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label class="label">Imagen del Producto</label>
                            <div class="file has-name is-fullwidth">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="image" accept="image/*">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="fas fa-upload"></i>
                                        </span>
                                        <span class="file-label">
                                            Seleccionar archivo
                                        </span>
                                    </span>
                                    <span class="file-name">
                                        Ningún archivo seleccionado
                                    </span>
                                </label>
                            </div>
                            @error('image')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field is-grouped is-grouped-right mt-5">
                            <div class="control">
                                <a href="{{ route('products.index') }}" class="button is-light">Cancelar</a>
                            </div>
                            <div class="control">
                                <button type="submit" class="button is-primary">
                                    <span class="icon">
                                        <i class="fas fa-save"></i>
                                    </span>
                                    <span>Guardar Producto</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Actualizar el nombre del archivo seleccionado
        const fileInput = document.querySelector('.file-input');
        const fileName = document.querySelector('.file-name');

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                fileName.textContent = fileInput.files[0].name;
            } else {
                fileName.textContent = 'Ningún archivo seleccionado';
            }
        });
    });
</script>
@endpush

@endsection
