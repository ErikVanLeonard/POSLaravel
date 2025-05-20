<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'rfc' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[A-Z&Ñ]{3,4}\d{6}[A-V0-9]{3}$/i',
            ],
            'address' => 'nullable|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        try {
            DB::beginTransaction();
            
            Client::create($validated);
            
            DB::commit();
            
            return redirect()
                ->route('clients.index')
                ->with('success', 'Cliente creado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'rfc' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[A-Z&Ñ]{3,4}\d{6}[A-V0-9]{3}$/i',
                Rule::unique('clients')->ignore($client->id),
            ],
            'address' => 'nullable|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('clients')->ignore($client->id),
            ],
        ]);

        try {
            DB::beginTransaction();
            
            $client->update($validated);
            
            DB::commit();
            
            return redirect()
                ->route('clients.show', $client)
                ->with('success', 'Cliente actualizado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        try {
            $client->delete();
            return redirect()
                ->route('clients.index')
                ->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el cliente: ' . $e->getMessage());
        }
    }
}
