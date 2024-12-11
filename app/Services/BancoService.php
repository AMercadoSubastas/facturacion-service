<?php

namespace App\Services;

use App\Models\Banco;
use Exception;
use PhpParser\NodeVisitor\FirstFindingVisitor;

class BancoService
{
    public function index(array $data = [])
    {
        $per_page = $data['per_page'] ?? 1;
        $sort_by = $data['sort_by'] ?? 'codnum';
        $sort_order = $data['sort_order'] ?? 'asc';
        $query = Banco::query();

        // Aplicar orden
        $query->with('codrem')->orderBy($sort_by, $sort_order);

        return $query->paginate($per_page);
    }

    public function show(int $codnum)
    {

        $data = Banco::where('codnum', $codnum)->first();
        if (!$data)
            throw new \Exception("No existe el Banco");
        
        return $data;
    }

    public function store(array $data = [])
    {

        
        $banco = Banco::where('codbanco', $data['codbanco'])->first();
        if ($banco)
            throw new \Exception("Ya existe el banco");

        
        return Banco::create($data);
    }


    public function update(array $data = [], int $codnum)
    {
        $banco = Banco::where('codnum', $codnum);
        $query = $banco->first();
        if (!$query) {
            throw new \Exception("No existe el Banco");
        }
        return $banco->update($data);
    }


    public function destroy(int $codnum)
    {
        $banco = Banco::where('codnum', $codnum);
        $query = $banco->first();
        if (!$query) {
            throw new \Exception("No existe el Banco");
        }
        return $banco->delete();

    }
}
