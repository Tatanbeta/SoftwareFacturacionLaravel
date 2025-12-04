<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaApiController extends Controller
{
    //listar clientes
    public function index(Request $request)
    {
        $query = Persona::query();

        // Búsqueda por cédula o nombre
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('cedula', 'like', '%' . $request->search . '%')
                  ->orWhere('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $personas = $query->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $personas
        ]);
    }

    //detalle cliente
    public function show($id)
    {
        $persona = Persona::with('invoices')->find($id);

        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $persona
        ]);
    }

    //crear cliente
    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|string|max:20|unique:personas,cedula',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:personas,email',
        ], [
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.unique' => 'Esta cédula ya está registrada',
            'nombre.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El formato del correo electrónico no es válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
        ]);

        try {
            $persona = Persona::create([
                'cedula' => $request->cedula,
                'nombre' => $request->nombre,
                'email' => $request->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cliente creado correctamente',
                'data' => $persona
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    // update cliente
    public function update(Request $request, $id)
    {
        $persona = Persona::find($id);

        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        $request->validate([
            'cedula' => 'required|string|max:20|unique:personas,cedula,' . $id,
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:personas,email,' . $id,
        ], [
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.unique' => 'Esta cédula ya está registrada',
            'nombre.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El formato del correo electrónico no es válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
        ]);

        try {
            $persona->update([
                'cedula' => $request->cedula,
                'nombre' => $request->nombre,
                'email' => $request->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cliente actualizado correctamente',
                'data' => $persona
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    // este seria el delete del cliente
    public function destroy($id)
    {
        $persona = Persona::find($id);

        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        // Verificar si tiene facturas asociadas
        if ($persona->invoices()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el cliente porque tiene facturas asociadas'
            ], 400);
        }

        $persona->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado correctamente'
        ]);
    }
}
