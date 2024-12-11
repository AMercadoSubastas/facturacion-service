<?php

namespace App\Services;

use App\Models\Remates;
use App\Models\Lotes;
use Exception;
use PhpParser\NodeVisitor\FirstFindingVisitor;

class RemateService
{
    public function index(array $data = [])
    {
        $per_page = $data['per_page'] ?? 1;
        $sort_by = $data['sort_by'] ?? 'codnum';
        $sort_order = $data['sort_order'] ?? 'desc';
        $query = Remates::query();

        // Aplicar orden
        $query->with('codpais','codprov','codloc','codcli','tipoind')->orderBy($sort_by, $sort_order);

        return $query->paginate($per_page);
    }

    public function show(int $ncomp)
    {

        $data = Remates::where('ncomp', $ncomp)->with('codpais','codprov','codloc','codcli','tipoind')->first();
        if (!$data)
            throw new \Exception("No existe la Subasta");
        
        return $data;
    }

    public function obtenerLotes(int $codrem)
    {

        $data = Lotes::where('codrem', $codrem)->get();
        if (!$data)
            throw new \Exception("No existe el Lote");
        
        return $data;
    }

    public function store(array $data = [])
    {

        
        $banco = Remates::where('ncomp', $data['ncomp'])->first();
        if ($banco)
            throw new \Exception("Ya existe la Subasta");

        
        return Remates::create($data);
    }


    public function update(array $data = [], int $codnum)
    {
        $banco = Remates::where('codnum', $codnum);
        $query = $banco->first();
        if (!$query) {
            throw new \Exception("No existe la Subasta");
        }
        return $banco->update($data);
    }


    public function destroy(int $codnum)
    {
        $banco = Remates::where('codnum', $codnum);
        $query = $banco->first();
        if (!$query) {
            throw new \Exception("No existe la Subasta");
        }
        return $banco->delete();

    }
}
