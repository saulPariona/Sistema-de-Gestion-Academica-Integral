<?php

namespace Database\Seeders;

use App\Models\Apoderado;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApoderadoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $estudiantes = User::where('rol', 'estudiante')->get();
        $this->command->info("  → Creando apoderados para {$estudiantes->count()} estudiantes...");

        $nombresM = [
            'Roberto Carlos', 'Jorge Luis', 'Pedro Miguel', 'Luis Alberto', 'Eduardo',
            'Raúl Enrique', 'Julio César', 'Víctor Hugo', 'José Antonio', 'Óscar Andrés',
            'Segundo Manuel', 'Carlos Enrique', 'Walter Jesús', 'Félix Alejandro', 'Hugo Alfredo',
            'Mario César', 'Fernando David', 'Gregorio Martín', 'Ernesto Javier', 'Andrés Felipe',
            'Miguel Ángel', 'Juan Carlos', 'Ricardo Daniel', 'Elías Benjamín', 'Gonzalo Rafael',
        ];

        $nombresF = [
            'Carmen Rosa', 'Martha Gladys', 'Teresa Juana', 'Silvia Nora', 'Norma Beatriz',
            'Gloria María', 'Doris Yolanda', 'María Elena', 'Luz Marina', 'Gladys Aurora',
            'Gregoria', 'Ana María', 'Juana Catalina', 'Nelly Haydée', 'Blanca Flor',
            'Rosa Elvira', 'Pilar Sofía', 'Olga Susana', 'Miriam Ruth', 'Angela Patricia',
            'Sonia Margarita', 'Rita Isabel', 'Graciela Inés', 'Elena Beatriz', 'Irene Guadalupe',
        ];

        $apellidos = [
            'Pérez González', 'Sánchez Medina', 'López Herrera', 'Ramírez Díaz', 'Gutierrez Salazar',
            'Castañeda Ponce', 'Morales Benítez', 'Villanueva Torres', 'Herrera Campos', 'Quispe Flores',
            'Mamani Condori', 'Huanca Rivera', 'Mendoza Vargas', 'Paredes Castro', 'Flores Espinoza',
            'Huamán Salazar', 'Rivera Mendoza', 'Aguilar Rojas', 'Delgado Ortega', 'Bustamante Díaz',
            'Córdova Valencia', 'Solórzano Paz', 'Campos Prado', 'Tapia Navarro', 'Choque Velásquez',
            'Contreras Ramos', 'Apaza Silva', 'Rondón Paredes', 'Aquino Ponce', 'Montalvo Cáceres',
            'Vargas Mendoza', 'Castro Paredes', 'Espinoza Rivera', 'Salazar Vega', 'Rojas Delgado',
            'Díaz Córdova', 'Ortega Bustamante', 'Paz Solórzano', 'Campos Valencia', 'Navarro Choque',
            'Velásquez Tapia', 'Contreras Prado', 'Ramos Apaza', 'Silva Rondón', 'Paredes Aquino',
            'Cáceres Montalvo', 'Ponce Esquivel', 'Benítez Carranza', 'Soto Maldonado', 'Huanca Ticona',
        ];

        $parentescos = ['padre', 'madre', 'padre', 'madre', 'abuelo', 'abuela', 'tío', 'madre', 'padre', 'madre'];

        $batch = [];
        $dniBase = 40100001;
        $estudiantesConApoderado = $estudiantes->take((int) ($estudiantes->count() * 0.70));

        foreach ($estudiantesConApoderado as $i => $est) {
            // Primer apoderado (siempre)
            $parentesco = $parentescos[$i % count($parentescos)];
            $esMasculino = in_array($parentesco, ['padre', 'abuelo', 'tío']);
            $nombre = $esMasculino
                ? $nombresM[$i % count($nombresM)]
                : $nombresF[$i % count($nombresF)];
            $apellido = $apellidos[$i % count($apellidos)];
            $dniNum = $dniBase + $i;

            $primerNombre = strtolower(Str::ascii(explode(' ', $nombre)[0]));
            $primerApellido = strtolower(Str::ascii(explode(' ', $apellido)[0]));

            $batch[] = [
                'estudiante_id' => $est->id,
                'nombre_completo' => "{$nombre} {$apellido}",
                'dni' => (string) $dniNum,
                'telefono' => '9' . str_pad((string) ($i + 50), 8, '0', STR_PAD_LEFT),
                'email' => "{$primerNombre}.{$primerApellido}.{$dniNum}@gmail.com",
                'parentesco' => $parentesco,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Segundo apoderado (~25% de los estudiantes)
            if ($i % 4 === 0) {
                $parentesco2 = $esMasculino ? 'madre' : 'padre';
                $nombre2 = $esMasculino
                    ? $nombresF[($i + 5) % count($nombresF)]
                    : $nombresM[($i + 5) % count($nombresM)];
                $apellido2 = $apellidos[($i + 7) % count($apellidos)];
                $dniNum2 = 40200001 + $i;

                $primerNombre2 = strtolower(Str::ascii(explode(' ', $nombre2)[0]));
                $primerApellido2 = strtolower(Str::ascii(explode(' ', $apellido2)[0]));

                $batch[] = [
                    'estudiante_id' => $est->id,
                    'nombre_completo' => "{$nombre2} {$apellido2}",
                    'dni' => (string) $dniNum2,
                    'telefono' => '9' . str_pad((string) ($i + 2050), 8, '0', STR_PAD_LEFT),
                    'email' => "{$primerNombre2}.{$primerApellido2}.{$dniNum2}@gmail.com",
                    'parentesco' => $parentesco2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insertar en lotes de 250
            if (count($batch) >= 250) {
                Apoderado::insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            Apoderado::insert($batch);
        }
    }
}
