<?php

namespace App\Services;

use App\Models\Concafactven;
use Exception;
use PhpParser\NodeVisitor\FirstFindingVisitor;

class ConcafactvenService
{
    public function index(array $data = [])
    {
        
        $data = Concafactven::get();
        if (!$data)
            throw new \Exception("No existe el Lote");
        
        return $data;
    }

    public function conceptosFc(array $data = [])
    {

        $data = Concafactven::where('impuesto', 1)->get();
        if (!$data)
            throw new \Exception("No existe el Lote");
        
        return $data;
    }

    public function store(array $data = [])
    {

        
        $concafactven = Concafactven::where('codnum', $data['codnum'])->first();
        if ($concafactven)
            throw new \Exception("Ya existe el banco");

        
        return Concafactven::create($data);
    }


    public function update(array $data = [], int $codnum)
    {
        $concafactven = Concafactven::where('codnum', $codnum);
        $query = $concafactven->first();
        if (!$query) {
            throw new \Exception("No existe el Banco");
        }
        return $concafactven->update($data);
    }


    public function destroy(int $codnum)
    {
        $concafactven = Concafactven::where('codnum', $codnum);
        $query = $concafactven->first();
        if (!$query) {
            throw new \Exception("No existe el Banco");
        }
        return $concafactven->delete();

    }
}
