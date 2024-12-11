<?php

namespace App\Http\Controllers;
use App\Services\CabfacService;
use App\Http\Requests\CabfacRequest;


use Illuminate\Http\Request;

class CabfacController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $request->only(['tcomp','serie','ncomp', 'cliente', 'direc', 'dnro', 'codpost', 'codpais', 'codprov', 'codloc', 'telef', 'codrem', 'estado', 'emitido', 'moneda', 'totbruto', 'totneto', 'totimp', 'tipoiva', 'nrengs', 'usuario', 'concepto', 'CAE', 'CAEFchVto']);
            $cabfacService = new CabfacService();
            $response = $cabfacService->index($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CabfacRequest $request)
    {
        try {
            $data = $request->only(['tcomp','serie','ncomp', 'cliente', 'direc', 'dnro', 'codpost', 'codpais', 'codprov', 'codloc', 'telef', 'codrem', 'estado', 'emitido', 'moneda', 'totbruto', 'totneto', 'totimp', 'tipoiva', 'nrengs', 'usuario', 'concepto', 'CAE', 'CAEFchVto']);
            $cabfacService = new CabfacService();
            $response = $cabfacService->store($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $codnum)
    {
        try {
            $request->validate([
                'codnum' => 'numeric',
            ]);
            $cabfacService = new CabfacService();

            $response = $cabfacService->show($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CabfacRequest $request, int $codnum)
    {
        try {
            $data = $request->only(['codbanco', 'nombre', 'codpais', 'activo']);
            $cabfacService = new CabfacService();
            $response = $cabfacService->update($data, $codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $codnum)
    {
        try {
            $cabfacService = new CabfacService();
            $response = $cabfacService->destroy($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    
}
