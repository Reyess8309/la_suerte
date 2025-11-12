<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Muestra una lista de todos los clientes. (READ)
     */
    public function index()
    {
        // 1. Pide al Modelo Cliente todos los registros
        $clientes = Cliente::all();

        // 2. Devuelve la vista 'clientes.index' y le pasa los datos
        return view('clientes.index', ['clientes' => $clientes]);
    }

    /**
     * Muestra el formulario para crear un nuevo cliente. (CREATE)
     */
    public function create()
    //
    {
        // Solo muestra el formulario (la vista)
        return view('clientes.create');
    }

    /**
     * Guarda el nuevo cliente en la base de datos. (CREATE)
     * (Este es tu siguiente paso para programar)
     */
    public function store(Request $request)
    {
        // 1. Validar los datos (¡próximo paso!)
        // $request->validate([ ... ]);

        // 2. Crear el cliente (¡próximo paso!)
        // Cliente::create($request->all());

        // 3. Redirigir a la lista
        // return redirect()->route('clientes.index');

        // Por ahora, solo muestra "guardado"
        return "¡Cliente guardado! (Lógica 'store' pendiente)";
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