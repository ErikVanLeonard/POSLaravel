@extends('layouts.app')

@section('title', 'Productos')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bulma.min.css">
@endpush

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-12">
                <div class="box">
                    <div class="level">
                        <div class="level-left">
                            <h2 class="title is-4 has-text-info">Lista de Productos</h2>
                        </div>
                        <div class="level-right">
                            <a href="{{ route('products.create') }}" class="button is-primary">
                                <span class="icon">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span>Nuevo Producto</span>
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="notification is-success is-light">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-container">
                        <table id="products-table" class="table is-fullwidth is-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>Cód. Barras</th>
                                    <th>Precio Menudeo</th>
                                    <th>Precio Mayoreo</th>
                                    <th>Stock</th>
                                    <th>Categoría</th>
                                    <th class="has-text-centered">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if($product->image_path)
                                                <figure class="image is-48x48">
                                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->nombre }}">
                                                </figure>
                                            @else
                                                <span class="has-text-grey-light">Sin imagen</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->nombre }}</td>
                                        <td>{{ $product->barcode }}</td>
                                        <td>${{ number_format($product->precio_menudeo, 2) }}</td>
                                        <td>${{ number_format($product->precio_mayoreo, 2) }}</td>
                                        <td>{{ $product->stock_actual }}</td>
                                        <td>{{ $product->category ? $product->category->name : 'N/A' }}</td>
                                        <td class="has-text-centered">
                                            <div class="buttons is-centered">
                                                <a href="{{ route('products.show', $product->id) }}" class="button is-info is-small">
                                                    <span class="icon">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </a>
                                                <a href="{{ route('products.edit', $product->id) }}" class="button is-warning is-small">
                                                    <span class="icon">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                </a>
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="is-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="button is-danger is-small" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                                        <span class="icon">
                                                            <i class="fas fa-trash"></i>
                                                        </span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bulma.min.js"></script>

<!-- PDF Export Dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
    // Función auto-ejecutable para evitar contaminación del ámbito global
    (function() {
        // Asegurarse de que jQuery esté cargado
        if (typeof jQuery == 'undefined') {
            console.error('jQuery no está cargado');
            return; // Este return ahora está dentro de una función
        } else {
            console.log('jQuery versión:', jQuery.fn.jquery);
        }
        
        // Verificar si DataTables está cargado
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('DataTables no está cargado');
            return; // Este return ahora está dentro de una función
        }

        $(document).ready(function() {
        console.log('Inicializando DataTable...');
        
        // Verificar si la tabla existe
        if ($.fn.DataTable.isDataTable('#products-table')) {
            $('#products-table').DataTable().destroy();
            console.log('DataTable anterior destruido');
        }
        
        // Inicializar DataTable con configuración mínima
        var table = $('#products-table').DataTable({
            responsive: true,
            paging: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [[0, 'desc']],
            language: {
                "decimal": ",",
                "thousands": ".",
                "emptyTable": "No hay datos disponibles en la tabla",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                "infoFiltered": "(filtrado de _MAX_ registros en total)",
                "infoPostFix": "",
                "lengthMenu": "Mostrar _MENU_ registros",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros coincidentes",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdf',
                    text: 'Exportar a PDF',
                    className: 'button is-info is-small',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6, 7] // Columnas a exportar (0-based index)
                    }
                }
            ],
            columnDefs: [
                { orderable: false, targets: [1, 8] }, // Deshabilitar ordenación para columnas de imagen y acciones
                { width: '5%', targets: 0 },  // ID
                { width: '10%', targets: 1 }, // Imagen
                { width: '15%', targets: 2 }, // Nombre
                { width: '10%', targets: 3 }, // Código de barras
                { width: '10%', targets: 4 }, // Precio menudeo
                { width: '10%', targets: 5 }, // Precio mayoreo
                { width: '5%', targets: 6 },  // Stock
                { width: '15%', targets: 7 }, // Categoría
                { width: '15%', targets: 8 }  // Acciones
            ]
        });
        
        console.log('DataTable inicializado correctamente');
        });
    })(); // Fin de la función auto-ejecutable
</script>
@endpush
