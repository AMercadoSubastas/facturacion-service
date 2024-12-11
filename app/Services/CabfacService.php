<?php

namespace App\Services;

use App\Models\Cabfac;
use Exception;
use PhpParser\NodeVisitor\FirstFindingVisitor;

class CabfacService
{
    public function index(array $data = [])
    {
        $per_page = $data['per_page'] ?? 1;
        $sort_by = $data['sort_by'] ?? 'codnum';
        $sort_order = $data['sort_order'] ?? 'asc';
        $query = Cabfac::query();

        // Aplicar orden
        $query->orderBy($sort_by, $sort_order);

        return $query->paginate($per_page);
    }

    public function show(int $codnum)
    {

        $cabfac = Cabfac::where('codnum', $codnum)->first();
        if (!$cabfac)
            throw new \Exception("No existe la factura");
        
        return $cabfac;
    }

    public function ncomp($tcomp){
        
        return Cabfac::where('tcomp', $tcomp)->orderBy('ncomp', 'desc')->first()->ncomp;
    
    }

    public function store(array $data = [])
    {

        $cabfac = Cabfac::where('tcomp', $data['tcomp'])
                        ->where('serie', $data['serie'])
                        ->where('ncomp', $data['ncomp'])
                        ->first();
        if ($cabfac)
            throw new \Exception("Ya existe la factura estimadx...");
        
        return Cabfac::create($data);
    }


    public function update(array $data = [], int $codnum)
    {
        $cabfac = Cabfac::where('codnum', $codnum);
        $query = $cabfac->first();
        if (!$query) {
            throw new \Exception("No existe el factura");
        }
        return $cabfac->update($data);
    }


    public function destroy(int $codnum)
    {
        $cabfac = Cabfac::where('codnum', $codnum);
        $query = $cabfac->first();
        if (!$query) {
            throw new \Exception("No existe el factura");
        }
        return $cabfac->delete();

    }
}
