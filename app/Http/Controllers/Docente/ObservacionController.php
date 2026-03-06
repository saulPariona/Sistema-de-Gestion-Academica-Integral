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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

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
        $curso = Curso::with('periodo')->findOrFail($curso);
        $this->authorize('gestionar', $curso);

        $estudiantes = $curso->estudiantes()->orderBy('apellidos')->get();
        $examenes = Examen::where('curso_id', $curso->id)->orderBy('created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Notas');

        // Fila 1: Info del curso
        $sheet->setCellValue('A1', 'Curso: ' . $curso->nombre);
        $sheet->setCellValue('A2', 'Periodo: ' . $curso->periodo->nombre);
        $sheet->setCellValue('A3', 'Fecha: ' . now()->format('d/m/Y'));

        // Fila 5: Encabezados
        $row = 5;
        $col = 'A';
        $sheet->setCellValue($col++ . $row, 'N°');
        $sheet->setCellValue($col++ . $row, 'DNI');
        $sheet->setCellValue($col++ . $row, 'Apellidos y Nombres');

        $examenCols = [];
        foreach ($examenes as $examen) {
            $examenCols[] = $col;
            $sheet->setCellValue($col++ . $row, $examen->titulo);
        }

        $colPromedio = $col++;
        $sheet->setCellValue($colPromedio . $row, 'Promedio');
        $colEstado = $col;
        $sheet->setCellValue($colEstado . $row, 'Estado');

        // Datos de estudiantes
        $row = 6;
        $numero = 1;

        foreach ($estudiantes as $estudiante) {
            $col = 'A';
            $sheet->setCellValue($col++ . $row, $numero);
            $sheet->setCellValueExplicit($col++ . $row, $estudiante->dni, DataType::TYPE_STRING);
            $sheet->setCellValue($col++ . $row, $estudiante->apellidos . ', ' . $estudiante->nombres);

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

                $sheet->setCellValue($col++ . $row, $nota);
                $sumaNotas += $nota;
                $contadorExamenes++;
            }

            $promedio = $contadorExamenes > 0 ? round($sumaNotas / $contadorExamenes, 2) : 0;
            $sheet->setCellValue($colPromedio . $row, $promedio);
            $sheet->setCellValue($colEstado . $row, $promedio >= 11 ? 'Aprobado' : 'Desaprobado');

            $row++;
            $numero++;
        }

        // Autoajustar ancho de columnas
        foreach (range('A', $colEstado) as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        // Generar descarga
        $nombreCurso = preg_replace('/[^A-Za-z0-9_\-]/', '_', $curso->nombre);
        $filename = "Notas_{$nombreCurso}_" . now()->format('Y-m-d') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
