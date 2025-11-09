<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# API — Módulo de **Procesos Estratégicos (Grupo 05)**

Esta API expone operaciones **CRUD** sobre los recursos del módulo de Procesos Estratégicos. Usa **Laravel Sanctum** (autenticación por token) y **spatie/laravel-permission** (autorización por permisos).

> Base URL: `/api`  
> Formato: `application/json`  
> Paginación: `paginate(20)` (entrega `data`, `links`, `meta`)

---

## 1) Autenticación (Sanctum)

- Tipo: Bearer Token (cabecera `Authorization: Bearer <TOKEN>`).
- Los tokens personales pueden incluir *abilities*, opcionales si utilizas permisos con Spatie.

**Ejemplo**
```bash
curl -H "Authorization: Bearer <TOKEN>" -H "Accept: application/json" https://tu-dominio.test/api/organizations
```

---

## 2) Autorización (Spatie Permission)

Permisos sugeridos por recurso/acción:

- `strategic_contents.view|create|update|delete`
- `organizations.view|create|update|delete`
- `agreements.view|create|update|delete`
- `strategic_documents.view|create|update|delete`
- `conversations.view|create|update|delete`
- `conversation_users.view|create|update|delete`
- `messages.view|create|update|delete`
- `message_files.view|create|update|delete`

Asigna estos permisos a **roles** (p. ej., `admin`, `editor`, `viewer`).

---

## 3) Recursos y Endpoints

> Todas las rutas están protegidas con `auth:sanctum`. Los permisos se verifican por acción.

### 3.1 Strategic Contents
Planeación estratégica: **misión, visión, objetivos, planes**.  
**Campos**: `type` (`mission|vision|objective|plan`), `content`.

- `GET /strategic-contents` — lista (permiso `strategic_contents.view`)
- `GET /strategic-contents/{id}` — detalle (`strategic_contents.view`)
- `POST /strategic-contents` — crear (`strategic_contents.create`)
- `PUT /strategic-contents/{id}` — actualizar (`strategic_contents.update`)
- `DELETE /strategic-contents/{id}` — eliminar (`strategic_contents.delete`)

**Crear**
```bash
curl -X POST https://tu-dominio.test/api/strategic-contents -H "Authorization: Bearer <TOKEN>" -H "Content-Type: application/json" -d '{"type":"mission","content":"Brindar formación técnica de alta calidad..."}'
```

---

### 3.2 Organizations
Entidades externas (universidades, empresas, gobierno, asociaciones).  
**Campos**: `ruc` (único), `name`, `type`, `contact_phone`, `contact_email`.

- `GET /organizations` — lista (`organizations.view`)
- `GET /organizations/{id}` — detalle (`organizations.view`)
- `POST /organizations` — crear (`organizations.create`)
- `PUT /organizations/{id}` — actualizar (`organizations.update`)
- `DELETE /organizations/{id}` — eliminar (`organizations.delete`)

---

### 3.3 Agreements
Convenios/alianzas con organizaciones.  
**Campos**: `organization_id` (FK), `name`, `start_date`, `renewal_date` (opcional), `purpose`, `status` (ej.: `vigente`, `en evaluación`).

- `GET /agreements` — lista **con** `organization` (`agreements.view`)
- `GET /agreements/{id}` — detalle **con** `organization` (`agreements.view`)
- `POST /agreements` — crear (`agreements.create`)
- `PUT /agreements/{id}` — actualizar (`agreements.update`)
- `DELETE /agreements/{id}` — eliminar (`agreements.delete`)

---

### 3.4 Strategic Documents
Documentos/evidencias estratégicas (actas, políticas, KPIs).  
**Campos**: `name`, `path` (ruta/URL en tu almacenamiento), `type`, `description`.

- `GET /strategic-documents` — lista (`strategic_documents.view`)
- `GET /strategic-documents/{id}` — detalle (`strategic_documents.view`)
- `POST /strategic-documents` — crear (`strategic_documents.create`)
- `PUT /strategic-documents/{id}` — actualizar (`strategic_documents.update`)
- `DELETE /strategic-documents/{id}` — eliminar (`strategic_documents.delete`)

> **Nota**: la subida del archivo se hace fuera de este endpoint; aquí se registra la ruta (`path`).

---

### 3.5 Conversations
Hilos internos por línea estratégica/tema.  
**Campos**: `name`.

- `GET /conversations` — lista (`conversations.view`)
- `GET /conversations/{id}` — detalle (`conversations.view`)
- `POST /conversations` — crear (`conversations.create`)
- `PUT /conversations/{id}` — actualizar (`conversations.update`)
- `DELETE /conversations/{id}` — eliminar (`conversations.delete`)

---

### 3.6 Conversation Users
Participantes asignados a una conversación.  
**Campos**: `conversation_id` (FK), `user_id` (FK→`users`).

- `GET /conversation-users` — lista (`conversation_users.view`)
- `GET /conversation-users/{id}` — detalle (`conversation_users.view`)
- `POST /conversation-users` — crear (`conversation_users.create`)
- `PUT /conversation-users/{id}` — actualizar (`conversation_users.update`)
- `DELETE /conversation-users/{id}` — eliminar (`conversation_users.delete`)

> Requiere usuarios existentes en `users`.

---

### 3.7 Messages
Mensajes dentro de una conversación.  
**Campos**: `conversation_id` (FK), `user_id` (FK), `content`.

- `GET /messages` — lista **con** `conversation` y `user` (`messages.view`)
- `GET /messages/{id}` — detalle **con** `conversation` y `user` (`messages.view`)
- `POST /messages` — crear (`messages.create`)
- `PUT /messages/{id}` — actualizar (`messages.update`)
- `DELETE /messages/{id}` — eliminar (`messages.delete`)

---

### 3.8 Message Files
Archivos adjuntos a mensajes.  
**Campos**: `message_id` (FK), `type` (ej.: `archivo`), `path`.

- `GET /message-files` — lista **con** `message` (`message_files.view`)
- `GET /message-files/{id}` — detalle **con** `message` (`message_files.view`)
- `POST /message-files` — crear (`message_files.create`)
- `PUT /message-files/{id}` — actualizar (`message_files.update`)
- `DELETE /message-files/{id}` — eliminar (`message_files.delete`)

---

## 4) Paginación y filtros

- Todas las listas (`index`) devuelven `paginate(20)`.  
- Parámetros: `?page=2`.  
- Filtros adicionales (ej.: `status` en `agreements`) pueden añadirse leyendo `Request` y aplicando `where(...)`.

---

## 5) Respuestas y errores

- **200 OK** (éxito), **201 Created** (creado), **204 No Content** (eliminado)  
- **401 Unauthorized** (sin token), **403 Forbidden** (sin permiso)  
- **404 Not Found** (no existe), **422 Unprocessable Entity** (validación)

**Ejemplo 422**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."]
  }
}
```

---

## 6) Requisitos y puesta en marcha

1. **Sanctum** instalado y configurado (`auth:sanctum` en rutas API).
2. **spatie/laravel-permission** instalado; migraciones + seeder de **roles/permisos**.
3. Usuarios creados y con roles/permisos asignados.
4. Seeders del **Grupo 05** ejecutados si necesitas datos de ejemplo.

**Comandos útiles**
```bash
php artisan migrate
php artisan db:seed --class=StrategicProcessSeeder
php artisan optimize:clear
```

---

## 7) Buenas prácticas

- Restringe escritura a roles con permisos adecuados.
- Usa `FormRequest` para validaciones complejas.
- Registra auditoría si es necesario.
- Para archivos, usa `Storage` y guarda sólo `path` en la BD.

---

## 8) Notas de versión

- **v1**: CRUD básico con relaciones incluidas en `agreements`, `messages` y `message_files`.  
- Roadmap: filtros por columnas, `?include=` para expansión selectiva, ordenación, búsqueda por texto y *soft deletes* donde aplique.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
