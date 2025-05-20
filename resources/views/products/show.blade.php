@extends('layouts.app')

@section('title', 'Detalles del Producto')

@section('content')
    <section class="section">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="box">
                        <h2 class="title is-5 has-text-info">Detalles del Producto</h2>

                        <div class="content">
                            <div class="columns is-mobile mb-2">
                                <div class="column is-3"><strong>ID</strong></div>
                                <div class="column">{{ $product->id }}</div>
                            </div>

                            <div class="columns is-mobile mb-2">
                                <div class="column is-3"><strong>Nombre</strong></div>
                                <div class="column">{{ $product->nombre }}</div>
                            </div>

                            <div class="columns is-mobile mb-2">
                                <div class="column is-3"><strong>Imagen</strong></div>
                                <div class="column">
                                    @if($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->nombre }}" width="100">
                                    @else
                                        Sin imagen
                                    @endif
                                </div>
                            </div>

                            <div class="columns is-mobile mb-2">
                                <div class="column is-3"><strong>Cód. Barras</strong></div>
                                <div class="column">{{ $product->barcode ?? 'N/A' }}</div>
                            </div>

                            <div class="columns is-mobile mb-2">
                                <div class="column is-3"><strong>Precio Menudeo</strong></div>
                                <div class="column">${{ number_format($product->precio_menudeo, 2) }}</div>
                            </div>

                            <div class="columns is-mobile mb-2">
                                <div class="column is-3"><strong>Precio Mayoreo</strong></div>
                                <div class="column">${{ number_format($product->precio_mayoreo, 2) }}</div>
                            </div>

                            <div class="columns is-mobile mb-2">
                                <div class="column is-3"><strong>Stock Actual</strong></div>
                                <div class="column">{{ $product->stock_actual }}</div>
                            </div>

                            <div class="columns is-mobile mb-2">
                                <div class="column is-3"><strong>Categoría</strong></div>
                                <div class="column">{{ $product->category ? $product->category->name : 'N/A' }}</div>
                            </div>

                            <div class="columns is-mobile mb-2">
                                <div class="column is-3"><strong>Creado</strong></div>
                                <div class="column">{{ $product->created_at->format('d/m/Y H:i:s') }}</div>
                            </div>

                            <div class="columns is-mobile mb-2">
                                <div class="column is-3"><strong>Actualizado</strong></div>
                                <div class="column">{{ $product->updated_at->format('d/m/Y H:i:s') }}</div>
                            </div>
                        </div>

                        <div class="field is-grouped is-grouped-justified mt-5">
                            <div class="control">
                                <a href="{{ route('products.index') }}" class="button is-light">Volver</a>
                            </div>
                            <div class="control">
                                <a href="{{ route('products.edit', $product) }}" class="button is-warning">Editar</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="is-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button is-danger ml-2" onclick="return confirm('¿Está seguro de eliminar este producto?')">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
