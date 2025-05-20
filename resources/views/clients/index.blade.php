@extends('layouts.app')

@section('title', 'Clientes')

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
                                    <i class="fas fa-users"></i>
                                </span>
                                <span>Clientes</span>
                            </h2>
                        </div>
                        <div class="level-right">
                            <a href="{{ route('clients.create') }}" class="button is-primary">
                                <span class="icon">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span>Nuevo Cliente</span>
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="notification is-success is-light">
                            <button class="delete"></button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="notification is-danger is-light">
                            <button class="delete"></button>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-container">
                        <table id="clients-table" class="table is-fullwidth is-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Empresa</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th>Registro</th>
                                    <th class="has-text-centered">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                    <tr>
                                        <td>{{ $client->id }}</td>
                                        <td>{{ $client->name }}</td>
                                        <td>{{ $client->company ?? 'N/A' }}</td>
                                        <td>{{ $client->phone }}</td>
                                        <td>{{ $client->email ?? 'N/A' }}</td>
                                        <td>{{ $client->created_at->format('d/m/Y') }}</td>
                                        <td class="has-text-centered">
                                            <div class="buttons is-centered">
                                                <a href="{{ route('clients.show', $client) }}" class="button is-info is-small">
                                                    <span class="icon">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </a>
                                                <a href="{{ route('clients.edit', $client) }}" class="button is-warning is-small">
                                                    <span class="icon">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                </a>
                                                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="is-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="button is-danger is-small" onclick="return confirm('¿Está seguro de eliminar este cliente?')">
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

<!-- Export Dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializar DataTable
        var table = $('#clients-table').DataTable({
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
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    title: 'Clientes',
                    extension: '.xlsx'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'button is-danger is-small',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    title: 'Listado de Clientes'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'button is-info is-small',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    title: 'Listado de Clientes'
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
