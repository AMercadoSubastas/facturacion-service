<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CabreciboService;
use App\Http\Requests\CabreciboRequest;

class CabreciboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $request->only(['tcomp','serie','ncomp', 'cliente', 'direc', 'dnro', 'codpost', 'codpais', 'codprov', 'codloc', 'telef', 'codrem', 'estado', 'emitido', 'moneda', 'totbruto', 'totneto', 'totimp', 'tipoiva', 'nrengs', 'usuario', 'concepto', 'CAE', 'CAEFchVto']);
            $cabreciboService = new CabreciboService();
            $response = $cabreciboService->index($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CabreciboRequest $request)
    {
        try {
            $data = $request->only(['tcomp','serie','ncomp', 'cliente', 'direc', 'dnro', 'codpost', 'codpais', 'codprov', 'codloc', 'telef', 'codrem', 'estado', 'emitido', 'moneda', 'totbruto', 'totneto', 'totimp', 'tipoiva', 'nrengs', 'usuario', 'concepto', 'CAE', 'CAEFchVto']);
            $cabreciboService = new CabreciboService();
            $response = $cabreciboService->store($data);
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
            $cabreciboService = new CabreciboService();

            $response = $cabreciboService->show($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CabreciboRequest $request, int $codnum)
    {
        try {
            $data = $request->only(['codbanco', 'nombre', 'codpais', 'activo']);
            $cabreciboService = new CabreciboService();
            $response = $cabreciboService->update($data, $codnum);
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
            $cabreciboService = new CabreciboService();
            $response = $cabreciboService->destroy($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
}
