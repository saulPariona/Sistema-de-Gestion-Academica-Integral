# Lista de Verificación del Sistema - ColegioMaxPlanck

## Credenciales de Prueba

### Administrador
- **Email:** admin@colegiomp.edu.pe
- **Contraseña:** Admin1234

### Docente
- **Email:** carlos.garcia@colegiomp.edu.pe
- **Contraseña:** Docente1234

### Estudiante
- **Email:** maria.perez@colegiomp.edu.pe
- **Contraseña:** Estudiante1234

## Pruebas por Rol

### ✅ ADMINISTRADOR

#### Acceso y Navegación
- [ ] Login exitoso con credenciales correctas
- [ ] Redirección al dashboard del administrador
- [ ] Visualización del menú completo (Inicio, Usuarios, Periodos, Cursos, Matrículas, Calificaciones, Auditoría)

#### Gestión de Usuarios
- [ ] Ver lista de usuarios
- [ ] Crear nuevo usuario (docente/estudiante)
- [ ] Editar usuario existente
- [ ] Activar/Desactivar usuario
- [ ] Resetear contraseña de usuario
- [ ] Ver apoderados de estudiante

#### Gestión de Periodos
- [ ] Ver lista de periodos
- [ ] Crear nuevo periodo
- [ ] Editar periodo existente

#### Gestión de Cursos
- [ ] Ver lista de cursos
- [ ] Crear nuevo curso
- [ ] Editar curso existente
- [ ] Asignar docente a curso

#### Gestión de Matrículas
- [ ] Ver lista de matrículas
- [ ] Filtrar matrículas por curso/periodo
- [ ] Crear nueva matrícula (estudiante en curso)

#### Gestión de Apoderados
- [ ] Ver apoderados desde lista de usuarios
- [ ] Agregar nuevo apoderado a estudiante
- [ ] Eliminar apoderado

#### Calificaciones y Auditorías
- [ ] Ver calificaciones globales por examen
- [ ] Filtrar calificaciones por curso/periodo
- [ ] Ver registro de auditoría
- [ ] Buscar en auditorías

### ✅ DOCENTE

#### Acceso y Navegación
- [ ] Login exitoso con credenciales correctas
- [ ] Redirección al dashboard del docente
- [ ] Ver lista de cursos asignados

#### Gestión de Curso
- [ ] Acceder a curso asignado ("Matemática Básica")
- [ ] Ver información del curso
- [ ] Ver lista de estudiantes matriculados
- [ ] Acceso denegado a cursos no asignados

#### Banco de Preguntas
- [ ] Ver banco de preguntas del curso
- [ ] Crear nueva pregunta con alternativas
- [ ] Cargar imagen en pregunta (opcional)
- [ ] Configurar dificultad y puntaje
- [ ] Marcar respuesta correcta (mínimo 4 alternativas, 1 correcta)
- [ ] Editar pregunta existente
- [ ] Eliminar pregunta

#### Gestión de Exámenes
- [ ] Ver lista de exámenes del curso
- [ ] Crear nuevo examen
  - [ ] Configurar título, descripción, puntaje total
  - [ ] Configurar tiempo límite
  - [ ] Configurar fechas de inicio/fin
  - [ ] Configurar intentos permitidos
  - [ ] Activar orden aleatorio de preguntas/alternativas
  - [ ] Configurar visibilidad de resultados
  - [ ] Activar revisión posterior
- [ ] Editar examen (solo si no está cerrado)
- [ ] Asignar preguntas a examen
- [ ] Publicar examen (cambio de estado: creado → publicado)
- [ ] Cerrar examen (cambio de estado: publicado → cerrado)

#### Resultados
- [ ] Ver resultados de examen
- [ ] Ver lista de intentos por estudiante
- [ ] Ver detalle de respuestas de un intento específico
- [ ] Ver respuestas correctas/incorrectas

#### Observaciones
- [ ] Ver observaciones del curso
- [ ] Crear nueva observación para estudiante
- [ ] Filtrar por estudiante

#### Exportación
- [ ] Exportar notas del curso en formato CSV
- [ ] Verificar que el archivo contiene todas las notas y promedios

### ✅ ESTUDIANTE

#### Acceso y Navegación
- [ ] Login exitoso con credenciales correctas
- [ ] Redirección al dashboard del estudiante
- [ ] Ver lista de cursos matriculados

#### Gestión de Curso
- [ ] Acceder a curso en el que está matriculado
- [ ] Ver información del curso y docente
- [ ] Ver exámenes disponibles
- [ ] Acceso denegado a cursos no matriculados

#### Rendir Examen
- [ ] Ver listado de exámenes disponibles
- [ ] Iniciar examen disponible
- [ ] Ver temporizador de tiempo restante
- [ ] Responder preguntas
- [ ] Guardado automático de respuestas (AJAX)
- [ ] Ver contador de preguntas respondidas
- [ ] Finalizar examen
- [ ] Confirmación antes de finalizar
- [ ] Advertencia si quedan preguntas sin responder

#### Resultados de Examen
- [ ] Ver puntaje obtenido
- [ ] Ver nota en escala vigesimal (base 20)
- [ ] Ver estado (Aprobado/Desaprobado)
- [ ] Ver detalle de respuestas (si el examen lo permite)
- [ ] Identificar respuestas correctas/incorrectas
- [ ] Ver respuesta correcta cuando se equivoca

#### Calificaciones
- [ ] Ver calificaciones de todos los cursos
- [ ] Ver notas por examen
- [ ] Ver promedio por curso
- [ ] Ver estado (Aprobado/Desaprobado/Pendiente)

#### Perfil
- [ ] Ver información personal
- [ ] Actualizar teléfono
- [ ] Actualizar dirección
- [ ] Cargar foto de perfil
- [ ] Cambiar contraseña
- [ ] Validación de contraseñas coincidentes

### ✅ AUTENTICACIÓN Y SEGURIDAD

#### Login
- [ ] Login con credenciales válidas
- [ ] Rechazo de credenciales inválidas
- [ ] Incremento de intentos fallidos
- [ ] Bloqueo de cuenta tras 5 intentos fallidos
- [ ] Desbloqueo automático después de 30 minutos

#### Recuperación de Contraseña
- [ ] Solicitar enlace de reseteo
- [ ] Recibir email con token (verificar logs)
- [ ] Resetear contraseña con token válido
- [ ] Rechazo de token inválido/expirado

#### Cambio de Contraseña
- [ ] Cambiar contraseña desde el sistema
- [ ] Validación de contraseña actual
- [ ] Validación de nueva contraseña (min 8 caracteres, mayúscula, minúscula, número)
- [ ] Confirmación de contraseñas coincidentes

#### Sesión e Inactividad
- [ ] Cierre de sesión manual
- [ ] Cierre automático por inactividad (30 minutos)
- [ ] Registro de último acceso
- [ ] Middleware de bloqueo funcional

#### Auditoría
- [ ] Registro de acciones críticas (crear, actualizar, eliminar)
- [ ] Almacenamiento de datos anteriores y nuevos
- [ ] Registro de IP del usuario
- [ ] Timestamp de acciones

### ✅ AUTORIZACIÓN Y PERMISOS

#### Control de Acceso por Rol
- [ ] Administrador: acceso a todas las funcionalidades
- [ ] Docente: acceso solo a cursos asignados
- [ ] Estudiante: acceso solo a cursos matriculados
- [ ] Redirección correcta según rol después del login

#### Policies y Gates
- [ ] CursoPolicy: docente solo ve sus cursos
- [ ] ExamenPolicy: estudiante solo puede rendir exámenes de sus cursos
- [ ] PreguntaPolicy: docente solo edita sus preguntas
- [ ] Verificación de permisos antes de cada acción

### ✅ FUNCIONALIDADES ESPECIALES

#### Exámenes
- [ ] Orden aleatorio de preguntas (si está activado)
- [ ] Orden aleatorio de alternativas (si está activado)
- [ ] Control de intentos permitidos
- [ ] Tiempo límite funcional (temporizador en JavaScript)
- [ ] Finalización automática al terminar el tiempo
- [ ] Calificación automática en base 20
- [ ] Cálculo correcto de puntajes

#### Navegación
- [ ] Navegación libre entre preguntas (si está activado)
- [ ] Restricción de navegación (si está desactivado)
- [ ] Advertencia al intentar abandonar el examen

#### Validaciones
- [ ] Formularios con validación en servidor
- [ ] Mensajes de error en español
- [ ] Mensajes de éxito visibles
- [ ] Prevención de duplicados (DNI, email únicos)

## Problemas Corregidos

### ✅ Políticas de Autorización
- **Problema:** Error 500 al acceder a acciones de docente
- **Causa:** Uso de `where('users.id')` en relaciones belongsToMany causaba ambigüedad
- **Solución:** Cambio a `whereKey()` que es el método correcto de Laravel para filtrar por ID en relaciones
- **Archivos modificados:**
  - `app/Policies/CursoPolicy.php`
  - `app/Policies/ExamenPolicy.php`
  - `app/Http/Controllers/Estudiante/EstudianteController.php`

### ✅ Datos de Prueba
- **Mejora:** Seeder ampliado con datos más completos
- **Incluye:** 2 docentes, 2 estudiantes, 2 cursos, preguntas con alternativas, examen configurado
- **Beneficio:** Permite probar todos los escenarios del sistema

## Comandos Útiles

```bash
# Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Recrear base de datos
php artisan migrate:fresh --seed

# Verificar rutas
php artisan route:list

# Iniciar servidor
php artisan serve
```

## Notas Técnicas

### Tecnologías
- Laravel 12
- PHP 8.2+
- Tailwind CSS v4
- SQLite/MySQL
- Blade Templates

### Arquitectura
- MVC Pattern
- Repository Pattern (service layer para auditoría)
- Policy-based Authorization
- Custom Middleware
- Form Request Validation
- Eloquent ORM con SoftDeletes

### Seguridad
- Contraseñas hasheadas con bcrypt
- CSRF Protection
- SQL Injection Prevention (Eloquent)
- XSS Prevention (Blade escaping)
- Role-based Access Control
- Account Lockout después de intentos fallidos
- Session Timeout
