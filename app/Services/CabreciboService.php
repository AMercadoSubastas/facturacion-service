<?php

namespace App\Services;

use App\Models\Cabrecibo;
use Exception;
use PhpParser\NodeVisitor\FirstFindingVisitor;

class CabreciboService
{
    public function index(array $data = [])
    {
        $per_page = $data['per_page'] ?? 1;
        $sort_by = $data['sort_by'] ?? 'codnum';
        $sort_order = $data['sort_order'] ?? 'asc';
        $query = Cabrecibo::query();

        // Aplicar orden
        $query->orderBy($sort_by, $sort_order);

        return $query->paginate($per_page);
    }

    public function show(int $codnum)
    {

        $cabfac = Cabrecibo::where('codnum', $codnum)->first();
        if (!$cabfac)
            throw new \Exception("No existe la factura");
        
        return $cabfac;
    }

    public function store(array $data = [])
    {

        $cabfac = Cabrecibo::where('ncomp', $data['ncomp'])->first();
        if ($cabfac)
            throw new \Exception("Ya existe el banco");

        
        return Cabrecibo::create($data);
    }


    public function update(array $data = [], int $codnum)
    {
        $cabfac = Cabrecibo::where('codnum', $codnum);
        $query = $cabfac->first();
        if (!$query) {
            throw new \Exception("No existe el factura");
        }
        return $cabfac->update($data);
    }


    public function destroy(int $codnum)
    {
        $cabfac = Cabrecibo::where('codnum', $codnum);
        $query = $cabfac->first();
        if (!$query) {
            throw new \Exception("No existe el factura");
        }
        return $cabfac->delete();

    }
}
