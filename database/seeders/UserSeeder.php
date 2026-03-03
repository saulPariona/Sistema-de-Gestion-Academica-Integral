<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== ADMINISTRADORES ====================
        User::create([
            'nombres' => 'Admin',
            'apellidos' => 'Sistema',
            'dni' => '00000001',
            'email' => 'admin@colegiomp.edu.pe',
            'password' => Hash::make('Admin1234'),
            'rol' => 'administrador',
            'estado' => 'activo',
            'cargo' => 'Administrador General',
            'telefono' => '999000001',
        ]);

        User::create([
            'nombres' => 'Rosa María',
            'apellidos' => 'Huamán Quispe',
            'dni' => '10234567',
            'email' => 'rosa.huaman@colegiomp.edu.pe',
            'password' => Hash::make('Admin1234'),
            'rol' => 'administrador',
            'estado' => 'activo',
            'sexo' => 'F',
            'cargo' => 'Coordinadora Académica',
            'telefono' => '999000002',
            'fecha_nacimiento' => '1980-03-12',
            'direccion' => 'Av. Arequipa 1520, Lince',
        ]);

        // ==================== DOCENTES (8 - uno por materia) ====================
        $docentes = [
            [
                'nombres' => 'Carlos Eduardo',
                'apellidos' => 'García López',
                'dni' => '12345678',
                'email' => 'carlos.garcia@colegiomp.edu.pe',
                'especialidad' => 'Álgebra',
                'grado_academico' => 'Magíster en Matemática Pura',
                'sexo' => 'M',
                'telefono' => '987654321',
                'fecha_nacimiento' => '1985-06-20',
                'direccion' => 'Jr. Lampa 456, Cercado de Lima',
            ],
            [
                'nombres' => 'Ana Lucía',
                'apellidos' => 'Martínez Rojas',
                'dni' => '23456789',
                'email' => 'ana.martinez@colegiomp.edu.pe',
                'especialidad' => 'Aritmética',
                'grado_academico' => 'Licenciada en Matemáticas',
                'sexo' => 'F',
                'telefono' => '987654322',
                'fecha_nacimiento' => '1988-11-05',
                'direccion' => 'Calle Los Olivos 234, San Isidro',
            ],
            [
                'nombres' => 'Miguel Ángel',
                'apellidos' => 'Torres Mendoza',
                'dni' => '34567890',
                'email' => 'miguel.torres@colegiomp.edu.pe',
                'especialidad' => 'Geometría',
                'grado_academico' => 'Magíster en Didáctica de la Matemática',
                'sexo' => 'M',
                'telefono' => '987654323',
                'fecha_nacimiento' => '1982-01-15',
                'direccion' => 'Av. Brasil 780, Jesús María',
            ],
            [
                'nombres' => 'Patricia Carmen',
                'apellidos' => 'Vargas Castillo',
                'dni' => '45678901',
                'email' => 'patricia.vargas@colegiomp.edu.pe',
                'especialidad' => 'Trigonometría',
                'grado_academico' => 'Licenciada en Ciencias Matemáticas',
                'sexo' => 'F',
                'telefono' => '987654324',
                'fecha_nacimiento' => '1990-07-28',
                'direccion' => 'Av. Universitaria 1200, San Martín de Porres',
            ],
            [
                'nombres' => 'Roberto Jesús',
                'apellidos' => 'Chávez Sánchez',
                'dni' => '56789012',
                'email' => 'roberto.chavez@colegiomp.edu.pe',
                'especialidad' => 'Física',
                'grado_academico' => 'Doctor en Física',
                'sexo' => 'M',
                'telefono' => '987654325',
                'fecha_nacimiento' => '1979-09-03',
                'direccion' => 'Calle Las Begonias 345, Miraflores',
            ],
            [
                'nombres' => 'Claudia Alejandra',
                'apellidos' => 'Ríos Fernández',
                'dni' => '67890123',
                'email' => 'claudia.rios@colegiomp.edu.pe',
                'especialidad' => 'Química',
                'grado_academico' => 'Magíster en Química Analítica',
                'sexo' => 'F',
                'telefono' => '987654326',
                'fecha_nacimiento' => '1987-04-17',
                'direccion' => 'Jr. Puno 678, Breña',
            ],
            [
                'nombres' => 'Fernando José',
                'apellidos' => 'Medina Paredes',
                'dni' => '78901234',
                'email' => 'fernando.medina@colegiomp.edu.pe',
                'especialidad' => 'Razonamiento Matemático',
                'grado_academico' => 'Licenciado en Educación Matemática',
                'sexo' => 'M',
                'telefono' => '987654327',
                'fecha_nacimiento' => '1984-12-09',
                'direccion' => 'Av. Colonial 2100, Callao',
            ],
            [
                'nombres' => 'Lucía Esperanza',
                'apellidos' => 'Palacios Huerta',
                'dni' => '89012345',
                'email' => 'lucia.palacios@colegiomp.edu.pe',
                'especialidad' => 'Razonamiento Verbal',
                'grado_academico' => 'Magíster en Lingüística',
                'sexo' => 'F',
                'telefono' => '987654328',
                'fecha_nacimiento' => '1986-08-22',
                'direccion' => 'Calle Schell 456, Miraflores',
            ],
        ];

        foreach ($docentes as $d) {
            User::create(array_merge($d, [
                'password' => Hash::make('Docente1234'),
                'rol' => 'docente',
                'estado' => 'activo',
            ]));
        }

        // ==================== ESTUDIANTES (42) ====================
        $estudiantes = [
            ['nombres' => 'María Fernanda',   'apellidos' => 'Pérez Sánchez',       'dni' => '70100001', 'sexo' => 'F', 'fecha_nacimiento' => '2008-05-15'],
            ['nombres' => 'Juan Diego',       'apellidos' => 'López Torres',         'dni' => '70100002', 'sexo' => 'M', 'fecha_nacimiento' => '2008-08-20'],
            ['nombres' => 'Camila Valentina', 'apellidos' => 'Ramírez Flores',       'dni' => '70100003', 'sexo' => 'F', 'fecha_nacimiento' => '2007-12-03'],
            ['nombres' => 'Sebastián André',  'apellidos' => 'Gutierrez Rojas',      'dni' => '70100004', 'sexo' => 'M', 'fecha_nacimiento' => '2008-02-14'],
            ['nombres' => 'Valentina Nicole', 'apellidos' => 'Morales Castañeda',    'dni' => '70100005', 'sexo' => 'F', 'fecha_nacimiento' => '2007-09-27'],
            ['nombres' => 'Diego Alejandro',  'apellidos' => 'Herrera Villanueva',   'dni' => '70100006', 'sexo' => 'M', 'fecha_nacimiento' => '2008-01-08'],
            ['nombres' => 'Luciana Sofía',    'apellidos' => 'Quispe Mamani',        'dni' => '70100007', 'sexo' => 'F', 'fecha_nacimiento' => '2008-04-19'],
            ['nombres' => 'Matías Gabriel',   'apellidos' => 'Condori Huanca',       'dni' => '70100008', 'sexo' => 'M', 'fecha_nacimiento' => '2007-11-30'],
            ['nombres' => 'Antonella Brisa',  'apellidos' => 'Vargas Mendoza',       'dni' => '70100009', 'sexo' => 'F', 'fecha_nacimiento' => '2008-07-22'],
            ['nombres' => 'Thiago Manuel',    'apellidos' => 'Castro Paredes',       'dni' => '70100010', 'sexo' => 'M', 'fecha_nacimiento' => '2008-03-05'],
            ['nombres' => 'Ariana Celeste',   'apellidos' => 'Flores Huamán',        'dni' => '70100011', 'sexo' => 'F', 'fecha_nacimiento' => '2007-10-12'],
            ['nombres' => 'Leonardo Fabián',  'apellidos' => 'Espinoza Rivera',      'dni' => '70100012', 'sexo' => 'M', 'fecha_nacimiento' => '2008-06-25'],
            ['nombres' => 'Jimena Andrea',    'apellidos' => 'Salazar Vega',         'dni' => '70100013', 'sexo' => 'F', 'fecha_nacimiento' => '2008-09-01'],
            ['nombres' => 'Adrián José',      'apellidos' => 'Rojas Delgado',        'dni' => '70100014', 'sexo' => 'M', 'fecha_nacimiento' => '2007-08-16'],
            ['nombres' => 'Valeria Isabela',  'apellidos' => 'Díaz Córdova',         'dni' => '70100015', 'sexo' => 'F', 'fecha_nacimiento' => '2008-01-29'],
            ['nombres' => 'Rodrigo Alonso',   'apellidos' => 'Mendoza Aguilar',      'dni' => '70100016', 'sexo' => 'M', 'fecha_nacimiento' => '2008-05-07'],
            ['nombres' => 'Catalina Luz',     'apellidos' => 'Ortega Bustamante',    'dni' => '70100017', 'sexo' => 'F', 'fecha_nacimiento' => '2007-07-14'],
            ['nombres' => 'Emilio Santiago',  'apellidos' => 'Paz Solórzano',        'dni' => '70100018', 'sexo' => 'M', 'fecha_nacimiento' => '2008-10-03'],
            ['nombres' => 'Renata Milagros',  'apellidos' => 'Campos Valencia',      'dni' => '70100019', 'sexo' => 'F', 'fecha_nacimiento' => '2008-02-20'],
            ['nombres' => 'Franco Daniel',    'apellidos' => 'Navarro Choque',       'dni' => '70100020', 'sexo' => 'M', 'fecha_nacimiento' => '2007-06-08'],
            ['nombres' => 'Bianca Alessandra','apellidos' => 'Velásquez Tapia',      'dni' => '70100021', 'sexo' => 'F', 'fecha_nacimiento' => '2008-11-17'],
            ['nombres' => 'Iker Nicolás',     'apellidos' => 'Contreras Prado',      'dni' => '70100022', 'sexo' => 'M', 'fecha_nacimiento' => '2008-04-02'],
            ['nombres' => 'Samantha Abigail', 'apellidos' => 'Ramos Apaza',          'dni' => '70100023', 'sexo' => 'F', 'fecha_nacimiento' => '2007-12-25'],
            ['nombres' => 'Maximiliano José', 'apellidos' => 'Silva Rondón',         'dni' => '70100024', 'sexo' => 'M', 'fecha_nacimiento' => '2008-08-11'],
            ['nombres' => 'Daniela Patricia', 'apellidos' => 'Paredes Aquino',       'dni' => '70100025', 'sexo' => 'F', 'fecha_nacimiento' => '2008-03-30'],
            ['nombres' => 'Joaquín Eduardo',  'apellidos' => 'Cáceres Montalvo',     'dni' => '70100026', 'sexo' => 'M', 'fecha_nacimiento' => '2007-05-19'],
            ['nombres' => 'Alejandra Isabel', 'apellidos' => 'Ponce Esquivel',       'dni' => '70100027', 'sexo' => 'F', 'fecha_nacimiento' => '2008-09-14'],
            ['nombres' => 'Gael Mathías',     'apellidos' => 'Benítez Carranza',     'dni' => '70100028', 'sexo' => 'M', 'fecha_nacimiento' => '2008-01-06'],
            ['nombres' => 'Mariana Fernanda', 'apellidos' => 'Soto Maldonado',       'dni' => '70100029', 'sexo' => 'F', 'fecha_nacimiento' => '2007-10-28'],
            ['nombres' => 'Liam Alexander',   'apellidos' => 'Huanca Ticona',        'dni' => '70100030', 'sexo' => 'M', 'fecha_nacimiento' => '2008-06-15'],
            ['nombres' => 'Isabella Regina',  'apellidos' => 'Zavala Pineda',        'dni' => '70100031', 'sexo' => 'F', 'fecha_nacimiento' => '2008-07-09'],
            ['nombres' => 'Nicolás Esteban',  'apellidos' => 'Valdivia Cornejo',     'dni' => '70100032', 'sexo' => 'M', 'fecha_nacimiento' => '2007-04-22'],
            ['nombres' => 'Sofía Alejandra',  'apellidos' => 'Miranda Tello',        'dni' => '70100033', 'sexo' => 'F', 'fecha_nacimiento' => '2008-12-01'],
            ['nombres' => 'Santiago Mateo',   'apellidos' => 'Arévalo Vilchez',      'dni' => '70100034', 'sexo' => 'M', 'fecha_nacimiento' => '2008-02-08'],
            ['nombres' => 'Elena Gabriela',   'apellidos' => 'Ccama Huayhua',        'dni' => '70100035', 'sexo' => 'F', 'fecha_nacimiento' => '2007-09-05'],
            ['nombres' => 'Bruno Fernando',   'apellidos' => 'Yupanqui Inga',        'dni' => '70100036', 'sexo' => 'M', 'fecha_nacimiento' => '2008-05-28'],
            ['nombres' => 'Regina Paola',     'apellidos' => 'Alvarado Quiroz',      'dni' => '70100037', 'sexo' => 'F', 'fecha_nacimiento' => '2008-08-17'],
            ['nombres' => 'Facundo Martín',   'apellidos' => 'Colque Apaza',         'dni' => '70100038', 'sexo' => 'M', 'fecha_nacimiento' => '2007-03-12'],
            ['nombres' => 'Micaela Victoria', 'apellidos' => 'Chura Mamani',         'dni' => '70100039', 'sexo' => 'F', 'fecha_nacimiento' => '2008-10-21'],
            ['nombres' => 'Dylan Adriel',     'apellidos' => 'Ochoa Peñaloza',       'dni' => '70100040', 'sexo' => 'M', 'fecha_nacimiento' => '2008-04-14'],
            ['nombres' => 'Abril Solange',    'apellidos' => 'Ticona Catacora',      'dni' => '70100041', 'sexo' => 'F', 'fecha_nacimiento' => '2007-11-07'],
            ['nombres' => 'Damián Renato',    'apellidos' => 'Huayta Condori',       'dni' => '70100042', 'sexo' => 'M', 'fecha_nacimiento' => '2008-06-30'],
        ];

        foreach ($estudiantes as $i => $e) {
            $slug = strtolower(str_replace(' ', '.', explode(' ', $e['nombres'])[0])) . '.' .
                    strtolower(str_replace(' ', '', explode(' ', $e['apellidos'])[0]));

            User::create(array_merge($e, [
                'email' => $slug . '@colegiomp.edu.pe',
                'password' => Hash::make('Estudiante1234'),
                'rol' => 'estudiante',
                'estado' => $i < 40 ? 'activo' : ($i === 40 ? 'inactivo' : 'activo'),
                'telefono' => '9' . str_pad((string)($i + 10), 8, '0', STR_PAD_LEFT),
            ]));
        }
    }
}
