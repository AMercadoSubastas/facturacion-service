<?php

namespace App\Services;

use App\Models\Lotes;
use Exception;
use PhpParser\NodeVisitor\FirstFindingVisitor;

class LoteService
{
    public function index(array $data = [])
    {
        $per_page = $data['per_page'] ?? 1;
        $sort_by = $data['sort_by'] ?? 'codnum';
        $sort_order = $data['sort_order'] ?? 'desc';
        $query = Lotes::query();

        // Aplicar orden
        $query->orderBy($sort_by, $sort_order);

        return $query->paginate($per_page);
    }

    public function show(int $codrem, int $codintnum)
    {

        $data = Lotes::where('codrem', $codrem)->where('codintnum', $codintnum)->get();
        if (!$data)
            throw new \Exception("No existe el Lote");
        
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

        
        $banco = Lotes::where('codnum', $data['codnum'])->first();
        if ($banco)
            throw new \Exception("Ya existe el Lote");

        
        return Lotes::create($data);
    }


    public function update(array $data = [], int $codnum)
    {
        $banco = Lotes::where('codnum', $codnum);
        $query = $banco->first();
        if (!$query) {
            throw new \Exception("No existe el Lote");
        }
        return $banco->update($data);
    }


    public function destroy(int $codnum)
    {
        $banco = Lotes::where('codnum', $codnum);
        $query = $banco->first();
        if (!$query) {
            throw new \Exception("No existe el Lote");
        }
        return $banco->delete();

    }
}
