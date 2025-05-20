@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="title">Editar Producto</h1>


        @if ($errors->any())
            <div class="notification is-danger">
                <button class="delete"></button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="field">
                <label class="label">Nombre del Producto</label>
                <div class="control">
                    <input class="input" type="text" id="nombre" name="nombre" value="{{ old('nombre', $product->nombre) }}" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Código de Barras (ID)</label>
                <div class="control">
                    <input class="input" type="text" id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Precio Menudeo</label>
                <div class="control has-icons-left">
                    <input class="input" type="number" step="0.01" id="precio_menudeo" name="precio_menudeo" value="{{ old('precio_menudeo', $product->precio_menudeo) }}" required>
                    <span class="icon is-small is-left">
                        <i class="fas fa-dollar-sign"></i>
                    </span>
                </div>
            </div>

            <div class="field">
                <label class="label">Precio Mayoreo</label>
                <div class="control has-icons-left">
                    <input class="input" type="number" step="0.01" id="precio_mayoreo" name="precio_mayoreo" value="{{ old('precio_mayoreo', $product->precio_mayoreo) }}" required>
                    <span class="icon is-small is-left">
                        <i class="fas fa-dollar-sign"></i>
                    </span>
                </div>
            </div>

            <div class="field">
                <label class="label">Stock Actual</label>
                <div class="control">
                    <input class="input" type="number" id="stock_actual" name="stock_actual" value="{{ old('stock_actual', $product->stock_actual) }}" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Categoría</label>
                <div class="control">
                    <div class="select is-fullwidth">
                        <select id="category_id" name="category_id" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <label class="label">Imagen del Producto</label>
                <div class="file">
                    <label class="file-label">
                        <input class="file-input" type="file" id="image_path" name="image_path">
                        <span class="file-cta">
                            <span class="file-icon">
                                <i class="fas fa-upload"></i>
                            </span>
                            <span class="file-label">
                                Elegir archivo...
                            </span>
                        </span>
                    </label>
                </div>
                @if($product->image_path)
                    <figure class="image is-128x128 mt-2">
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->nombre }}">
                    </figure>
                @endif
            </div>

            <div class="field is-grouped mt-5">
                <div class="control">
                    <button type="submit" class="button is-primary">
                        <span class="icon">
                            <i class="fas fa-save"></i>
                        </span>
                        <span>Actualizar Producto</span>
                    </button>
                </div>
                <div class="control">
                    <a href="{{ route('products.index') }}" class="button is-light">
                        <span class="icon">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                        <span>Cancelar</span>
                    </a>
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
    // Script para mostrar el nombre del archivo seleccionado
    const fileInput = document.querySelector('#image_path');
    if (fileInput) {
        fileInput.onchange = () => {
            if (fileInput.files.length > 0) {
                const fileName = document.querySelector('.file-name');
                if (fileName) {
                    fileName.textContent = fileInput.files[0].name;
                }
            }
        };
    }

    // Cerrar notificación de errores
    document.addEventListener('DOMContentLoaded', () => {
        (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
            $notification = $delete.parentNode;
            $delete.addEventListener('click', () => {
                $notification.parentNode.removeChild($notification);
            });
        });
    });
</script>
@endpush
@endsection
