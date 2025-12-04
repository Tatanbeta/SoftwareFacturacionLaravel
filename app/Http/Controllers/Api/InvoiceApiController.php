<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InvoiceApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['persona', 'usuario']);

        // Filtro de fechas
        if ($request->has('fecha_inicio')) {
            $query->where('issue_date', '>=', $request->fecha_inicio);
        }
        if ($request->has('fecha_fin')) {
            $query->where('issue_date', '<=', $request->fecha_fin);
        }

        // Filtro tipo de factura
        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('invoice_type', $request->tipo);
        }

        // Filtro  cliente (cedula o nombre)
        if ($request->has('cliente') && $request->cliente != '') {
            $query->whereHas('persona', function($q) use ($request) {
                $q->where('cedula', 'like', '%' . $request->cliente . '%')
                  ->orWhere('nombre', 'like', '%' . $request->cliente . '%');
            });
        }

        $invoices = $query->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }

    // detalle de una factura
    public function show($id)
    {
        $invoice = Invoice::with(['persona', 'usuario', 'detalles'])
            ->find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $invoice
        ]);
    }
    
    // crear factura
    public function store(Request $request)
    {
        $request->validate([
            'persona_id' => 'required|exists:personas,id',
            'user_id' => 'required|exists:usuarios,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'invoice_type' => 'required|in:contado,credito',
            'items' => 'required|array|min:1',
            'items.*.item_code' => 'required|string|max:50',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.applies_tax' => 'required|boolean',
        ], [
            'persona_id.required' => 'El cliente es obligatorio',
            'persona_id.exists' => 'El cliente seleccionado no existe',
            'user_id.required' => 'El usuario es obligatorio',
            'user_id.exists' => 'El usuario seleccionado no existe',
            'issue_date.required' => 'La fecha de emisión es obligatoria',
            'due_date.required' => 'La fecha de vencimiento es obligatoria',
            'due_date.after_or_equal' => 'La fecha de vencimiento debe ser igual o posterior a la fecha de emisión',
            'invoice_type.required' => 'El tipo de factura es obligatorio',
            'invoice_type.in' => 'El tipo de factura debe ser contado o crédito',
            'items.required' => 'Debe agregar al menos un ítem a la factura',
            'items.min' => 'Debe agregar al menos un ítem a la factura',
            'items.*.item_code.required' => 'El código del ítem es obligatorio',
            'items.*.item_name.required' => 'El nombre del ítem es obligatorio',
            'items.*.unit_price.required' => 'El valor unitario es obligatorio',
            'items.*.unit_price.min' => 'El valor unitario debe ser mayor o igual a 0',
            'items.*.quantity.required' => 'La cantidad es obligatoria',
            'items.*.quantity.min' => 'La cantidad debe ser mayor a 0',
            'items.*.applies_tax.required' => 'Debe indicar si aplica IVA',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $tax_total = 0;

            foreach ($request->items as $item) {
                $lineSub = $item['unit_price'] * $item['quantity'];
                $lineTax = $item['applies_tax'] ? $lineSub * 0.19 : 0;

                $subtotal += $lineSub;
                $tax_total += $lineTax;
            }

            $invoice = Invoice::create([
                'persona_id' => $request->persona_id,
                'user_id' => $request->user_id,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'invoice_type' => $request->invoice_type,
                'subtotal' => $subtotal,
                'tax_total' => $tax_total,
                'total' => $subtotal + $tax_total
            ]);

            foreach ($request->items as $item) {
                // Recalcular subtotal de línea
                $lineSubtotal = $item['unit_price'] * $item['quantity'];
                $lineTaxAmount = $item['applies_tax'] ? $lineSubtotal * 0.19 : 0;
                
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'item_code' => $item['item_code'],
                    'item_name' => $item['item_name'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'applies_tax' => $item['applies_tax'],
                    'tax_amount' => $lineTaxAmount,
                    'subtotal' => $lineSubtotal,
                    'total' => $lineSubtotal + $lineTaxAmount
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Factura creada correctamente',
                'data' => $invoice
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al crear factura: ' . $e->getMessage()
            ], 500);
        }
    }

    //eliminar factura
    public function destroy($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada'
            ], 404);
        }

        $invoice->delete();

        return response()->json([
            'success' => true,
            'message' => 'Factura eliminada'
        ]);
    }
}
