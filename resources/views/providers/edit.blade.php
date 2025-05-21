@extends('layouts.app')

@section('title', 'Editar Proveedor')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box">
                    <h2 class="title is-4 has-text-info">
                        <span class="icon">
                            <i class="fas fa-truck"></i>
                        </span>
                        <span>Editar Proveedor: {{ $provider->company }}</span>
                    </h2>

                    @if($errors->any())
                        <div class="notification is-danger">
                            <button class="delete"></button>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('providers.update', $provider->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('providers._form')
                    </form>

                    @if($provider->documents->count() > 0)
                    <div class="mt-5">
                        <h3 class="title is-5">Documentos del Proveedor</h3>
                        <div class="table-container">
                            <table class="table is-fullwidth is-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre del Documento</th>
                                        <th>Tipo</th>
                                        <th>Tamaño</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($provider->documents as $document)
                                    <tr>
                                        <td>{{ $document->name }}</td>
                                        <td>{{ strtoupper(pathinfo($document->file_path, PATHINFO_EXTENSION)) }}</td>
                                        <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                                        <td>
                                            <div class="buttons">
                                                <a href="{{ Storage::url($document->file_path) }}" class="button is-small is-info" target="_blank" title="Ver">
                                                    <span class="icon">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </a>
                                                <a href="{{ route('providers.document.download', $document->id) }}" class="button is-small is-success" title="Descargar">
                                                    <span class="icon">
                                                        <i class="fas fa-download"></i>
                                                    </span>
                                                </a>
                                                <button type="button" class="button is-small is-danger delete-document" data-id="{{ $document->id }}" title="Eliminar">
                                                    <span class="icon">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Cerrar notificaciones
        (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
            const $notification = $delete.parentNode;
            $delete.addEventListener('click', () => {
                $notification.parentNode.removeChild($notification);
            });
        });

        // Manejar la eliminación de documentos
        (document.querySelectorAll('.delete-document') || []).forEach(($button) => {
            $button.addEventListener('click', function() {
                const documentId = this.getAttribute('data-id');
                if (confirm('¿Estás seguro de que deseas eliminar este documento?')) {
                    fetch(`/providers/document/${documentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.closest('tr').remove();
                            // Mostrar notificación de éxito
                            const notification = document.createElement('div');
                            notification.className = 'notification is-success';
                            notification.innerHTML = `
                                <button class="delete"></button>
                                Documento eliminado correctamente.
                            `;
                            document.querySelector('.box').insertBefore(notification, document.querySelector('.box').firstChild);
                            
                            // Agregar evento para cerrar la notificación
                            notification.querySelector('.delete').addEventListener('click', () => {
                                notification.remove();
                            });
                            
                            // Eliminar la notificación después de 5 segundos
                            setTimeout(() => {
                                notification.remove();
                            }, 5000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ocurrió un error al eliminar el documento.');
                    });
                }
            });
        });
    });
</script>
@endpush
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Eliminar documento
        $('.delete-document').on('click', function() {
            const documentId = $(this).data('id');
            
            if (confirm('¿Estás seguro de eliminar este documento? Esta acción no se puede deshacer.')) {
                $.ajax({
                    url: '{{ url("providers/document") }}/' + documentId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Recargar la página para actualizar la lista de documentos
                            location.reload();
                        } else {
                            alert('Ocurrió un error al eliminar el documento.');
                        }
                    },
                    error: function() {
                        alert('Ocurrió un error al eliminar el documento.');
                    }
                });
            }
        });
    });
</script>
@endpush
