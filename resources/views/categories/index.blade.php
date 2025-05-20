@extends('layouts.app')

@section('title', 'Categorías')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bulma.min.css">
@endpush

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
                        <table id="categories-table" class="table is-fullwidth is-striped" style="width:100%">
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

<!-- Export Dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<script>
    $(document).ready(function() {
        // Inicializar DataTable
        var table = $('#categories-table').DataTable({
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
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'button is-success is-small',
                    exportOptions: {
                        columns: [0, 1, 2, 3] // Columnas a exportar (0-based index)
                    },
                    title: 'Categorias',
                    extension: '.xlsx'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'button is-danger is-small',
                    exportOptions: {
                        columns: [0, 1, 2, 3] // Columnas a exportar (0-based index)
                    },
                    title: 'Categorias'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'button is-info is-small',
                    exportOptions: {
                        columns: [0, 1, 2, 3] // Columnas a exportar (0-based index)
                    },
                    title: 'Listado de Categorias'
                }
            ],
            columnDefs: [
                { orderable: false, targets: [4] }, // Deshabilitar ordenación para columna de acciones
                { width: '5%', targets: 0 },  // ID
                { width: '30%', targets: 1 }, // Nombre
                { width: '20%', targets: 2 }, // Creado
                { width: '20%', targets: 3 }, // Actualizado
                { width: '25%', targets: 4 }  // Acciones
            ]
        });
    });
</script>
@endpush
