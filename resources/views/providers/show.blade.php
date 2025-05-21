@extends('layouts.app')

@section('title', 'Detalles del Proveedor')

@section('content')
<section class="section">
    <div class="container">
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="{{ route('providers.index') }}">Proveedores</a></li>
                <li class="is-active"><a href="#" aria-current="page">Detalles</a></li>
            </ul>
        </nav>

        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box">
                    <div class="level is-mobile">
                        <div class="level-left">
                            <h2 class="title is-4 has-text-info">
                                <span class="icon">
                                    <i class="fas fa-truck"></i>
                                </span>
                                <span>{{ $provider->company }}</span>
                            </h2>
                        </div>
                        <div class="level-right">
                            <div class="buttons">
                                <a href="{{ route('providers.edit', $provider->id) }}" class="button is-primary">
                                    <span class="icon">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <span>Editar</span>
                                </a>
                                <a href="{{ route('providers.index') }}" class="button is-light">
                                    <span class="icon">
                                        <i class="fas fa-arrow-left"></i>
                                    </span>
                                    <span>Volver</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label">Compañía</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <p class="has-text-light">{{ $provider->company }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label">Representante</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <p class="has-text-light">{{ $provider->contact_name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label">Teléfono</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <p class="has-text-light">
                                            <a href="tel:{{ $provider->phone }}" class="has-text-link">
                                                <span class="icon">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                                <span>{{ $provider->phone }}</span>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label">Correo Electrónico</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <p class="has-text-light">
                                            <a href="mailto:{{ $provider->email }}" class="has-text-link">
                                                <span class="icon">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                                <span>{{ $provider->email }}</span>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($provider->address)
                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label">Dirección</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <p class="has-text-light">
                                            <span class="icon">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <span>{{ $provider->address }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($provider->order_website || $provider->billing_email || $provider->order_phone)
                        <hr>
                        <h4 class="title is-5 has-text-light">Información Adicional</h4>
                        @endif

                        @if($provider->order_website)
                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label">Sitio Web</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <p class="has-text-light">
                                            <a href="{{ $provider->order_website }}" target="_blank" class="has-text-link">
                                                <span class="icon">
                                                    <i class="fas fa-globe"></i>
                                                </span>
                                                <span>{{ $provider->order_website }}</span>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($provider->billing_email)
                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label">Correo Facturación</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <p class="has-text-light">
                                            <a href="mailto:{{ $provider->billing_email }}" class="has-text-link">
                                                <span class="icon">
                                                    <i class="fas fa-file-invoice-dollar"></i>
                                                </span>
                                                <span>{{ $provider->billing_email }}</span>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($provider->order_phone)
                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label">Teléfono Pedidos</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <p class="has-text-light">
                                            <a href="tel:{{ $provider->order_phone }}" class="has-text-link">
                                                <span class="icon">
                                                    <i class="fas fa-phone-volume"></i>
                                                </span>
                                                <span>{{ $provider->order_phone }}</span>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($provider->documents->count() > 0)
                        <hr>
                        <h4 class="title is-5 has-text-light">Documentos del Proveedor</h4>
                        <div class="table-container">
                            <table class="table is-fullwidth is-striped is-hoverable">
                                <thead>
                                    <tr>
                                        <th>Nombre del Documento</th>
                                        <th>Tipo</th>
                                        <th>Tamaño</th>
                                        <th class="has-text-centered">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($provider->documents as $document)
                                    <tr>
                                        <td>{{ $document->name }}</td>
                                        <td>
                                            <span class="tag is-info is-light">
                                                {{ strtoupper(pathinfo($document->file_path, PATHINFO_EXTENSION)) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                                        <td>
                                            <div class="buttons is-centered">
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
                        @endif
                    </div>
                    <hr>
                    <div class="level is-mobile">
                        <div class="level-left">
                            <div class="level-item">
                                <p class="has-text-light-light is-size-7">
                                    <span class="icon">
                                        <i class="fas fa-calendar-plus"></i>
                                    </span>
                                    <span>Creado el {{ $provider->created_at->format('d/m/Y H:i') }}</span>
                                    <span class="mx-2">•</span>
                                    <span class="icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>
                                    <span>Actualizado el {{ $provider->updated_at->format('d/m/Y H:i') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

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

                if (confirm('¿Estás seguro de que deseas eliminar este documento? Esta acción no se puede deshacer.')) {
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

                            // Eliminar la fila de la tabla
                            this.closest('tr').remove();

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
