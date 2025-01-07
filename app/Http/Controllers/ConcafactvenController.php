<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ConcafactvenService;


class ConcafactvenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $request->only(['codnum','nroconc','descrip', 'porcentaje','tipoiva']);
            $concafactvenService = new ConcafactvenService();
            $response = $concafactvenService->index($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    // Conceptos impuesto 1
    public function conceptosFc(Request $request)
    {
        try {
            $data = $request->only(['nroconc','descrip', 'porcentaje','tipoiva']);
            $concafactvenService = new ConcafactvenService();
            $response = $concafactvenService->conceptosFc($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->only(['codnum', 'nroconc', 'descrip', 'porcentaje', 'tipoiva']);
            $concafactvenService = new ConcafactvenService();
            $response = $concafactvenService->store($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $codnum)
    {
        try {
            $data = $request->only(['codnum', 'nroconc', 'descrip', 'porcentaje', 'tipoiva']);
            $concafactvenService = new ConcafactvenService();
            $response = $concafactvenService->update($data, $codnum);
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
            $concafactvenService = new ConcafactvenService();
            $response = $concafactvenService->destroy($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
}
