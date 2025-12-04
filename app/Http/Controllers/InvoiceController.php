<?php
namespace App\Http\Controllers;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Persona;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    //Lista de facturas
    public function index(Request $request)
    {
        $query = Invoice::with(['persona', 'usuario']);
        //filtros 
        if ($request->filled('fecha_inicio')) {
            $query->where('issue_date', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->where('issue_date', '<=', $request->fecha_fin);
        }
        if ($request->filled('tipo')) {
            $query->where('invoice_type', $request->tipo);
        }
        if ($request->filled('cliente')) {
            $buscar = $request->cliente;

            $query->whereHas('persona', function($q) use ($buscar) {
                $q->where('cedula', 'LIKE', "%$buscar%")
                ->orWhere('nombre', 'LIKE', "%$buscar%");
            });
        }

        //Paginacion
        $invoices = $query->orderBy('id', 'desc')
                        ->paginate(10)
                        ->appends($request->all());

        
        return view('invoices.index', [
            'invoices' => $invoices,
            'request' => $request->all()
        ]);
    }

    //Crear factura
    public function create()
    {
        $personas = Persona::orderBy('nombre', 'asc')->get();
        $usuarios = Usuario::all();

        return view('invoices.create', compact('personas', 'usuarios'));
    }

    //Guardar factura
    public function store(Request $request)
    {
        $request->validate([
            'persona_id' => 'required',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'invoice_type' => 'required',
            'item_code.*' => 'required',
            'item_name.*' => 'required',
            'unit_price.*' => 'required|numeric',
            'quantity.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {

            // usuario en sesión
            $userId = auth()->id() ?? 1;

            // calcular totales
            $subtotal = 0;
            $tax_total = 0;

            for ($i = 0; $i < count($request->item_code); $i++) {
                $line_sub = $request->unit_price[$i] * $request->quantity[$i];
                $line_tax = !empty($request->applies_tax[$i]) ? ($line_sub * 0.19) : 0;

                $subtotal += $line_sub;
                $tax_total += $line_tax;
            }

            $total = $subtotal + $tax_total;

            // crear factura
            $invoice = Invoice::create([
                'persona_id'   => $request->persona_id,
                'user_id'      => $userId,
                'issue_date'   => $request->issue_date,
                'due_date'     => $request->due_date,
                'invoice_type' => $request->invoice_type,
                'subtotal'     => $subtotal,
                'tax_total'    => $tax_total,
                'total'        => $total,
            ]);

            // crear items
            for ($i = 0; $i < count($request->item_code); $i++) {
                $line_sub = $request->unit_price[$i] * $request->quantity[$i];
                $line_tax = !empty($request->applies_tax[$i]) ? ($line_sub * 0.19) : 0;

                InvoiceDetail::create([
                    'invoice_id'  => $invoice->id,
                    'item_code'   => $request->item_code[$i],
                    'item_name'   => $request->item_name[$i],
                    'unit_price'  => $request->unit_price[$i],
                    'quantity'    => $request->quantity[$i],
                    'applies_tax' => !empty($request->applies_tax[$i]),
                    'tax_amount'  => $line_tax,
                    'subtotal'    => $line_sub,
                    'total'       => $line_sub + $line_tax,
                ]);
            }

            DB::commit();

            return redirect()->route('invoices.index')
                ->with('success', 'Factura creada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    //Ver factura
    public function show($id)
    {
        $invoice = Invoice::with('persona', 'usuario', 'detalles')
            ->findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }
    //Generar pdf
    public function pdf($id)
{
    $invoice = Invoice::with(['persona', 'usuario', 'detalles'])->findOrFail($id);

    $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
              ->setPaper('letter')
              ->setOption('isHtml5ParserEnabled', true)
              ->setOption('isPhpEnabled', true);

    $nombreArchivo = "Factura_{$invoice->id}.pdf";

    return $pdf->download($nombreArchivo);
}
}
