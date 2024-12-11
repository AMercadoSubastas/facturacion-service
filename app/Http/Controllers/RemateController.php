<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RemateService;
use App\Http\Requests\RematesRequest;

class RemateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $request->only(['ncomp', 'direccion', 'observacion', 'codpais','codprov','codloc']);
            $bancoService = new RemateService();
            $response = $bancoService->index($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(RematesRequest $request)
    {
        try {
            $data = $request->only(['ncomp', 'direccion', 'observacion', 'codpais','codprov','codloc']);
            $bancoService = new RemateService();
            $response = $bancoService->store($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $ncomp)
    {
        try {
            $request->validate([
                'ncomp' => 'numeric',
            ]);
            $bancoService = new RemateService();

            $response = $bancoService->show($ncomp);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    public function obtenerLotes(Request $request, int $codrem)
    {
        try {
            $request->validate([
                'codrem' => 'numeric',
            ]);
            $bancoService = new RemateService();

            $response = $bancoService->obtenerLotes($codrem);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RematesRequest $request, int $codnum)
    {
        try {
            $data = $request->only(['ncomp', 'direccion', 'observacion', 'codpais','codprov','codloc']);
            $bancoService = new RemateService();
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
            $bancoService = new RemateService();
            $response = $bancoService->destroy($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
}
