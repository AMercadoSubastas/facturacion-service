<?php

namespace App\Services;


use Exception;

use App\Models\Entidades;
use App\Models\Tipoivas;
use App\Models\Tipoenti;

use App\Services\AFIPService; 
use Illuminate\Support\Facades\Log;


class EntidadService
{
    protected $afipService;

    public function __construct(AFIPService $afipService)
    {
        $this->afipService = $afipService;
    }

    public function index(array $data = [])
    {
        try {
            $per_page = $data['per_page'] ?? 10; // Número de resultados por página
            $sort_by = $data['sort_by'] ?? 'codnum'; // Columna para ordenar
            $sort_order = $data['sort_order'] ?? 'asc'; // Orden (ascendente o descendente)
            $search = $data['search'] ?? '';

            // Realiza la consulta con paginación, cargando las relaciones necesarias
            $query = Entidades::query()
                ->with(['codpais', 'codprov', 'codloc', 'tipoenti', 'tipoiva', 'tipoind']) // Asegúrate de incluir 'tipoiva'
                ->orderBy($sort_by, $sort_order);

            // Aplicar el filtro de búsqueda si hay un término proporcionado
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('razsoc', 'like', "%{$search}%")
                      ->orWhere('cuit', 'like', "%{$search}%");
                });
            }

            // Aplica paginación antes de obtener los resultados
            return $query->paginate($per_page);
        } catch (Exception $e) {
            throw new Exception("Error al obtener las entidades: " . $e->getMessage());
        }
    }



    public function show(string $cuit)
    {
        try {
            $entidades = Entidades::where('cuit', $cuit)->with([
                'codpais',
                'codprov',
                'codloc',
                'tipoenti',
                'tipoiva',
                'tipoind'
            ])->get();
            
            if ($entidades->isEmpty()) {
                throw new Exception("No existe la entidad o el CUIT es incorrecto. Agregar guiones si es necesario.");
            }

            return $entidades;
        } catch (Exception $e) {
            throw new Exception("Error al mostrar la entidad: " . $e->getMessage());
        }
    }

    public function codnum($cuit)
    {
        try {
            $entidad = Entidades::where('cuit', $cuit)->first();

            if (!$entidad) {
                throw new Exception("No existe la entidad o el CUIT es incorrecto. Agregar guiones si es necesario.");
            }
            
            return $entidad->codnum;
        } catch (Exception $e) {
            throw new Exception("Error al obtener el codnum de la entidad: " . $e->getMessage());
        }
    }

    public function store(array $data = [])
    {
        try {
            $entidadExistente = Entidades::where('cuit', $data['cuit'])
                                         ->where('tipoent', $data['tipoent'])
                                         ->first();

            if ($entidadExistente) {
                throw new Exception("Ya existe una entidad con el CUIT " . $data['cuit']);
            }
            return Entidades::create($data);
        } catch (Exception $e) {
            throw new Exception("Error al crear la entidad: " . $e->getMessage());
        }
    }


    public function update(array $data = [], int $codnum)
    {
        try {
            $entidades = Entidades::where('codnum', $codnum);
            $query = $entidades->first();

            if (!$query) {
                throw new Exception("No existe la entidad con el codnum " . $codnum);
            }

            return $entidades->update($data);
        } catch (Exception $e) {
            throw new Exception("Error al actualizar la entidad: " . $e->getMessage());
        }
    }


    public function destroy(int $codnum)
    {
        try {
            $entidades = Entidades::where('codnum', $codnum);
            $query = $entidades->first();

            if (!$query) {
                throw new Exception("No existe la entidad con el codnum " . $codnum);
            }

            return $entidades->delete();
        } catch (Exception $e) {
            throw new Exception("Error al eliminar la entidad: " . $e->getMessage());
        }
    }

    public function searchConstancia($cuit)
    {
        try {
             return $this->afipService->searchConstancia($cuit);
        } catch (Exception $e) {
            throw new Exception("Error al buscar la constancia: " . $e->getMessage());
        }
    }
    public function updateEstado(int $codnum, array $data = [])
    {
        try {
            Log::info("Valor de codnum: " . $codnum);
            $entidad = Entidades::findOrFail($codnum);
            Log::info("Entidad encontrada: " . json_encode($entidad));

            $entidad->activo = $data['activo'];
            $entidad->save();

            Log::info("Estado actualizado a: " . $data['activo']);

            return $entidad;
        } catch (Exception $e) {
            Log::error("Error al actualizar el estado de la entidad: " . $e->getMessage());
            throw new Exception("Error al actualizar el estado de la entidad: " . $e->getMessage());
        }
    }

    public function showByCodnum(int $codnum)
    {
        try {
    
            $entidad = Entidades::where('codnum', $codnum)
                                ->with(['codpais', 'codprov', 'codloc', 'tipoenti', 'tipoiva', 'tipoind'])
                                ->first();
            Log::info($entidad);
            $tipoenti =  Tipoenti::where('codnum', $entidad->tipoent)->first();
            Log::info($tipoenti);
            if (!$entidad) {
                throw new Exception("No existe la entidad con el codnum " . $codnum);
            }

            return $entidad;
        } catch (Exception $e) {
            throw new Exception("Error al mostrar la entidad: " . $e->getMessage());
        }
    }

    

}
