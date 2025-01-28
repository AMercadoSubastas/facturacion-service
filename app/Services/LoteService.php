<?php

namespace App\Services;

use App\Models\Lotes;
use Exception;
use Illuminate\Support\Facades\Log;
use PhpParser\NodeVisitor\FirstFindingVisitor;

class LoteService
{
    public function index(array $data = [])
    {
        $per_page = $data['per_page'] ?? 10;
        $sort_by = $data['sort_by'] ?? 'codnum';
        $sort_order = $data['sort_order'] ?? 'desc';
        $search = $data['search'] ?? null;
    
        $query = Lotes::query();
    
        // Aplicar búsqueda
        if (!empty($search)) {
            $query->where('codnum', 'LIKE', "%$search%")
                  ->orWhere('observ', 'LIKE', "%$search%")
                  ->orWhere('descor', 'LIKE', "%$search%");
        }
    
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

    public function updateEstado(int $codnum, int $estado)
    {
        // Registra los valores de entrada en un array
        Log::info('Actualizando estado del lote', [
            'codnum' => $codnum,
            'estado' => $estado,
        ]);
    
        // Busca el lote
        $lote = Lotes::where('codnum', $codnum)->first();
    
        // Registra el lote si existe
        Log::info('Lote encontrado:', $lote ? $lote->toArray() : ['Lote no encontrado']);
    
        if (!$lote) {
            throw new \Exception("El lote no existe.");
        }
    
        // Actualiza el estado
        $lote->estado = $estado;
        $lote->save();
    
        Log::info('Estado del lote actualizado', [
            'codnum' => $codnum,
            'nuevo_estado' => $estado,
        ]);
    
        return $lote;
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
