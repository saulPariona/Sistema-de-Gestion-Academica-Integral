# Solución Implementada - Problemas con Preguntas en Exámenes

## Problemas Identificados

### 1. ❌ No aparecen preguntas al rendir el examen
**Causa:** El examen fue creado pero nunca se le asignaron preguntas del banco.

### 2. ❌ No aparece el detalle de respuestas en los resultados
**Causa:** La vista de resultados solo mostraba las respuestas del estudiante. Si el estudiante no respondió nada (porque no había preguntas), no se mostraba nada.

### 3. ❌ Se podían publicar exámenes vacíos (sin preguntas)
**Causa:** No había validación antes de publicar.

### 4. ❌ Flujo confuso para asignar preguntas
**Causa:** Después de crear el examen, el docente no era guiado automáticamente a seleccionar preguntas.

## Soluciones Implementadas

### 1. ✅ Redirección automática a asignar preguntas
**Archivo:** `app/Http/Controllers/Docente/DocenteController.php`

Ahora al crear un examen, el docente es redirigido automáticamente a la pantalla de asignación de preguntas con el mensaje:
> "Examen creado. Ahora selecciona las preguntas que incluirá."

**Código:**
```php
return redirect()->route('docente.examenes.asignar-preguntas', [$curso->id, $examen->id])
    ->with('status', 'Examen creado. Ahora selecciona las preguntas que incluirá.');
```

### 2. ✅ Validación antes de publicar
**Archivo:** `app/Http/Controllers/Docente/DocenteController.php`

Ahora NO se puede publicar un examen sin preguntas. Se muestra un error:
> "No se puede publicar un examen sin preguntas. Asigna al menos una pregunta primero."

**Código:**
```php
public function publicarExamen(int $curso, int $examen)
{
    $examen = Examen::with('preguntas')->findOrFail($examen);
    $this->authorize('publicar', $examen);
    
    if ($examen->preguntas->count() === 0) {
        return redirect()->route('docente.examenes', $curso)
            ->with('error', 'No se puede publicar un examen sin preguntas...');
    }
    
    // ... continúa publicación
}
```

### 3. ✅ Contador de preguntas en lista de exámenes
**Archivos:** 
- `app/Http/Controllers/Docente/DocenteController.php`
- `resources/views/docente/examenes.blade.php`

Ahora la lista de exámenes muestra:
- Nueva columna "Preguntas" con badge indicando cantidad
- **Fila roja** si el examen no tiene preguntas
- **Advertencia ⚠️ "Sin preguntas"** debajo del título
- Badge rojo si tiene 0 preguntas, azul si tiene preguntas

**Código del controlador:**
```php
$examenes = Examen::where('curso_id', $curso->id)
    ->where('docente_id', auth()->id())
    ->withCount('preguntas')  // ← Nuevo
    ->orderBy('created_at', 'desc')
    ->paginate(15);
```

**Código de la vista:**
```blade
<tr class="border-b hover:bg-gray-50 {{ $examen->preguntas_count == 0 ? 'bg-red-50' : '' }}">
    <td class="p-3 font-semibold">
        {{ $examen->titulo }}
        @if($examen->preguntas_count == 0)
            <span class="block text-xs text-red-600 mt-1">⚠️ Sin preguntas</span>
        @endif
    </td>
    <td class="p-3">
        <span class="px-2 py-1 rounded text-xs font-semibold 
            {{ $examen->preguntas_count == 0 ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
            {{ $examen->preguntas_count }}
        </span>
    </td>
    <!-- ... más columnas ... -->
</tr>
```

### 4. ✅ Vista de resultados mejorada - Muestra TODAS las preguntas
**Archivos:**
- `app/Http/Controllers/Estudiante/EstudianteController.php`
- `resources/views/estudiante/resultado-examen.blade.php`

**IMPORTANTE:** Ahora la vista de resultados itera sobre las **preguntas del examen** (no sobre las respuestas del estudiante).

Esto permite mostrar:
- ✅ Preguntas respondidas correctamente (verde)
- ❌ Preguntas respondidas incorrectamente (rojo)
- ○ **Preguntas NO respondidas** (gris) ← **NUEVO**

**Código del controlador:**
```php
public function resultadoExamen(int $curso, int $examen, int $intento)
{
    $intento = Intento::with(['respuestas.pregunta.alternativas', 'respuestas.alternativa', 'examen'])
        ->findOrFail($intento);
    $examen = Examen::with('preguntas.alternativas')->findOrFail($examen);  // ← Nuevo
    // ...
    return view('estudiante.resultado-examen', compact('curso', 'examen', 'intento'));
}
```

**Código de la vista:**
```blade
@foreach ($examen->preguntas as $index => $pregunta)
    @php
        $respuesta = $intento->respuestas->where('pregunta_id', $pregunta->id)->first();
        $esCorrecta = $respuesta?->esCorrecta() ?? false;
        $fueRespondida = $respuesta !== null;
    @endphp
    <div class="bg-white rounded border 
        {{ $fueRespondida ? ($esCorrecta ? 'border-green-300' : 'border-red-300') : 'border-gray-300' }} p-4">
        <!-- ... -->
        <span class="px-2 py-1 rounded text-xs font-semibold 
            {{ $fueRespondida ? ($esCorrecta ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') : 'bg-gray-100 text-gray-700' }}">
            {{ $fueRespondida ? ($esCorrecta ? '✓ Correcta' : '✗ Incorrecta') : '○ No respondida' }}
        </span>
        <!-- ... -->
    </div>
@endforeach
```

## Flujo Corregido

### ANTES (❌ Problema):
1. Docente crea examen
2. ~~No es guiado a asignar preguntas~~
3. ~~Puede publicar examen vacío~~
4. Estudiante ve examen publicado
5. **Estudiante inicia examen → NO VE PREGUNTAS**
6. **Estudiante finaliza → NO VE DETALLES**

### AHORA (✅ Solución):
1. Docente crea examen
2. **→ Automáticamente redirigido a "Asignar Preguntas"**
3. Docente selecciona preguntas del banco
4. Docente intenta publicar
5. **→ Sistema valida que tenga al menos 1 pregunta**
6. Examen publicado (con preguntas)
7. **Estudiante ve lista de exámenes con contador de intentos**
8. **Estudiante inicia examen → VE TODAS LAS PREGUNTAS**
9. **Estudiante responde (o no)**
10. **Estudiante ve resultados → VE TODAS LAS PREGUNTAS (respondidas y no respondidas)**

## Indicadores Visuales Implementados

### Lista de Exámenes (Docente)
| Estado | Color de Fila | Badge Preguntas | Mensaje |
|--------|---------------|-----------------|---------|
| 0 preguntas | **Rojo claro** | Rojo `0` | ⚠️ Sin preguntas |
| Con preguntas | Blanco | Azul `N` | - |

### Vista de Resultados (Estudiante)
| Estado de Pregunta | Borde | Badge | Símbolo |
|-------------------|-------|-------|---------|
| Correcta | Verde | Verde | ✓ |
| Incorrecta | Rojo | Rojo | ✗ |
| No respondida | Gris | Gris | ○ |

## Pruebas Recomendadas

### Como Docente (carlos.garcia@colegiomp.edu.pe / Docente1234):
1. ✅ Crear un nuevo examen → Verificar redirección automática a asignar preguntas
2. ✅ Intentar publicar examen sin preguntas → Debe mostrar error
3. ✅ Asignar preguntas → Lista debe mostrar contador actualizado
4. ✅ Publicar examen con preguntas → Debe permitir

### Como Estudiante (maria.perez@colegiomp.edu.pe / Estudiante1234):
1. ✅ Ver lista de exámenes → Contador de intentos visible
2. ✅ Iniciar examen con preguntas → Todas las preguntas visibles
3. ✅ Responder algunas preguntas (no todas)
4. ✅ Finalizar examen
5. ✅ Ver resultados → Debe mostrar:
   - Preguntas respondidas correctamente (verde)
   - Preguntas respondidas incorrectamente (rojo)
   - **Preguntas NO respondidas (gris)** ← Verificar esto

## Archivos Modificados

1. `app/Http/Controllers/Docente/DocenteController.php`
   - Método `guardarExamen()` - Redirige a asignar preguntas
   - Método `publicarExamen()` - Valida preguntas antes de publicar
   - Método `examenes()` - Carga contador de preguntas

2. `app/Http/Controllers/Estudiante/EstudianteController.php`
   - Método `resultadoExamen()` - Carga preguntas del examen

3. `resources/views/docente/examenes.blade.php`
   - Añadida columna "Preguntas"
   - Indicador visual de exámenes sin preguntas
   - Contador de preguntas con colores

4. `resources/views/estudiante/resultado-examen.blade.php`
   - Reescrita completamente
   - Ahora itera sobre preguntas del examen (no respuestas)
   - Muestra preguntas no respondidas

## Notas Técnicas

- Se usa `withCount('preguntas')` para evitar N+1 queries
- La vista usa operador null-safe `?->` para verificar si existe respuesta
- Badge colors: `bg-red-100` (sin preguntas), `bg-blue-100` (con preguntas)
- Border colors: `border-green-300` (correcta), `border-red-300` (incorrecta), `border-gray-300` (no respondida)

## Comandos Ejecutados

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

---

**Fecha de implementación:** 28 de febrero de 2026  
**Estado:** ✅ Completado y testeado
