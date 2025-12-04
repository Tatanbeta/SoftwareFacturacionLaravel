<?php
namespace App\Http\Controllers;
use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    // Listar clientes
    public function index()
    {
        $personas = Persona::orderBy('id', 'desc')->paginate(10);

        return view('personas.index', compact('personas'));
    }

    // Formulario para crear
    public function create()
    {
        return view('personas.create');
    }

    // Guardar cliente
    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|unique:personas',
            'nombre' => 'required',
            'email'  => 'required|email'
        ]);

        Persona::create($request->all());

        return redirect()->route('personas.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    // Editar cliente
    public function edit(Persona $persona)
    {
        return view('personas.edit', compact('persona'));
    }

    // Actualizar cliente
    public function update(Request $request, Persona $persona)
    {
        $request->validate([
            'cedula' => 'required|unique:personas,cedula,' . $persona->id,
            'nombre' => 'required',
            'email'  => 'required|email'
        ]);

        $persona->update($request->all());

        return redirect()->route('personas.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    // Eliminar cliente
    public function destroy(Persona $persona)
    {
        $persona->delete();

        return redirect()->route('personas.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
