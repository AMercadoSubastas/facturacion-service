<?php

namespace App\Services;

use App\Models\Remates;
use App\Models\Lotes;
use Exception;
use PhpParser\NodeVisitor\FirstFindingVisitor;
use Illuminate\Support\Facades\Log;

class RemateService
{
    public function index(array $data = [])
    {
        $per_page = $data['per_page'] ?? 10;
        $sort_by = $data['sort_by'] ?? 'codnum';
        $sort_order = $data['sort_order'] ?? 'desc';
    
        $query = Remates::with(['codpais', 'codprov', 'codloc', 'codcli', 'tipoind', 'lotes', 'usuarioAsignado']);
    
        // Filtrar por término de búsqueda en codnum, codcli.razsoc o usuario.nombre
        if (!empty($data['search'])) {
            $search = $data['search'];
            $query->where(function ($q) use ($search) {
                $q->where('codnum', 'like', '%' . $search . '%')
                  ->orWhereHas('codcli', function ($subQuery) use ($search) {
                      $subQuery->where('razsoc', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('usuarioAsignado', function ($subQuery) use ($search) {
                      $subQuery->where('nombre', 'like', '%' . $search . '%');
                  });
            });
        }
    
        $query->orderBy($sort_by, $sort_order);
    
        $result = $query->paginate($per_page);
    
        // Registrar los resultados devueltos
        Log::info('Resultados de la consulta:', [
            'result' => $result->toArray(),
        ]);
    
        return $result;
    }
    
    public function show(int $ncomp)
    {
        $data = Remates::where('ncomp', $ncomp)
            ->with(['codpais', 'codprov', 'codloc', 'codcli', 'tipoind'])
            ->first();
    
        if (!$data) {
            throw new \Exception("No existe la Subasta");
        }
    
        // Obtener la lista de clientes activos
        $clientesActivos = \App\Models\Entidades::where('activo', 1)->get();
    
        return [
            'subasta' => $data,
            'clientes' => $clientesActivos,
        ];
    }
    
    public function obtenerLotes(int $codnum, array $params = [])
    {
        $perPage = $params['per_page'] ?? 10;
        $sortBy = $params['sort_by'] ?? 'codnum'; // Por defecto ordena por 'codnum'
        $sortOrder = $params['sort_order'] ?? 'asc';
        $search = $params['search'] ?? null;
    
        $query = Lotes::where('codrem', $codnum);
    
        // Filtrar por búsqueda
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('codnum', 'like', "%$search%")
                  ->orWhere('codintnum', 'like', "%$search%")
                  ->orWhere('observ', 'like', "%$search%")
                  ->orWhere('descor', 'like', "%$search%");
            });
        }
    
        // Ordenar
        if (in_array($sortBy, ['codnum', 'codintnum', 'estado', 'preciobase', 'fecalta'])) {
            $query->orderBy($sortBy, $sortOrder);
        }
    
        return $query->paginate($perPage);
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
