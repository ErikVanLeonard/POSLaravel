@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-12">
                <div class="box">
                <h1 class="title is-4 has-text-centered">
                    <span class="icon-text">
                        <span class="icon">
                            <i class="fas fa-clipboard-list"></i>
                        </span>
                        <span>Auditoría del Sistema</span>
                    </span>
                </h1>

                <form method="GET" action="{{ route('audit-logs.index') }}" class="mb-5">
                    <div class="field is-horizontal">
                        <div class="field-body">
                            <div class="field">
                                <label class="label">Módulo</label>
                                <div class="control is-expanded">
                                    <div class="select is-fullwidth">
                                        <select name="model_type" id="model_type">
                                            <option value="">Todos los módulos</option>
                                            @foreach($modelTypes as $model)
                                                <option value="{{ $model }}" {{ request('model_type') == $model ? 'selected' : '' }}>
                                                    {{ $model }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="field">
                                <label class="label">Acción</label>
                                <div class="control is-expanded">
                                    <div class="select is-fullwidth">
                                        <select name="event" id="event">
                                            <option value="">Todas las acciones</option>
                                            <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Creado</option>
                                            <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Actualizado</option>
                                            <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Eliminado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="field">
                                <label class="label">Fecha Inicio</label>
                                <div class="control">
                                    <input type="date" name="start_date" id="start_date" class="input" 
                                           value="{{ request('start_date') }}">
                                </div>
                            </div>
                            
                            <div class="field">
                                <label class="label">Fecha Fin</label>
                                <div class="control">
                                    <input type="date" name="end_date" id="end_date" class="input" 
                                           value="{{ request('end_date') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field is-grouped is-grouped-right">
                        <div class="control">
                            <button type="submit" class="button is-primary">
                                <span class="icon">
                                    <i class="fas fa-filter"></i>
                                </span>
                                <span>Filtrar</span>
                            </button>
                        </div>
                        <div class="control">
                            <a href="{{ route('audit-logs.index') }}" class="button is-light">
                                <span class="icon">
                                    <i class="fas fa-undo"></i>
                                </span>
                                <span>Limpiar</span>
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-container">
                    <table class="table is-fullwidth is-striped is-hoverable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Módulo</th>
                                <th>Acción</th>
                                <th>Fecha</th>
                                <th class="has-text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>#{{ $log->id }}</td>
                                    <td>{{ $log->user ? $log->user->name : 'Sistema' }}</td>
                                    <td>{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'created' => 'is-success',
                                                'updated' => 'is-primary',
                                                'deleted' => 'is-danger',
                                            ][$log->event] ?? 'is-info';
                                        @endphp
                                        <span class="tag {{ $badgeClass }} is-light">
                                            {{ ucfirst($log->event) }}
                                        </span>
                                    </td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td class="has-text-right">
                                        <a href="{{ route('audit-logs.show', $log) }}" class="button is-small is-info" 
                                           title="Ver detalles">
                                            <span class="icon">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="has-text-centered">
                                        <div class="notification is-light">
                                            No se encontraron registros de auditoría.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                {{ $logs->withQueryString()->links('vendor.pagination.bulma') }}
            </div>
        </div>
    </div>
</div>
@endsection
