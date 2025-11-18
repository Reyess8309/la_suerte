<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;


class UsuarioController extends Controller
{
    /**
     * Muestra una lista de todos los empleados.
     */
    public function index()
    {
        $usuarios = Usuario::all();
        return view('usuarios.index', ['usuarios' => $usuarios]);
    }

    /**
     * Muestra el formulario para crear un nuevo empleado.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Guarda el nuevo empleado en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos del formulario
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|string|in:admin,employee',
        ]);

        // 2. Si la validación pasa, crea el usuario.
        Usuario::create($validatedData);

        // 3. Redirige al usuario de vuelta a la lista de empleados
        return redirect()->route('usuarios.index')
                         ->with('success', '¡Empleado registrado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        // (Pendiente)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        // (Pendiente)
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        // (Pendiente)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        // (Pendiente)
    }
}