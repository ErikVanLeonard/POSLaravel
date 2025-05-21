@extends('layouts.app')

@section('title', 'Proveedores')

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
                            <h2 class="title is-4 has-text-info">
                                <span class="icon">
                                    <i class="fas fa-truck"></i>
                                </span>
                                <span>Proveedores</span>
                            </h2>
                        </div>
                        <div class="level-right">
                            <a href="{{ route('providers.create') }}" class="button is-primary">
                                <span class="icon">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span>Nuevo Proveedor</span>
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="notification is-success is-light">
                            <button class="delete"></button>
                            {{ session('success') }}
                        </div>
                    @endif

                        <div class="tabs is-boxed is-centered">
                            <ul>
                                <li class="is-active" data-tab="active">
                                    <a>
                                        <span class="icon is-small"><i class="fas fa-list"></i></span>
                                        <span>Activos</span>
                                    </a>
                                </li>
                                <li data-tab="trashed">
                                    <a>
                                        <span class="icon is-small"><i class="fas fa-trash"></i></span>
                                        <span>Papelera</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">
                        <div class="tab-pane is-active" id="active">
                            <div class="table-container">
                                <table class="table is-striped is-hoverable is-fullwidth" id="providers-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Compañía</th>
                                                <th>Representante</th>
                                                <th>Teléfono</th>
                                                <th>Email</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($providers->whereNull('deleted_at') as $provider)
                                            <tr>
                                                <td>{{ $provider->id }}</td>
                                                <td>{{ $provider->company }}</td>
                                                <td>{{ $provider->contact_name }}</td>
                                                <td>{{ $provider->phone }}</td>
                                                <td>{{ $provider->email }}</td>
                                                <td class="has-text-centered">
                                                    <div class="buttons are-small is-justify-content-center">
                                                        <a href="{{ route('providers.show', $provider->id) }}" class="button is-info is-small" title="Ver">
                                                            <span class="icon">
                                                                <i class="fas fa-eye"></i>
                                                            </span>
                                                        </a>
                                                        <a href="{{ route('providers.edit', $provider->id) }}" class="button is-primary is-small" title="Editar">
                                                            <span class="icon">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                        </a>
                                                        <form action="{{ route('providers.destroy', $provider->id) }}" method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="button is-danger is-small" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este proveedor?')">
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
                                                <td colspan="6" class="has-text-centered">No hay proveedores registrados</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="trashed">
                            <div class="table-container">
                                <table class="table is-striped is-hoverable is-fullwidth" id="trashed-providers-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Compañía</th>
                                                <th>Representante</th>
                                                <th>Teléfono</th>
                                                <th>Email</th>
                                                <th>Eliminado el</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($providers->whereNotNull('deleted_at') as $provider)
                                            <tr>
                                                <td>{{ $provider->id }}</td>
                                                <td>{{ $provider->company }}</td>
                                                <td>{{ $provider->contact_name }}</td>
                                                <td>{{ $provider->phone }}</td>
                                                <td>{{ $provider->email }}</td>
                                                <td>{{ $provider->deleted_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <form action="{{ route('providers.restore', $provider->id) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('POST')
                                                        <button type="submit" class="button is-warning is-small" title="Restaurar">
                                            <span class="icon is-small">
                                                <i class="fas fa-trash-restore"></i>
                                            </span>
                                            <span>Restaurar</span>
                                        </button>
                                                    </form>
                                                    <form action="{{ route('providers.force-delete', $provider->id) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="button is-danger is-small" title="Eliminar permanentemente" onclick="return confirm('¿Estás seguro de eliminar permanentemente este proveedor? Esta acción no se puede deshacer.')">
                                            <span class="icon is-small">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                            <span>Eliminar permanentemente</span>
                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="has-text-centered">No hay proveedores eliminados</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Estilos para DataTables con Bulma */
    .dataTables_wrapper {
        width: 100%;
        margin: 1rem 0;
    }
    .dataTables_length select {
        padding-right: 2.5em;
        background-color: #fff;
        border-color: #dbdbdb;
        border-radius: 4px;
        padding: 5px 10px;
    }
    .dataTables_filter input {
        padding: 0.5em;
        border: 1px solid #dbdbdb;
        border-radius: 4px;
        margin-left: 0.5em;
    }
    .dataTables_info {
        padding: 0.75em 0;
        color: #4a4a4a;
    }
    .dataTables_paginate {
        padding: 0.75em 0;
    }
    .dataTables_paginate .paginate_button {
        padding: 0.5em 1em;
        margin: 0 0.25em;
        border: 1px solid #dbdbdb;
        border-radius: 4px;
        color: #363636;
    }
    .dataTables_paginate .paginate_button.current {
        background-color: #485fc7;
        color: white !important;
        border-color: #485fc7;
    }
    .dataTables_paginate .paginate_button:hover {
        background-color: #f5f5f5;
        color: #363636 !important;
    }
    .dataTables_paginate .paginate_button.current:hover {
        background-color: #3a56c0;
        color: white !important;
    }
        cursor: pointer;
    }
    .dataTables_paginate a.current {
        background-color: #485fc7;
        border-color: #485fc7;
        color: white;
    }
    .dataTables_empty {
        padding: 1rem;
        text-align: center;
        color: #7a7a7a;
    }
    /* Estilos para mensajes de error */
    .datatable-error {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
        background-color: #feecf0;
        color: #cc0f35;
    }
    /* Estilos para las pestañas */
    .tab-pane {
        display: none;
    }
    .tab-pane.is-active {
        display: block;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
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

<script>
    $(document).ready(function() {
        // Configuración de DataTables en español
        var espanol = {
            "decimal": ",",
            "thousands": ".",
            "emptyTable": "No hay datos disponibles en la tabla",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros en total)",
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
        };

        // Inicializar DataTable para proveedores activos
        var activeTable = $('#providers-table').DataTable({
            responsive: true,
            paging: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [[0, 'desc']],
            language: espanol,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'button is-success is-small',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    title: 'Proveedores',
                    extension: '.xlsx'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'button is-danger is-small',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    title: 'Listado de Proveedores'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'button is-info is-small',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    title: 'Listado de Proveedores'
                }
            ],
            columnDefs: [
                { orderable: false, targets: [5] },
                { width: '5%', targets: 0 },
                { width: '25%', targets: 1 },
                { width: '20%', targets: 2 },
                { width: '15%', targets: 3 },
                { width: '20%', targets: 4 },
                { width: '15%', targets: 5 }
            ]
        });

        // Inicializar DataTable para proveedores eliminados
        var trashedTable = $('#trashed-providers-table').DataTable({
            responsive: true,
            paging: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [[0, 'desc']],
            language: espanol,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'button is-success is-small',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    title: 'Proveedores Eliminados',
                    extension: '.xlsx'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'button is-danger is-small',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    title: 'Proveedores Eliminados'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'button is-info is-small',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    title: 'Proveedores Eliminados'
                }
            ],
            columnDefs: [
                { orderable: false, targets: [6] },
                { width: '5%', targets: 0 },
                { width: '20%', targets: 1 },
                { width: '15%', targets: 2 },
                { width: '15%', targets: 3 },
                { width: '20%', targets: 4 },
                { width: '10%', targets: 5 },
                { width: '15%', targets: 6 }
            ]
        });

        // Manejar las pestañas
        $('.tabs li').on('click', function() {
            var tab = $(this).data('tab');
            $('.tabs li').removeClass('is-active');
            $(this).addClass('is-active');
            $('.tab-pane').removeClass('is-active');
            $('#' + tab).addClass('is-active');
            
            // Redibujar la tabla cuando se cambia de pestaña
            if (tab === 'active') {
                activeTable.columns.adjust().responsive.recalc();
            } else {
                trashedTable.columns.adjust().responsive.recalc();
            }
        });

        // Cerrar notificaciones
        document.addEventListener('DOMContentLoaded', () => {
            (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
                const $notification = $delete.parentNode;
                $delete.addEventListener('click', () => {
                    $notification.parentNode.removeChild($notification);
                });
            });
        });
    });
</script>
@endpush
@endpush
