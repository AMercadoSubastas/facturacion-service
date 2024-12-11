<?php

namespace App\Services;

use App\Models\Entidades;
use Exception;
use PhpParser\NodeVisitor\FirstFindingVisitor;

class EntidadService
{
    public function index(array $data = [])
    {
        $per_page = $data['per_page'] ?? 1;
        $sort_by = $data['sort_by'] ?? 'codnum';
        $sort_order = $data['sort_order'] ?? 'asc';
        $query = Entidades::query();

        // Aplicar orden
        $query->with([
            'codpais',
            'codprov',
            'codloc',
            'tipoenti',
            'tipoiva',
            'tipoind'
        ])
        ->orderBy($sort_by, $sort_order);

        return $query->paginate($per_page);
    }

    public function show(string $cuit)
    {

        $entidades = Entidades::where('cuit', $cuit)->with([
            'codpais',
            'codprov',
            'codloc',
            'tipoenti',
            'tipoiva',
            'tipoind'
        ])->get();
        
        if (!$entidades) {
            throw new \Exception("No existe la entidad o cuit incorrecto/agregar guiones");
        }
        
        return $entidades;
    }

    public function codnum($cuit) {

        $entidades = Entidades::where('cuit', $cuit)->first()->codnum;
        if (!$entidades) {
            throw new \Exception("No existe la entidad o cuit incorrecto/agregar guiones");
        }
        
        return $entidades;
    }

    public function store(array $data = [])
    {

        
        $entidades = Entidades::where('cuit', $data['cuit'])
                                ->where('tipoent', $data['tipoent'])
                                ->first();
        if ($entidades)
            throw new \Exception("Ya existe la entidad ".$data['cuit']."");

        
        return Entidades::create($data);
    }


    public function update(array $data = [], int $codnum)
    {
        $entidades = Entidades::where('codnum', $codnum);
        $query = $entidades->first();
        if (!$query) {
            throw new \Exception("No existe la entidad");
        }
        return $entidades->update($data);
    }


    public function destroy(int $codnum)
    {
        $entidades = Entidades::where('codnum', $codnum);
        $query = $entidades->first();
        if (!$query) {
            throw new \Exception("No existe la entidad");
        }
        return $entidades->delete();

    }
}
