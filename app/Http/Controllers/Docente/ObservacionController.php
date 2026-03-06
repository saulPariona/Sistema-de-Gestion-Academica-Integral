<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Docente\StoreObservacionRequest;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Intento;
use App\Models\Observacion;
use App\Services\AuditoriaService;
use Illuminate\Support\Facades\Auth;

class ObservacionController extends Controller
{
    public function index(int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $this->authorize('gestionar', $curso);
        $observaciones = Observacion::where('curso_id', $curso->id)
            ->where('docente_id', Auth::id())
            ->with('estudiante')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('docente.observaciones', compact('curso', 'observaciones'));
    }

    public function create(int $curso)
    {
        $curso = Curso::with('estudiantes')->findOrFail($curso);
        $this->authorize('gestionar', $curso);
        return view('docente.crear-observacion', compact('curso'));
    }

    public function store(StoreObservacionRequest $request, int $curso)
    {
        $observacion = Observacion::create([
            'docente_id' => Auth::id(),
            'estudiante_id' => $request->estudiante_id,
            'curso_id' => $curso,
            'texto' => $request->texto,
        ]);

        AuditoriaService::registrar('crear_observacion', 'Observacion', $observacion->id);
        return redirect()->route('docente.observaciones', $curso)->with('status', 'Observación registrada correctamente.');
    }

    public function exportarNotas(int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $this->authorize('gestionar', $curso);

        $estudiantes = $curso->estudiantes;
        $examenes = Examen::where('curso_id', $curso->id)->get();

        $datos = [];
        foreach ($estudiantes as $estudiante) {
            $fila = [
                'DNI' => $estudiante->dni,
                'Estudiante' => $estudiante->nombreCompleto(),
            ];

            $sumaNotas = 0;
            $contadorExamenes = 0;

            foreach ($examenes as $examen) {
                $intento = Intento::where('examen_id', $examen->id)
                    ->where('estudiante_id', $estudiante->id)
                    ->where('estado', 'finalizado')
                    ->orderBy('puntaje_obtenido', 'desc')
                    ->first();

                $nota = 0;
                if ($intento && $examen->puntaje_total > 0) {
                    $nota = round(($intento->puntaje_obtenido / $examen->puntaje_total) * 20, 2);
                }
                $fila[$examen->titulo] = $nota;
                $sumaNotas += $nota;
                $contadorExamenes++;
            }

            $fila['Promedio'] = $contadorExamenes > 0 ? round($sumaNotas / $contadorExamenes, 2) : 0;
            $datos[] = $fila;
        }

        $filename = "notas_{$curso->nombre}_" . now()->format('Ymd') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($datos) {
            $file = fopen('php://output', 'w');
            if (!empty($datos)) {
                fputcsv($file, array_keys($datos[0]));
                foreach ($datos as $fila) {
                    fputcsv($file, $fila);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
