@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-10">
                <div class="box">
                    <div class="level is-mobile">
                        <div class="level-left">
                            <div class="level-item">
                                <h1 class="title is-4">
                                    <span class="icon-text">
                                        <span class="icon">
                                            <i class="fas fa-clipboard-list"></i>
                                        </span>
                                        <span>Detalles de Auditoría #{{ $auditLog->id }}</span>
                                    </span>
                                </h1>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <a href="{{ route('audit-logs.index') }}" class="button is-light">
                                    <span class="icon">
                                        <i class="fas fa-arrow-left"></i>
                                    </span>
                                    <span>Volver</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="columns">
                        <div class="column is-6">
                            <h2 class="title is-5 mb-4">
                                <span class="icon-text">
                                    <span class="icon">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                    <span>Información General</span>
                                </span>
                            </h2>

                            <div class="content">
                                <table class="table is-fullwidth is-striped">
                                    <tbody>
                                        <tr>
                                            <th class="has-text-grey" width="40%">ID de Registro</th>
                                            <td>#{{ $auditLog->id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="has-text-grey">Usuario</th>
                                            <td>{{ $auditLog->user ? $auditLog->user->name : 'Sistema' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="has-text-grey">Módulo</th>
                                            <td>{{ class_basename($auditLog->auditable_type) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="has-text-grey">ID del Recurso</th>
                                            <td>{{ $auditLog->auditable_id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="has-text-grey">Acción</th>
                                            <td>
                                                @php
                                                    $badgeClass = [
                                                        'created' => 'is-success',
                                                        'updated' => 'is-primary',
                                                        'deleted' => 'is-danger',
                                                    ][$auditLog->event] ?? 'is-info';
                                                @endphp
                                                <span class="tag {{ $badgeClass }} is-medium">
                                                    {{ ucfirst($auditLog->event) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="has-text-grey">Fecha y Hora</th>
                                            <td>{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="has-text-grey">Dirección IP</th>
                                            <td>
                                                <span class="tag is-light is-family-monospace">
                                                    {{ $auditLog->ip_address }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="has-text-grey">User Agent</th>
                                            <td>
                                                <div class="content is-small">
                                                    <code>{{ $auditLog->user_agent }}</code>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="column is-6">
                            <h2 class="title is-5 mb-4">
                                <span class="icon-text">
                                    <span class="icon">
                                        <i class="fas fa-database"></i>
                                    </span>
                                    <span>Datos del Recurso</span>
                                </span>
                            </h2>
                            
                            @if($auditLog->auditable)
                                <div class="table-container">
                                    <table class="table is-fullwidth is-striped">
                                        <tbody>
                                            @foreach($auditLog->auditable->toArray() as $key => $value)
                                                <tr>
                                                    <th class="has-text-grey" width="40%">
                                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                                    </th>
                                                    <td>
                                                        @if(is_array($value))
                                                            <pre class="is-size-7 has-background-light p-2">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                        @elseif($value === null)
                                                            <span class="has-text-grey">Nulo</span>
                                                        @elseif($value === '')
                                                            <span class="has-text-grey">Vacío</span>
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="notification is-warning">
                                    <div class="content">
                                        <p class="has-text-weight-semibold">
                                            <span class="icon-text">
                                                <span class="icon">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </span>
                                                <span>Recurso no encontrado</span>
                                            </span>
                                        </p>
                                        <p>Este recurso ya no existe en el sistema.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($auditLog->event === 'updated' && $auditLog->old_values)
                        <div class="mt-6">
                            <h2 class="title is-5 mb-4">
                                <span class="icon-text">
                                    <span class="icon">
                                        <i class="fas fa-exchange-alt"></i>
                                    </span>
                                    <span>Cambios Realizados</span>
                                </span>
                            </h2>
                            
                            <div class="table-container">
                                <table class="table is-fullwidth is-striped is-hoverable">
                                    <thead>
                                        <tr>
                                            <th>Campo</th>
                                            <th>Valor Anterior</th>
                                            <th>Nuevo Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($auditLog->old_values as $field => $oldValue)
                                            @php
                                                $newValue = $auditLog->new_values[$field] ?? null;
                                                $hasChanged = $oldValue != $newValue;
                                            @endphp
                                            @if($hasChanged)
                                                <tr>
                                                    <td class="has-text-weight-semibold">
                                                        {{ ucfirst(str_replace('_', ' ', $field)) }}
                                                    </td>
                                                    <td>
                                                        @if(is_array($oldValue))
                                                            <pre class="is-size-7 has-background-light p-2">{{ json_encode($oldValue, JSON_PRETTY_PRINT) }}</pre>
                                                        @else
                                                            <span class="{{ $oldValue === null || $oldValue === '' ? 'has-text-grey' : '' }}">
                                                                {{ $oldValue ?? 'Nulo' }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(is_array($newValue))
                                                            <pre class="is-size-7 has-background-light p-2">{{ json_encode($newValue, JSON_PRETTY_PRINT) }}</pre>
                                                        @else
                                                            <span class="has-text-weight-semibold {{ $newValue === null || $newValue === '' ? 'has-text-grey' : '' }}">
                                                                {{ $newValue ?? 'Nulo' }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
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

    <style>
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            background-color: #f5f5f5;
            padding: 1rem;
            border-radius: 4px;
            font-size: 0.85em;
            font-family: monospace;
        }

        .table th {
            background-color: #f5f5f5;
        }

        .tag {
            font-size: 0.9em;
        }

        .content pre {
            background-color: transparent;
            padding: 0;
            margin: 0;
        }

        .notification {
            margin-top: 1rem;
        }
    </style>
</section>
@endsection
