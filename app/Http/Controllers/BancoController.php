<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BancoService;
use App\Http\Requests\BancoRequest;

class BancoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $request->only(['codbanco','nombre','codpais', 'activo']);
            $bancoService = new BancoService();
            $response = $bancoService->index($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(BancoRequest $request)
    {
        try {
            $data = $request->only(['codbanco', 'nombre', 'codpais']);
            $bancoService = new BancoService();
            $response = $bancoService->store($data);
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
            $bancoService = new BancoService();

            $response = $bancoService->show($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BancoRequest $request, int $codnum)
    {
        try {
            $data = $request->only(['codbanco', 'nombre', 'codpais', 'activo']);
            $bancoService = new BancoService();
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
            $bancoService = new BancoService();
            $response = $bancoService->destroy($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
}
