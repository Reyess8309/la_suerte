<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Muestra una lista de todos los clientes.
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', ['clientes' => $clientes]);
    }

    /**
     * Muestra el formulario para crear un nuevo cliente.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Guarda el nuevo cliente en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos del formulario
        $validatedData = $request->validate([
            // Reglas de validación
            'documento_id' => 'required|string|max:20|unique:clientes',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'nullable|string|max:15',
        ]);

        // 2. Si la validación pasa, crea el cliente
        Cliente::create($validatedData);

        // 3. Redirige al usuario de vuelta a la lista de clientes
        return redirect()->route('clientes.index')
                         ->with('success', '¡Cliente registrado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        // (Pendiente)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        // (Pendiente)
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        // (Pendiente)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        // (Pendiente)
    }
}