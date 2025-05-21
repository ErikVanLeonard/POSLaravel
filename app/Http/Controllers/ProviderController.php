<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\ProviderDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $providers = Provider::withTrashed()->latest()->get();
        return view('providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('providers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:providers,email',
            'address' => 'nullable|string',
            'order_website' => 'nullable|url',
            'billing_email' => 'nullable|email',
            'order_phone' => 'nullable|string|max:20',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $provider = Provider::create($request->except('documents'));

        // Handle file uploads
        if ($request->hasFile('documents')) {
            $this->uploadDocuments($request->file('documents'), $provider);
        }

        return redirect()->route('providers.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        $provider->load('documents');
        return view('providers.show', compact('provider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Provider $provider)
    {
        $provider->load('documents');
        return view('providers.edit', compact('provider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:providers,email,' . $provider->id,
            'address' => 'nullable|string',
            'order_website' => 'nullable|url',
            'billing_email' => 'nullable|email',
            'order_phone' => 'nullable|string|max:20',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $provider->update($request->except('documents'));

        // Handle file uploads
        if ($request->hasFile('documents')) {
            $this->uploadDocuments($request->file('documents'), $provider);
        }

        return redirect()->route('providers.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {
        $provider->delete();
        return redirect()->route('providers.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $provider = Provider::withTrashed()->findOrFail($id);
        $provider->restore();
        return redirect()->route('providers.index')
            ->with('success', 'Proveedor restaurado exitosamente.');
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete($id)
    {
        $provider = Provider::withTrashed()->findOrFail($id);
        
        // Delete all related documents
        foreach ($provider->documents as $document) {
            Storage::delete('public/' . $document->file_path);
            $document->delete();
        }
        
        $provider->forceDelete();
        
        return redirect()->route('providers.index')
            ->with('success', 'Proveedor eliminado permanentemente.');
    }

    /**
     * Delete a document.
     */
    public function deleteDocument($id)
    {
        $document = ProviderDocument::findOrFail($id);
        Storage::delete('public/' . $document->file_path);
        $document->delete();
        
        return response()->json(['success' => 'Documento eliminado exitosamente.']);
    }

    /**
     * Handle file uploads for provider documents.
     */
    /**
     * Download a provider document.
     */
    public function downloadDocument($id)
    {
        $document = ProviderDocument::findOrFail($id);
        $filePath = storage_path('app/public/' . $document->file_path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'El archivo no existe.');
        }
        
        return response()->download($filePath, $document->name);
    }

    /**
     * Handle file uploads for provider documents.
     */
    private function uploadDocuments($files, $provider)
    {
        foreach ($files as $file) {
            if ($file->isValid()) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = 'documents/' . time() . '_' . Str::random(10) . '.' . $extension;
                
                // Store the file in the storage/app/public/documents directory
                $path = $file->storeAs('public/documents', $fileName);
                
                // Create a record in the database
                $provider->documents()->create([
                    'name' => $originalName,
                    'file_path' => 'documents/' . $fileName,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }
    }
}
