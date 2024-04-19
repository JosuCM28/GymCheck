<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cliente;
use App\Models\Membresia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ClienteController extends Controller
{
    public function index(Request $request) {  

        $value = trim($request->get('busqueda'));    

        $clientes = Cliente::where('name', 'LIKE', '%'.$value.'%')->get();
    
        return view('dashboard', compact('clientes'));
    }

    public function create(Request $request)
    {
        return view('profile.partials.create-cliente', [
            'user' => $request->user(),
        ]);
    }
    
    public function store(Request $request) {
        $validate = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'address' => 'required',
            'phone_number' => 'required|max:12'
        ]);

        $cliente = Cliente::create($validate);

        $fechaInicio = Carbon::now();
        $tipoMembresia = $request->input('tipo');

        switch($tipoMembresia) {
            case 'Semanal':
                $fecha_vencimiento = $fechaInicio->addDays(7);
                break;
            case 'Mensual':
                $fecha_vencimiento = $fechaInicio->addDays(30);
                break;
            case 'Anual':
                $fecha_vencimiento = $fechaInicio->addDays(365);
                break;
        };

        $membresia = new Membresia();
        $membresia->tipo = $tipoMembresia;
        $membresia->fechaVencimiento = $fecha_vencimiento;
        $membresia->cliente_id = $cliente->id;
        $membresia->save();

        return redirect()->route('cliente.index');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('profile.partials.update-cliente', compact('cliente'));
    }

    public function update(Request $request, $id) 
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'address' => 'required',
            'phone_number' => 'required|max:12'
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());
    

        return redirect()->route('cliente.index');

    }

    public function show($id){
        $cliente = Cliente::findOrFail($id);
        return view('profile.partials.delete-cliente', compact('cliente'));
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('cliente.index');
    }
}
