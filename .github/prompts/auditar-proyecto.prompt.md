---
description: "Auditoría técnica senior de proyecto Laravel. Analiza completitud, escalabilidad, seguridad, testing y buenas prácticas. Genera diagnóstico con prioridades."
agent: "agent"
tools: ["search", "file"]
---

Actúa como un desarrollador senior con 10+ años de experiencia en Laravel y arquitectura de software.

Realiza una auditoría técnica completa del proyecto Laravel. Analiza los siguientes aspectos y genera un diagnóstico priorizado:

## Aspectos a Auditar

### 1. Arquitectura y Escalabilidad
- Patrón de diseño (MVC, Services, Repositories)
- Separación de responsabilidades en controladores (< 200 líneas ideal)
- Uso de Service Layer, DTOs, Actions
- Inyección de dependencias

### 2. Seguridad
- Rate limiting en login y formularios
- Protección CSRF
- Validación de entrada (Form Requests)
- Políticas de autorización
- Protección contra SQL Injection, XSS
- Gestión de contraseñas y bloqueo de cuentas
- Middleware de seguridad

### 3. Testing
- Cobertura de tests unitarios y de integración
- Tests de Feature para cada flujo principal
- Factories y seeders para testing
- Configuración de PHPUnit

### 4. Infraestructura
- CI/CD (GitHub Actions, pipelines)
- Docker/Containerización
- Variables de entorno (.env.example)
- Logging estructurado
- Queue system para tareas pesadas

### 5. Código y Mantenibilidad
- Localización (i18n)
- Documentación (README, API docs)
- Manejo de errores y excepciones
- Notificaciones (email, in-app)
- Observadores y Eventos

### 6. Frontend
- Accesibilidad (a11y)
- Responsividad
- Performance (lazy loading, caching)
- UX patterns

## Formato de Salida

Para cada hallazgo, clasifica su prioridad:
- **CRÍTICO**: Bloquea producción o es una vulnerabilidad de seguridad
- **IMPORTANTE**: Necesario para un proyecto profesional completo
- **RECOMENDADO**: Mejora la calidad pero no es bloqueante
- **NICE-TO-HAVE**: Mejoras opcionales para escalar

Incluye para cada punto: descripción del problema, impacto, y sugerencia de solución concreta.
