<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'API Strategic Process') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        :root {
            --bg: #f7f8fb;
            --panel: #ffffff;
            --text: #0f172a;
            --muted: #475569;
            --border: #e2e8f0;
            --accent: #5c6ac4;
            --code: #0f172a;
        }
        body.theme-dark {
            --bg: #0b1220;
            --panel: #0f172a;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --border: #1e293b;
            --accent: #8b5cf6;
            --code: #e2e8f0;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(circle at 10% 20%, #eef2ff 0, #eef2ff 20%, transparent 25%),
                        radial-gradient(circle at 90% 20%, #e0f2fe 0, #e0f2fe 18%, transparent 24%),
                        var(--bg);
            color: var(--text);
            transition: background 0.3s ease, color 0.3s ease;
            min-height: 100vh;
        }
        .page { max-width: 1200px; margin: 0 auto; padding: 32px 20px 48px; }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 24px;
        }
        .brand {
            font-weight: 700;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .pill {
            background: linear-gradient(135deg, #5c6ac4, #22c55e);
            color: #fff;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        button.toggle {
            border: 1px solid var(--border);
            background: var(--panel);
            color: var(--text);
            border-radius: 10px;
            padding: 10px 14px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        button.toggle:hover { border-color: var(--accent); transform: translateY(-1px); }
        h1 {
            font-size: 32px;
            margin: 0 0 10px;
            letter-spacing: -0.02em;
        }
        .muted { color: var(--muted); margin: 0; }
        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .layout { display: grid; gap: 16px; }
        @media (min-width: 900px) { .layout { grid-template-columns: 1.2fr 1fr; } }
        .badge { background: rgba(92,106,196,0.12); color: var(--text); padding: 2px 8px; border-radius: 8px; font-weight: 600; font-size: 12px; }
        .grid { display: grid; gap: 12px; }
        @media (min-width: 720px) { .grid { grid-template-columns: repeat(2, minmax(0,1fr)); } }
        .card {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            background: var(--panel);
        }
        .endpoint {
            font-weight: 700;
            font-size: 14px;
            color: var(--accent);
            margin-bottom: 6px;
        }
        pre {
            background: rgba(15, 23, 42, 0.06);
            color: var(--code);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px;
            overflow-x: auto;
            font-size: 13px;
            line-height: 1.45;
        }
        code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
        details {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            background: var(--panel);
        }
        summary {
            cursor: pointer;
            font-weight: 700;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        summary::-webkit-details-marker { display: none; }
        .tag { background: rgba(92,106,196,0.14); border-radius: 6px; padding: 2px 8px; font-size: 12px; color: var(--text); }
        ul { padding-left: 18px; margin: 6px 0; color: var(--muted); }
        li { margin: 4px 0; }
        .stack { display: grid; gap: 10px; }
    </style>
</head>
<body>
    <div class="page">
        <header>
            <div class="brand">
                <span class="pill">API</span>
                <span>Documentacion de Strategic Process</span>
            </div>
            <button class="toggle" id="themeToggle" type="button">Modo oscuro</button>
        </header>

        <div class="layout">
            <section class="panel">
                <h1>Vision general</h1>
                <p class="muted">Todas las rutas se sirven bajo <code>/api</code>. Autentica con <code>Bearer {token}</code> tras iniciar sesion.</p>
                <div class="stack">
                    <div class="card">
                        <div class="endpoint">Autenticacion</div>
                        <pre><code>POST /api/login
Body:
{
  "email": "user@example.com",
  "password": "secret"
}
Respuesta 200:
{
  "user": {...},
  "token": "xxxx",
  "token_type": "Bearer"
}</code></pre>
                        <p class="muted">Usa el token en el header <code>Authorization: Bearer</code>. <code>POST /api/logout</code> invalida el token. <code>GET /api/user</code> devuelve el usuario autenticado.</p>
                    </div>
                    <div class="card">
                        <div class="endpoint">Paginacion</div>
                        <p class="muted">Las rutas <code>index</code> devuelven objetos paginados.</p>
                        <pre><code>{
  "data": [...],
  "links": { "next": null, "prev": null },
  "meta": { "current_page": 1, "per_page": 20, ... }
}</code></pre>
                    </div>
                </div>
            </section>

            <section class="panel">
                <h1>Entrada y salida</h1>
                <p class="muted">Los ejemplos muestran campos requeridos/opcionales y la forma del JSON de respuesta.</p>
                <div class="stack">
                    <div class="card">
                        <div class="endpoint">Sunat RUC</div>
                        <pre><code>GET /api/sunat/ruc/{ruc}
Respuesta 200:
{
  "success": true,
  "ruc": "20123456789",
  "nombre": "...",
  "direccion": "...",
  "estado": "...",
  "condicion": "..."
}</code></pre>
                        <p class="muted">Requiere token APIPERU_TOKEN configurado en <code>.env</code>.</p>
                    </div>
                    <div class="card">
                        <div class="endpoint">Quality standards</div>
                        <pre><code>GET /api/strategic/quality-standards
Respuesta 200:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "...",
      "category": "...",
      "target_score": 4.5,
      "current_score": 4.2,
      "target_roles": ["admin", "user"],
      "ratings_count": 3
    }
  ]
}</code></pre>
                        <pre><code>POST /api/strategic/quality-standards/{id}/rate
Body:
{ "score": 1-5, "comment": "opcional" }
Respuesta 200:
{ "success": true, "message": "Gracias! ..." }</code></pre>
                    </div>
                </div>
            </section>
        </div>

        <div class="stack" style="margin-top: 16px;">
            <details open>
                <summary>Contenido estrategico <span class="tag">/api/strategic-contents</span></summary>
                <div class="grid">
                    <div class="card">
                        <div class="endpoint">Crear</div>
                        <pre><code>POST /api/strategic-contents
{
  "type": "mission | vision | objective | plan",
  "content": "texto"
}</code></pre>
                        <p class="muted">Devuelve el registro creado (201). <code>PATCH /{id}</code> acepta los mismos campos. <code>DELETE /{id}</code> responde 204.</p>
                    </div>
                    <div class="card">
                        <div class="endpoint">Listar y ver</div>
                        <pre><code>GET /api/strategic-contents
Respuesta: objeto paginado de contenidos

GET /api/strategic-contents/{id}
Respuesta: contenido simple</code></pre>
                    </div>
                </div>
            </details>

            <details open>
                <summary>Planes y objetivos <span class="tag">/api/strategic-plans</span> | <span class="tag">/api/strategic-objectives</span></summary>
                <div class="grid">
                    <div class="card">
                        <div class="endpoint">Plan estrategico</div>
                        <pre><code>POST /api/strategic-plans
{
  "title": "string",
  "description": "string",
  "start_date": "YYYY-MM-DD",
  "end_date": "YYYY-MM-DD",
  "status": "string",
  "user_id": 1
}</code></pre>
                        <pre><code>Respuesta 201:
{
  "id": 10,
  "title": "...",
  "description": "...",
  "start_date": "...",
  "end_date": "...",
  "status": "...",
  "user_id": 1
}</code></pre>
                        <p class="muted">GET lista paginada (con <code>objectives_count</code> e <code>iniciatives_count</code>). <code>GET /{id}</code> devuelve conteos. <code>PATCH /{id}</code> actualiza campos. <code>DELETE /{id}</code> 204.</p>
                    </div>
                    <div class="card">
                        <div class="endpoint">Objetivo estrategico</div>
                        <pre><code>POST /api/strategic-objectives
{
  "plan_id": 10,
  "title": "string",
  "description": "string",
  "goal_value": 100,
  "user_id": 1,
  "weight": 20,
  "kpis": [1, 2] // opcional
}</code></pre>
                        <pre><code>Respuesta 201:
{
  "id": 5,
  "plan_id": 10,
  "title": "...",
  "description": "...",
  "goal_value": 100,
  "user_id": 1,
  "weight": 20,
  "kpis": [1,2],
  "plan": {...},
  "user": {...}
}</code></pre>
                        <p class="muted"><code>GET /{id}</code> devuelve el objetivo + <code>kpis-contend</code> (arreglo de KpiGoal) y relaciones. <code>PATCH /{id}</code> acepta los mismos campos como opcionales. <code>DELETE /{id}</code> 204.</p>
                    </div>
                </div>
            </details>

            <details open>
                <summary>Iniciativas y evaluaciones <span class="tag">/api/iniciatives</span> | <span class="tag">/api/iniciative-evaluations</span></summary>
                <div class="grid">
                    <div class="card">
                        <div class="endpoint">Iniciativa</div>
                        <pre><code>POST /api/iniciatives
{
  "title": "string",
  "plan_id": 10,
  "summary": "string",
  "user_id": 1,
  "status": "propuesta | en_revision | aprobada | rechazada | en_ejecucion | finalizada | evaluada",
  "start_date": "YYYY-MM-DD",
  "end_date": "YYYY-MM-DD",
  "estimated_impact": "string"
}</code></pre>
                        <p class="muted">Status por defecto: <code>propuesta</code>. <code>GET /api/iniciatives?status=&plan_id=&user_id=</code> filtra. <code>PATCH /{id}</code> valida transiciones permitidas. <code>DELETE /{id}</code> solo si no hay evaluaciones (422 en caso contrario).</p>
                        <pre><code>POST /api/iniciatives/{id}/transition
{ "status": "en_revision" }
// Devuelve la iniciativa con plan, user, evaluations</code></pre>
                    </div>
                    <div class="card">
                        <div class="endpoint">Evaluacion de iniciativa</div>
                        <pre><code>POST /api/iniciative-evaluations
{
  "iniciative_id": 5,
  "evaluator_user": 3,
  "summary": "texto",
  "score": 0-100,
  "document_id": 12 // opcional
}</code></pre>
                        <p class="muted">Si la iniciativa esta <code>finalizada</code>, pasa a <code>evaluada</code>. <code>GET /api/iniciative-evaluations?iniciative_id=</code> lista paginada con relaciones (<code>iniciative</code>, <code>evaluator</code>, <code>document</code>). <code>PATCH /{id}</code> y <code>DELETE /{id}</code> actualizan o eliminan.</p>
                    </div>
                </div>
            </details>

            <details open>
                <summary>Organizaciones y acuerdos <span class="tag">/api/organizations</span> | <span class="tag">/api/agreements</span></summary>
                <div class="grid">
                    <div class="card">
                        <div class="endpoint">Organizacion</div>
                        <pre><code>POST /api/organizations
{
  "ruc": "string unico",
  "name": "string",
  "type": "string",
  "contact_phone": "string opcional",
  "contact_email": "email opcional"
}</code></pre>
                        <p class="muted">Lista paginada: <code>GET /api/organizations</code>. <code>GET /{id}</code> devuelve la organizacion. <code>PATCH /{id}</code> permite los mismos campos. <code>DELETE /{id}</code> 204.</p>
                    </div>
                    <div class="card">
                        <div class="endpoint">Acuerdo</div>
                        <pre><code>POST /api/agreements
{
  "organization_id": 1,
  "name": "string",
  "start_date": "YYYY-MM-DD",
  "renewal_date": "YYYY-MM-DD | null",
  "purpose": "string | null",
  "status": "string"
}</code></pre>
                        <p class="muted">Respuestas incluyen la relacion <code>organization</code>. <code>GET /api/agreements</code> lista paginada. <code>GET /{id}</code> muestra detalle. <code>PATCH /{id}</code> actualiza. <code>DELETE /{id}</code> 204.</p>
                    </div>
                </div>
            </details>

            <details open>
                <summary>Documentos estrategicos <span class="tag">/api/strategic-documents</span></summary>
                <div class="grid">
                    <div class="card">
                        <div class="endpoint">Subir documento</div>
                        <pre><code>POST /api/strategic-documents
Content-Type: multipart/form-data
Campos:
- name: string (req)
- type: string (opcional)
- category: string (opcional)
- visibility: internal | restricted | public (opcional)
- description: string (opcional)
- file: pdf/doc/docx/png/jpg (<=10MB, req)</code></pre>
                        <pre><code>Respuesta 201:
{
  "message": "Documento guardado correctamente",
  "document": {
    "id": 4,
    "name": "...",
    "visibility": "internal",
    "file_id": 10,
    "file": {
      "secure_url": "...",
      "mime_type": "...",
      ...
    }
  }
}</code></pre>
                        <p class="muted"><code>GET /api/strategic-documents</code> devuelve lista paginada. <code>GET /{id}</code> incluye <code>file_url</code>. <code>PATCH /{id}</code> acepta los mismos campos y permite reemplazar el archivo. <code>DELETE /{id}</code> borra documento y archivo (204).</p>
                    </div>
                </div>
            </details>

            <details open>
                <summary>Conversaciones y mensajes <span class="tag">/api/conversations</span> | <span class="tag">/api/messages</span> | <span class="tag">/api/message-files</span></summary>
                <div class="grid">
                    <div class="card">
                        <div class="endpoint">Conversacion</div>
                        <pre><code>POST /api/conversations
{ "name": "string" }</code></pre>
                        <p class="muted">Listar: <code>GET /api/conversations</code> (paginado). Ver: <code>GET /{id}</code>. Actualizar con <code>PATCH /{id}</code>. Eliminar con <code>DELETE /{id}</code> 204.</p>
                    </div>
                    <div class="card">
                        <div class="endpoint">Mensaje</div>
                        <pre><code>POST /api/messages
{
  "conversation_id": 1,
  "user_id": 5,
  "content": "texto"
}</code></pre>
                        <p class="muted">El usuario debe pertenecer a la conversacion o devuelve 422. Respuesta incluye <code>conversation</code> y <code>user</code>. Lista paginada en <code>GET /api/messages</code>. <code>PATCH /{id}</code> valida pertenencia. <code>DELETE /{id}</code> 204.</p>
                    </div>
                    <div class="card">
                        <div class="endpoint">Archivo de mensaje</div>
                        <pre><code>POST /api/message-files
{
  "message_id": 1,
  "type": "mime o etiqueta",
  "path": "ruta/archivo"
}</code></pre>
                        <p class="muted">Responde con la relacion <code>message</code>. <code>GET /api/message-files</code> lista paginada; <code>GET /{id}</code> muestra el archivo; <code>PATCH /{id}</code> actualiza; <code>DELETE /{id}</code> 204.</p>
                    </div>
                </div>
            </details>

            <details open>
                <summary>Candidatos <span class="tag">/api/candidates</span></summary>
                <p class="muted">La ruta esta declarada (<code>apiResource</code> excepto <code>update</code> y <code>edit</code>), pero el controlador aun no implementa logica. Considera completarlo antes de usarlo en produccion.</p>
            </details>
        </div>
    </div>

    <script>
        (function () {
            const toggle = document.getElementById('themeToggle');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const stored = localStorage.getItem('theme');
            const initial = stored || (prefersDark ? 'dark' : 'light');

            function setTheme(mode) {
                document.body.classList.remove('theme-light', 'theme-dark');
                document.body.classList.add(`theme-${mode}`);
                toggle.textContent = mode === 'dark' ? 'Modo claro' : 'Modo oscuro';
                localStorage.setItem('theme', mode);
            }

            setTheme(initial);

            toggle.addEventListener('click', () => {
                const next = document.body.classList.contains('theme-dark') ? 'light' : 'dark';
                setTheme(next);
            });
        })();
    </script>
</body>
</html>
