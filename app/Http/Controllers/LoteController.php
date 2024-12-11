<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoteRequest;
use App\Services\LoteService;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $request->only(['codrem', 'codintlote', 'estado', 'descripcion','preciobase','preciofinal', 'comiscobr', 'comispag','fecalta']);
            $bancoService = new LoteService();
            $response = $bancoService->index($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(LoteRequest $request)
    {
        try {
            $data = $request->only(['codrem', 'codintlote', 'estado', 'descripcion','preciobase','preciofinal', 'comiscobr', 'comispag','fecalta']);
            $bancoService = new LoteService();
            $response = $bancoService->store($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $codrem, int $codintlote)
    {
        try {
            $request->validate([
                'codrem' => 'numeric',
                'codintlote' => 'numeric',
            ]);
            $bancoService = new LoteService();

            $response = $bancoService->show($codrem, $codintlote);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LoteRequest $request, int $codnum)
    {
        try {
            $data = $request->only(['codrem', 'codintlote', 'estado', 'descripcion','preciobase','preciofinal', 'comiscobr', 'comispag','fecalta']);
            $bancoService = new LoteService();
            $response = $bancoService->update($data, $codnum);
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
            $bancoService = new LoteService();
            $response = $bancoService->destroy($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
}
