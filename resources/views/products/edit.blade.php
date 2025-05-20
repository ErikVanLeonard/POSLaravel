@extends('layouts.app') {{-- Asumiendo que tienes un layout base --}}

@section('content')
<div class="container">
    <h1>Editar Producto</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $product->nombre) }}" required>
        </div>

        <div class="form-group">
            <label for="barcode">Código de Barras (ID):</label>
            <input type="text" class="form-control" id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}" required>
        </div>

        <div class="form-group">
            <label for="precio_menudeo">Precio Menudeo:</label>
            <input type="number" step="0.01" class="form-control" id="precio_menudeo" name="precio_menudeo" value="{{ old('precio_menudeo', $product->precio_menudeo) }}" required>
        </div>

        <div class="form-group">
            <label for="precio_mayoreo">Precio Mayoreo:</label>
            <input type="number" step="0.01" class="form-control" id="precio_mayoreo" name="precio_mayoreo" value="{{ old('precio_mayoreo', $product->precio_mayoreo) }}" required>
        </div>

        <div class="form-group">
            <label for="stock_actual">Stock Actual:</label>
            <input type="number" class="form-control" id="stock_actual" name="stock_actual" value="{{ old('stock_actual', $product->stock_actual) }}" required>
        </div>

        <div class="form-group">
            <label for="category_id">Categoría:</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Seleccione una categoría</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="image_path">Imagen del Producto:</label>
            <input type="file" class="form-control-file" id="image_path" name="image_path">
            @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->nombre }}" width="100" class="mt-2">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Producto</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
