<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->command->info('  → Creando 2 administradores...');
        $this->crearAdministradores();

        $this->command->info('  → Creando 50 docentes (8 principales + 42 adicionales)...');
        $this->crearDocentesPrincipales();
        $this->crearDocentesAdicionales();

        $this->command->info('  → Creando 1000 estudiantes (42 explícitos + 958 masivos)...');
        $this->crearEstudiantesExplicitos();
        $this->crearEstudiantesMasivos();
    }

    private function crearAdministradores(): void
    {
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
    }

    /**
     * 8 docentes principales (uno por materia del currículo preuniversitario).
     */
    private function crearDocentesPrincipales(): void
    {
        $docentes = [
            ['nombres' => 'Carlos Eduardo',    'apellidos' => 'García López',        'dni' => '12345678', 'email' => 'carlos.garcia@colegiomp.edu.pe',  'especialidad' => 'Álgebra',                 'grado_academico' => 'Magíster en Matemática Pura',            'sexo' => 'M', 'telefono' => '987654321', 'fecha_nacimiento' => '1985-06-20', 'direccion' => 'Jr. Lampa 456, Cercado de Lima'],
            ['nombres' => 'Ana Lucía',         'apellidos' => 'Martínez Rojas',      'dni' => '23456789', 'email' => 'ana.martinez@colegiomp.edu.pe',    'especialidad' => 'Aritmética',              'grado_academico' => 'Licenciada en Matemáticas',              'sexo' => 'F', 'telefono' => '987654322', 'fecha_nacimiento' => '1988-11-05', 'direccion' => 'Calle Los Olivos 234, San Isidro'],
            ['nombres' => 'Miguel Ángel',      'apellidos' => 'Torres Mendoza',      'dni' => '34567890', 'email' => 'miguel.torres@colegiomp.edu.pe',   'especialidad' => 'Geometría',               'grado_academico' => 'Magíster en Didáctica de la Matemática', 'sexo' => 'M', 'telefono' => '987654323', 'fecha_nacimiento' => '1982-01-15', 'direccion' => 'Av. Brasil 780, Jesús María'],
            ['nombres' => 'Patricia Carmen',   'apellidos' => 'Vargas Castillo',     'dni' => '45678901', 'email' => 'patricia.vargas@colegiomp.edu.pe',  'especialidad' => 'Trigonometría',            'grado_academico' => 'Licenciada en Ciencias Matemáticas',     'sexo' => 'F', 'telefono' => '987654324', 'fecha_nacimiento' => '1990-07-28', 'direccion' => 'Av. Universitaria 1200, San Martín de Porres'],
            ['nombres' => 'Roberto Jesús',     'apellidos' => 'Chávez Sánchez',      'dni' => '56789012', 'email' => 'roberto.chavez@colegiomp.edu.pe',   'especialidad' => 'Física',                  'grado_academico' => 'Doctor en Física',                       'sexo' => 'M', 'telefono' => '987654325', 'fecha_nacimiento' => '1979-09-03', 'direccion' => 'Calle Las Begonias 345, Miraflores'],
            ['nombres' => 'Claudia Alejandra', 'apellidos' => 'Ríos Fernández',      'dni' => '67890123', 'email' => 'claudia.rios@colegiomp.edu.pe',     'especialidad' => 'Química',                 'grado_academico' => 'Magíster en Química Analítica',          'sexo' => 'F', 'telefono' => '987654326', 'fecha_nacimiento' => '1987-04-17', 'direccion' => 'Jr. Puno 678, Breña'],
            ['nombres' => 'Fernando José',     'apellidos' => 'Medina Paredes',      'dni' => '78901234', 'email' => 'fernando.medina@colegiomp.edu.pe',   'especialidad' => 'Razonamiento Matemático', 'grado_academico' => 'Licenciado en Educación Matemática',     'sexo' => 'M', 'telefono' => '987654327', 'fecha_nacimiento' => '1984-12-09', 'direccion' => 'Av. Colonial 2100, Callao'],
            ['nombres' => 'Lucía Esperanza',   'apellidos' => 'Palacios Huerta',     'dni' => '89012345', 'email' => 'lucia.palacios@colegiomp.edu.pe',    'especialidad' => 'Razonamiento Verbal',     'grado_academico' => 'Magíster en Lingüística',                'sexo' => 'F', 'telefono' => '987654328', 'fecha_nacimiento' => '1986-08-22', 'direccion' => 'Calle Schell 456, Miraflores'],
        ];

        $passwordHash = Hash::make('Docente1234');

        foreach ($docentes as $d) {
            User::create(array_merge($d, [
                'password' => $passwordHash,
                'rol' => 'docente',
                'estado' => 'activo',
            ]));
        }
    }

    /**
     * 42 docentes adicionales con nombres peruanos realistas.
     * Distribución: 40 activos, 2 inactivos.
     * Cada uno con especialidad rotativa entre las 8 materias.
     */
    private function crearDocentesAdicionales(): void
    {
        $especialidades = [
            'Álgebra', 'Aritmética', 'Geometría', 'Trigonometría',
            'Física', 'Química', 'Razonamiento Matemático', 'Razonamiento Verbal',
        ];

        $grados = [
            'Licenciado(a) en Educación', 'Licenciado(a) en Matemáticas',
            'Magíster en Educación', 'Magíster en Ciencias',
            'Doctor(a) en Educación', 'Licenciado(a) en Física',
            'Licenciado(a) en Comunicaciones',
        ];

        $nombresM = [
            'Andrés Felipe', 'Jorge Luis', 'Héctor Raúl', 'Luis Fernando', 'Pedro Pablo',
            'Óscar Alejandro', 'Julio César', 'Enrique Manuel', 'Víctor Hugo', 'Marco Antonio',
            'Daniel Eduardo', 'Ricardo Alonso', 'Gustavo Adolfo', 'Iván Darío', 'Alberto José',
            'Sergio Armando', 'Raúl Ernesto', 'Walter Jesús', 'César Augusto', 'Pablo Emilio',
            'Gonzalo Rafael',
        ];

        $nombresF = [
            'Carmen Rosa', 'Silvia Patricia', 'Norma Beatriz', 'Gloria María', 'Martha Gladys',
            'Doris Yolanda', 'Luz Marina', 'Teresa Juana', 'Nelly Haydée', 'Blanca Flor',
            'Gladys Aurora', 'Pilar Sofía', 'Elena Beatriz', 'Rita Isabel', 'Sonia Margarita',
            'Juana Catalina', 'Graciela Inés', 'Rosa Elvira', 'Angela Patricia', 'Miriam Ruth',
            'Olga Susana',
        ];

        $apellidosPool = [
            'Quispe Mamani', 'Condori Huanca', 'Flores Espinoza', 'Rivera Mendoza', 'Aguilar Rojas',
            'Delgado Ortega', 'Bustamante Díaz', 'Solórzano Paz', 'Campos Valencia', 'Tapia Navarro',
            'Velásquez Choque', 'Contreras Ramos', 'Apaza Silva', 'Rondón Paredes', 'Ponce Esquivel',
            'Montalvo Cáceres', 'Aquino Benítez', 'Carranza Soto', 'Pineda Zavala', 'Cornejo Valdivia',
            'Tello Miranda', 'Vilchez Arévalo', 'Huayhua Ccama', 'Inga Yupanqui', 'Quiroz Alvarado',
            'Ochoa Peñaloza', 'Catacora Ticona', 'Maldonado Soto', 'Prado Campos', 'Navarro Choque',
            'Salinas Huayta', 'Romero Cruz', 'Figueroa Abad', 'Montoya Suárez', 'Lara Bravo',
            'Vega Alarcón', 'Heredia Cárdenas', 'Santana Porras', 'Luna Cevallos', 'Paz Barriga',
            'Rosas Pizarro', 'Zamora Cueto',
        ];

        $distritos = [
            'Cercado de Lima', 'San Juan de Lurigancho', 'San Martín de Porres', 'Ate', 'Comas',
            'Villa El Salvador', 'Villa María del Triunfo', 'Los Olivos', 'Puente Piedra',
            'Santiago de Surco', 'Chorrillos', 'Santa Anita', 'Carabayllo', 'Independencia',
            'El Agustino', 'La Victoria', 'Rímac', 'Breña', 'Jesús María', 'Miraflores',
        ];

        $vias = ['Av.', 'Jr.', 'Calle', 'Psje.'];
        $calles = [
            'Túpac Amaru', 'Los Pinos', 'San Martín', 'Bolognesi', 'Grau',
            'Las Flores', 'Los Rosales', 'La Marina', 'Universitaria', 'Colonial',
        ];

        $passwordHash = Hash::make('Docente1234');

        for ($i = 0; $i < 42; $i++) {
            $sexo = $i % 2 === 0 ? 'M' : 'F';
            $nombres = $sexo === 'M'
                ? $nombresM[$i % count($nombresM)]
                : $nombresF[$i % count($nombresF)];
            $apellidos = $apellidosPool[$i % count($apellidosPool)];

            $primerNombre = strtolower(Str::ascii(explode(' ', $nombres)[0]));
            $primerApellido = strtolower(Str::ascii(explode(' ', $apellidos)[0]));
            $dniNum = 20100001 + $i;

            $anioNac = 1975 + ($i % 21);
            $mesNac = str_pad(($i % 12) + 1, 2, '0', STR_PAD_LEFT);
            $diaNac = str_pad(($i % 28) + 1, 2, '0', STR_PAD_LEFT);

            User::create([
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'dni' => (string) $dniNum,
                'email' => "{$primerNombre}.{$primerApellido}.{$dniNum}@colegiomp.edu.pe",
                'password' => $passwordHash,
                'rol' => 'docente',
                'estado' => $i < 40 ? 'activo' : 'inactivo',
                'sexo' => $sexo,
                'especialidad' => $especialidades[$i % count($especialidades)],
                'grado_academico' => $grados[$i % count($grados)],
                'telefono' => '9' . str_pad((string) (60000 + $i), 8, '0', STR_PAD_LEFT),
                'fecha_nacimiento' => "{$anioNac}-{$mesNac}-{$diaNac}",
                'direccion' => $vias[$i % count($vias)] . ' ' . $calles[$i % count($calles)] . ' ' . (($i + 1) * 100 + 50) . ', ' . $distritos[$i % count($distritos)],
            ]);
        }
    }

    /**
     * 42 estudiantes explícitos originales (con datos deterministas).
     */
    private function crearEstudiantesExplicitos(): void
    {
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

        $passwordHash = Hash::make('Estudiante1234');

        foreach ($estudiantes as $i => $e) {
            $nombre = strtolower(Str::ascii(explode(' ', $e['nombres'])[0]));
            $apellido = strtolower(Str::ascii(explode(' ', $e['apellidos'])[0]));

            User::create(array_merge($e, [
                'email' => "{$nombre}.{$apellido}@colegiomp.edu.pe",
                'password' => $passwordHash,
                'rol' => 'estudiante',
                'estado' => $i < 40 ? 'activo' : ($i === 40 ? 'inactivo' : 'activo'),
                'telefono' => '9' . str_pad((string) ($i + 10), 8, '0', STR_PAD_LEFT),
            ]));
        }
    }

    /**
     * 958 estudiantes masivos con datos peruanos realistas.
     *
     * Distribución de estados:
     *   - 930 activos (para matrículas y exámenes)
     *   - 18 inactivos (se visualizan en gestión de usuarios)
     *   - 10 bloqueados (para demostrar el sistema de seguridad)
     *
     * Se usa insert() en lotes de 250 para rendimiento óptimo.
     */
    private function crearEstudiantesMasivos(): void
    {
        $nombresM = [
            'Ángel David', 'Bryan Kevin', 'Carlos Alberto', 'Daniel Enrique', 'Eduardo Miguel',
            'Felipe Andrés', 'Gabriel Alonso', 'Hugo César', 'Iván Marcelo', 'Jean Pierre',
            'Kevin Alexander', 'Luis Ángel', 'Mario Andrés', 'Néstor Fabián', 'Omar Renato',
            'Paolo Sebastián', 'Rafael Joaquín', 'Samuel Alejandro', 'Tomás Ignacio', 'Ulises Leonardo',
            'Víctor Manuel', 'Williams José', 'Ximeno André', 'Yair Gonzalo', 'Zacarías Abel',
            'Adrián Marcelo', 'Benjamín Luca', 'Christian Joel', 'Dominic Esteban', 'Esteban Patricio',
            'Fabricio Alonso', 'Gianfranco Pier', 'Hans Mauricio', 'Italo Renato', 'Josué Emanuel',
            'Klaus Fernando', 'Leandro Matías', 'Mathias Franco', 'Nicolás Andrés', 'Óscar Gabriel',
            'Patrick Emilio', 'Renato Alonso', 'Steven Rodrigo', 'Tadeo Valentín', 'Uriel Mathías',
            'Vicente Emiliano', 'Warren Sebastián', 'Xander David', 'Yael Adrián', 'Zaid Emmanuel',
        ];

        $nombresF = [
            'Alejandra Sofía', 'Brenda Lucía', 'Carolina Andrea', 'Diana Paola', 'Emily Valentina',
            'Fernanda Nicole', 'Gabriela Alejandra', 'Hannah Isabella', 'Ivana Celeste', 'Jazmín Antonella',
            'Katherine Milagros', 'Lorena Beatriz', 'Melany Cristina', 'Natalia Fernanda', 'Olga Patricia',
            'Paola Daniela', 'Quiara Renata', 'Romina Alessandra', 'Silvana Jimena', 'Tatiana Valeria',
            'Úrsula Mariana', 'Vanessa Brigitte', 'Wendy Samantha', 'Xiomara Lucero', 'Yamileth Ariana',
            'Zoila Marisol', 'Abigail Catalina', 'Belén Mikaela', 'Cielo Esperanza', 'Dafne Valentina',
            'Estrella Camila', 'Fiorella Luciana', 'Génesis Ariana', 'Hillary Johana', 'Iris Guadalupe',
            'Julissa Mercedes', 'Kiara Stephany', 'Luana Victoria', 'Mía Antonella', 'Naomi Lucero',
            'Oriana Paz', 'Pía Constanza', 'Rafaela Sol', 'Samira Alejandra', 'Thalia Milagros',
            'Uma Valentina', 'Valery Carolina', 'Wanda Julieta', 'Ximena Graciela', 'Yaretzi Solange',
        ];

        $apellidos1 = [
            'Quispe', 'Mamani', 'Condori', 'Flores', 'Huanca', 'Apaza', 'Chura', 'Ccama',
            'López', 'García', 'Martínez', 'Rodríguez', 'Sánchez', 'Ramírez', 'Torres',
            'Hernández', 'Díaz', 'Morales', 'Vásquez', 'Castro', 'Vargas', 'Rojas',
            'Herrera', 'Gutiérrez', 'Mendoza', 'Espinoza', 'Salazar', 'Ortega', 'Ramos',
            'Delgado', 'Rivera', 'Aguilar', 'Campos', 'Navarro', 'Contreras', 'Velásquez',
            'Ponce', 'Cáceres', 'Benítez', 'Carranza', 'Zavala', 'Miranda', 'Arévalo',
            'Yupanqui', 'Alvarado', 'Colque', 'Ochoa', 'Ticona', 'Huayta', 'Paredes',
        ];

        $apellidos2 = [
            'Mamani', 'Quispe', 'Huanca', 'Condori', 'Flores', 'Apaza', 'Torres', 'López',
            'García', 'Sánchez', 'Ramírez', 'Morales', 'Castañeda', 'Villanueva', 'Mendoza',
            'Espinoza', 'Vega', 'Delgado', 'Córdova', 'Aguilar', 'Bustamante', 'Solórzano',
            'Valencia', 'Choque', 'Tapia', 'Prado', 'Ramos', 'Silva', 'Rondón', 'Aquino',
            'Montalvo', 'Esquivel', 'Soto', 'Maldonado', 'Ticona', 'Pineda', 'Cornejo',
            'Tello', 'Vilchez', 'Huayhua', 'Inga', 'Quiroz', 'Peñaloza', 'Catacora',
            'Barriga', 'Cueto', 'Pizarro', 'Porras', 'Cevallos', 'Suárez',
        ];

        $passwordHash = Hash::make('Estudiante1234');
        $batch = [];
        $now = now();

        for ($i = 0; $i < 958; $i++) {
            $sexo = $i % 2 === 0 ? 'M' : 'F';
            $nombres = $sexo === 'M'
                ? $nombresM[$i % count($nombresM)]
                : $nombresF[$i % count($nombresF)];

            $ap1 = $apellidos1[($i * 7 + 3) % count($apellidos1)];
            $ap2 = $apellidos2[($i * 11 + 5) % count($apellidos2)];
            $apellidos = "{$ap1} {$ap2}";

            $dniNum = 70200001 + $i;

            // Fechas de nacimiento distribuidas entre 2007-2009
            $anioNac = 2007 + ($i % 3);
            $mesNac = str_pad(($i % 12) + 1, 2, '0', STR_PAD_LEFT);
            $diaNac = str_pad(($i % 28) + 1, 2, '0', STR_PAD_LEFT);

            $primerNombre = strtolower(Str::ascii(explode(' ', $nombres)[0]));
            $primerApellido = strtolower(Str::ascii($ap1));

            // 930 activos | 10 bloqueados (930-939) | 18 inactivos (940-957)
            $estado = 'activo';
            if ($i >= 940) {
                $estado = 'inactivo';
            } elseif ($i >= 930) {
                $estado = 'bloqueado';
            }

            $batch[] = [
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'dni' => (string) $dniNum,
                'email' => "{$primerNombre}.{$primerApellido}.{$dniNum}@colegiomp.edu.pe",
                'password' => $passwordHash,
                'rol' => 'estudiante',
                'estado' => $estado,
                'sexo' => $sexo,
                'fecha_nacimiento' => "{$anioNac}-{$mesNac}-{$diaNac}",
                'telefono' => '9' . str_pad((string) (100000 + $i), 8, '0', STR_PAD_LEFT),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Insertar en lotes de 250
            if (count($batch) >= 250) {
                User::insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            User::insert($batch);
        }
    }
}
