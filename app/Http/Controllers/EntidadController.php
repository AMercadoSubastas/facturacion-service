<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EntidadService;
use App\Http\Requests\EntidadRequest;

class EntidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $request->only([
                'codnum',
                'razsoc',
                'calle',
                'numero',
                'pisodto',
                'codpais',
                'codprov',
                'codloc',
                'codpost',
                'telcelu',
                'tipoent',
                'tipoiva',
                'cuit',
                'calif',
                'fecalta',
                'contacto',
                'mailcont',
                'tipoind'
            ]);
            $entidadService = new EntidadService();
            $response = $entidadService->index($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(EntidadRequest $request)
    {
        try {
            $data = $request->only([
                'razsoc',
                'calle',
                'numero',
                'pisodto',
                'codpais',
                'codprov',
                'codloc',
                'codpost',
                'telcelu',
                'tipoent',
                'tipoiva',
                'cuit',
                'calif',
                'fecalta',
                'contacto',
                'mailcont',
                'tipoind'
        ]);
            $entidadService = new EntidadService();
            $response = $entidadService->store($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $cuit)
    {
        try {
            $entidadService = new EntidadService();

            $response = $entidadService->show($cuit);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EntidadRequest $request, int $codnum)
    {
        try {
            $data = $request->only([
                'razsoc',
                'calle',
                'numero',
                'pisodto',
                'codpais',
                'codprov',
                'codloc',
                'codpost',
                'telcelu',
                'tipoent',
                'tipoiva',
                'cuit',
                'calif',
                'fecalta',
                'contacto',
                'mailcont',
                'tipoind'
            ]);
            $entidadService = new EntidadService();
            $response = $entidadService->update($data, $codnum);
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
            $entidadService = new EntidadService();
            $response = $entidadService->destroy($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
}
