# Nalanda
Nalanda Technical test
# 🎟️ Booking API - Prueba Técnica Senior

Servicio backend de reserva de experiencias y gestión de aforo masivo desarrollado bajo los principios de **Diseño Guiado por el Dominio (DDD)**, **Arquitectura Hexagonal**, y **Tipado Estricto** en PHP 8.4 y Symfony 7.

---

## 🏗️ Decisiones de Arquitectura


### Bounded Contexts
1. **Experience**: Gestión de catálogos y proveedores de actividades.
2. **Session**: Programación de fechas, precios y control de aforo total e inmediato.
3. **Booking **: Transacciones de compra, estados y emisión de eventos asíncronos.

### Reglas de Diseño de Capas (Hexagonal)
**Dominio Puro**: Las entidades (`Experience`, `Session`, `Booking`) están totalmente blindadas. No son modelos anémicos. No contienen setters; validan sus propias invariantes en el constructor (`trim`, `empty`, fechas pasadas, aforos negativos) y mutan su estado mediante métodos semánticos de negocio.
**Aplicación (Casos de Uso)**: Clases planas independientes (`ExperienceCreator`, `SessionCreator`, `BookingCreator`, `BookingCanceler`) que orquestan una única acción atómica del sistema.
**Infraestructura**: Puertos adaptados a controladores HTTP de Symfony, repositorios nativos de Doctrine v3 para PostgreSQL y Listeners de eventos centralizados en configuración PHP.

---

## Endpoints de la API (RESTful)

### Experiencias
* **`POST /api/experiences/`** - Registrar una nueva experiencia.

### Sesiones
* **`POST /api/experiences/{experienceId}/sessions/`** - Programar una sesión (Subrecurso REST Jerárquico). 

### Reservas
* **`POST /api/sessions/{sessionId}/bookings/`** - Crear una reserva y decrementar aforo bajo Bloqueo Pesimista.
* **`PATCH /api/bookings/{bookingId}/`** - Modificación parcial de estado. Envía `{"status": "canceled"}` para cancelar la reserva de forma segura y devolver las plazas al aforo de la sesión.

---

## Suite de Pruebas (PHPUnit 10)

Se ha desarrollado una suite **PHPUnit 10** y aislamiento automático mediante transacciones. Se han definido pruebas funcionales y unitarias.


### Ejecución de Pruebas
Lanza los comandos en la terminal de tu contenedor Docker:

```bash
# Limpiar cachés de metadatos y contenedores
php bin/console doctrine:cache:clear-metadata
php bin/console cache:clear --env=test

# Ejecutar la suite completa (Unitarios y Funcionales)
vendor/bin/phpunit
```
### Disclaimer
Han quedado pendientes por testear la cancelación de un evento en sus test funcional, así como algunos de sus tests unitarios.
---

## Otros
Se ha mantenido un nivel de 10 en el PHPStan.