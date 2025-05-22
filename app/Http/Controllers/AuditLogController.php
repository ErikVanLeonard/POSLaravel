<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // Obtener los tipos de modelos disponibles primero
        $modelTypes = [];
        $auditLogs = AuditLog::select('auditable_type')
            ->distinct()
            ->whereNotNull('auditable_type')
            ->get();
            
        foreach ($auditLogs as $log) {
            $parts = explode('\\', $log->auditable_type);
            $modelName = end($parts);
            if (!in_array($modelName, $modelTypes)) {
                $modelTypes[] = $modelName;
            }
        }
        
        sort($modelTypes); // Ordenar alfabéticamente

        // Construir la consulta base
        $query = AuditLog::with(['user', 'auditable']);

        // Filtrar por tipo de modelo
        if ($request->filled('model_type')) {
            $modelType = 'App\\Models\\' . $request->model_type;
            $query->where('auditable_type', 'LIKE', '%' . $request->model_type);
        }

        // Filtrar por tipo de evento
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filtrar por rango de fechas
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Ordenar y paginar
        $logs = $query->latest()->paginate(15)->withQueryString();

        return view('audit-logs.index', compact('logs', 'modelTypes'));
    }

    public function show(AuditLog $auditLog)
    {
        $auditLog->load(['user', 'auditable']);
        return view('audit-logs.show', compact('auditLog'));
    }
}
