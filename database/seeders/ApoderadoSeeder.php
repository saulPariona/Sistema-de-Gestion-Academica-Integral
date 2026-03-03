<?php

namespace Database\Seeders;

use App\Models\Apoderado;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApoderadoSeeder extends Seeder
{
    public function run(): void
    {
        $estudiantes = User::where('rol', 'estudiante')->get();

        $apoderados = [
            // parentescos comunes en Perú
            ['nombre' => 'Roberto Carlos',  'apellido' => 'Pérez González',       'parentesco' => 'padre',   'dni' => '40100001'],
            ['nombre' => 'Carmen Rosa',     'apellido' => 'Sánchez Medina',       'parentesco' => 'madre',   'dni' => '40100002'],
            ['nombre' => 'Jorge Luis',      'apellido' => 'López Herrera',        'parentesco' => 'padre',   'dni' => '40100003'],
            ['nombre' => 'Martha Gladys',   'apellido' => 'Ramírez Díaz',         'parentesco' => 'madre',   'dni' => '40100004'],
            ['nombre' => 'Pedro Miguel',    'apellido' => 'Gutierrez Salazar',    'parentesco' => 'padre',   'dni' => '40100005'],
            ['nombre' => 'Teresa Juana',    'apellido' => 'Castañeda Ponce',      'parentesco' => 'madre',   'dni' => '40100006'],
            ['nombre' => 'Luis Alberto',    'apellido' => 'Morales Benítez',      'parentesco' => 'padre',   'dni' => '40100007'],
            ['nombre' => 'Silvia Nora',     'apellido' => 'Villanueva Torres',    'parentesco' => 'madre',   'dni' => '40100008'],
            ['nombre' => 'Eduardo',         'apellido' => 'Herrera Campos',       'parentesco' => 'padre',   'dni' => '40100009'],
            ['nombre' => 'Norma Beatriz',   'apellido' => 'Quispe Flores',        'parentesco' => 'madre',   'dni' => '40100010'],
            ['nombre' => 'Raúl Enrique',    'apellido' => 'Mamani Condori',       'parentesco' => 'padre',   'dni' => '40100011'],
            ['nombre' => 'Gloria María',    'apellido' => 'Huanca Rivera',        'parentesco' => 'madre',   'dni' => '40100012'],
            ['nombre' => 'Julio César',     'apellido' => 'Mendoza Vargas',       'parentesco' => 'padre',   'dni' => '40100013'],
            ['nombre' => 'Doris Yolanda',   'apellido' => 'Paredes Castro',       'parentesco' => 'madre',   'dni' => '40100014'],
            ['nombre' => 'Víctor Hugo',     'apellido' => 'Flores Espinoza',      'parentesco' => 'padre',   'dni' => '40100015'],
            ['nombre' => 'María Elena',     'apellido' => 'Huamán Salazar',       'parentesco' => 'madre',   'dni' => '40100016'],
            ['nombre' => 'José Antonio',    'apellido' => 'Rivera Mendoza',       'parentesco' => 'padre',   'dni' => '40100017'],
            ['nombre' => 'Luz Marina',      'apellido' => 'Aguilar Rojas',        'parentesco' => 'madre',   'dni' => '40100018'],
            ['nombre' => 'Óscar Andrés',    'apellido' => 'Delgado Ortega',       'parentesco' => 'padre',   'dni' => '40100019'],
            ['nombre' => 'Gladys Aurora',   'apellido' => 'Bustamante Díaz',      'parentesco' => 'madre',   'dni' => '40100020'],
            ['nombre' => 'Segundo Manuel',  'apellido' => 'Córdova Valencia',     'parentesco' => 'abuelo',  'dni' => '40100021'],
            ['nombre' => 'Gregoria',        'apellido' => 'Solórzano Paz',        'parentesco' => 'abuela',  'dni' => '40100022'],
            ['nombre' => 'Carlos Enrique',  'apellido' => 'Campos Prado',         'parentesco' => 'tío',     'dni' => '40100023'],
            ['nombre' => 'Ana María',       'apellido' => 'Tapia Navarro',        'parentesco' => 'madre',   'dni' => '40100024'],
            ['nombre' => 'Walter Jesús',    'apellido' => 'Choque Velásquez',     'parentesco' => 'padre',   'dni' => '40100025'],
            ['nombre' => 'Juana Catalina',  'apellido' => 'Contreras Ramos',      'parentesco' => 'madre',   'dni' => '40100026'],
            ['nombre' => 'Félix Alejandro', 'apellido' => 'Apaza Silva',          'parentesco' => 'padre',   'dni' => '40100027'],
            ['nombre' => 'Nelly Haydée',    'apellido' => 'Rondón Paredes',       'parentesco' => 'madre',   'dni' => '40100028'],
            ['nombre' => 'Hugo Alfredo',    'apellido' => 'Aquino Ponce',         'parentesco' => 'padre',   'dni' => '40100029'],
            ['nombre' => 'Blanca Flor',     'apellido' => 'Montalvo Cáceres',     'parentesco' => 'madre',   'dni' => '40100030'],
        ];

        // Asignar un apoderado a los primeros 30 estudiantes
        foreach ($estudiantes->take(30) as $i => $est) {
            $ap = $apoderados[$i];
            Apoderado::create([
                'estudiante_id' => $est->id,
                'nombre_completo' => $ap['nombre'] . ' ' . $ap['apellido'],
                'dni' => $ap['dni'],
                'telefono' => '9' . str_pad((string)($i + 50), 8, '0', STR_PAD_LEFT),
                'email' => strtolower(str_replace(' ', '.', explode(' ', $ap['nombre'])[0])) . '.' .
                           strtolower(explode(' ', $ap['apellido'])[0]) . '@gmail.com',
                'parentesco' => $ap['parentesco'],
            ]);
        }
    }
}
