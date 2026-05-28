# FrontPhp_AppiGenericaPhp - Frontend PHP para Sistema de Facturacion

![PHP Version](https://img.shields.io/badge/PHP-7.0+-blue?logo=php)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.3-purple?logo=bootstrap)
![API](https://img.shields.io/badge/API-REST_cURL-green?logo=curl)
![Architecture](https://img.shields.io/badge/Architecture-MVC_Simplificado-orange)
![License](https://img.shields.io/badge/License-Educativo-lightgrey)

Frontend PHP con Bootstrap que consume la API REST generica (**ApiGenericaPhp**) via cURL. CRUD completo para 10 entidades incluyendo facturacion maestro-detalle.

---

## Tabla de Contenidos

- [Como funciona el sistema completo](#como-funciona-el-sistema-completo)
- [Caracteristicas](#caracteristicas)
- [Arquitectura](#arquitectura)
- [Patrones de Diseno](#patrones-de-diseno)
- [Requisitos](#requisitos)
- [Instalacion](#instalacion)
- [Configuracion](#configuracion)
- [Paginas y Navegacion](#paginas-y-navegacion)
- [ApiService - Servicio de Comunicacion](#apiservice---servicio-de-comunicacion)
- [Facturacion Maestro-Detalle](#facturacion-maestro-detalle)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Conceptos de PHP Usados](#conceptos-de-php-usados)
- [Como Escalar](#como-escalar-este-proyecto)
- [Solucion de Problemas](#solucion-de-problemas-comunes)

---

## Como funciona el sistema completo

Este proyecto es el **frontend** (interfaz web). Existe un proyecto aparte llamado **ApiGenericaPhp** que es el backend (API REST). Juntos forman un sistema completo de facturacion.

### Diagrama de conexion

```
+-----------------------------+          +-----------------------------+          +-------------------+
|      NAVEGADOR              |          |       ApiGenericaPhp        |          |    MariaDB/MySQL   |
|  (Usuario en el browser)    |          |     (Backend - API REST)    |          |   (Base de datos)  |
+-----------------------------+          +-----------------------------+          +-------------------+
|                             |          |                             |          |                   |
|  http://localhost/          |  cURL    |  http://localhost:8000      |   PDO    | bdfacturas_       |
|  FrontPhp_AppiGenericaPhp/  | -------> |  /api/producto              | -------> | mariadb_local     |
|  pages/producto.php         |          |  /api/factura               |          |                   |
|                             | <------- |  /api/cliente               | <------- | 12 tablas         |
|  Renderiza HTML con los     |   JSON   |  /api/vendedor              |   Filas  | 5 triggers        |
|  datos recibidos            |          |  /api/{cualquier_tabla}     |          | 5 stored procs    |
+-----------------------------+          +-----------------------------+          +-------------------+

      Apache (puerto 80)               PHP built-in server (puerto 8000)       MariaDB (puerto 3306)
```

### Flujo de una peticion real

Cuando un usuario abre la pagina de facturas en el navegador:

```
1. El navegador pide: http://localhost/FrontPhp_AppiGenericaPhp/pages/factura.php
2. Apache ejecuta factura.php (frontend PHP)
3. factura.php usa ApiService.php para hacer llamadas cURL a la API:
   - GET http://localhost:8000/api/factura         (listar facturas)
   - GET http://localhost:8000/api/cliente          (listar clientes para dropdown)
   - GET http://localhost:8000/api/vendedor         (listar vendedores para dropdown)
   - GET http://localhost:8000/api/persona          (listar personas para resolver nombres)
4. La API recibe cada peticion, consulta la BD y responde JSON
5. factura.php combina los datos (join manual en PHP) y genera HTML
6. Apache envia el HTML al navegador
7. El usuario ve la tabla de facturas con nombres de clientes y vendedores
```

### Como se conectan (archivo clave)

El frontend sabe donde esta la API gracias a `config.php`:

```php
define('API_BASE_URL', 'http://localhost:8000');
```

Y el servicio `services/ApiService.php` usa esa URL para todas las llamadas cURL.

### Para levantar el sistema completo

Se necesitan **tres cosas corriendo al mismo tiempo**:

| Componente | Como se inicia | Puerto |
|---|---|---|
| MariaDB | XAMPP Control Panel (boton Start en MySQL) | 3306 |
| Apache | XAMPP Control Panel (boton Start en Apache) | 80 |
| ApiGenericaPhp | `C:\xampp\php\php.exe -S localhost:8000 -t public` desde la carpeta del API | 8000 |

Luego abrir en el navegador: `http://localhost/FrontPhp_AppiGenericaPhp/pages/home.php`

---

## Caracteristicas

- **CRUD Completo**: Crear, Leer, Actualizar y Eliminar sobre 10 entidades
- **Facturacion Maestro-Detalle**: Facturas con productos (lineas de detalle dinamicas)
- **Bootstrap 5.3.3**: Interfaz responsive con sidebar de navegacion
- **ApiService Generico**: Una sola clase PHP maneja TODA la comunicacion con la API via cURL
- **Patron PRG**: Post/Redirect/Get para evitar reenvio de formularios
- **Mensajes Flash**: Notificaciones de exito/error via sesiones PHP
- **Dashboard**: Pagina home con verificacion de conexion a la API
- **Responsive**: Sidebar colapsable con menu hamburguesa en movil
- **Sin Frameworks PHP**: PHP vanilla — ideal para aprender los fundamentos
- **Sin Base de Datos Directa**: Todo pasa por la API REST (separacion total)

---

## Arquitectura

```
+-------------------------------------------------------------+
|                         NAVEGADOR                           |
|                 (HTML + Bootstrap + JS minimo)               |
+-------------------------------------------------------------+
                              |
                              v
+-------------------------------------------------------------+
|                          PAGINAS                            |
|  home.php | producto.php | factura.php | cliente.php | ...  |
|  (Reciben HTTP, procesan POST, renderizan HTML)             |
+-------------------------------------------------------------+
                              |
                              v
+-------------------------------------------------------------+
|                       SERVICIO API                          |
|                      ApiService.php                         |
|  listar() | obtenerPorClave() | crear() | actualizar() |   |
|  eliminar()                                                 |
+-------------------------------------------------------------+
                              |
                         cURL (HTTP)
                              |
                              v
+-------------------------------------------------------------+
|                    ApiGenericaPhp                            |
|               (API REST en localhost:8000)                   |
+-------------------------------------------------------------+
```

### Flujo dentro del frontend

```
GET /pages/producto.php?accion=editar&clave=PR001

1. producto.php       -> Detecta accion="editar", clave="PR001"
2. ApiService::listar -> GET http://localhost:8000/api/producto (trae todos)
3. producto.php       -> Busca en el array el registro con codigo=PR001
4. producto.php       -> Renderiza formulario pre-llenado con los datos
5. Usuario edita      -> Submit del form (POST)
6. producto.php       -> Detecta accion_post="actualizar"
7. ApiService::actualizar -> PUT http://localhost:8000/api/producto/codigo/PR001
8. producto.php       -> Guarda mensaje en $_SESSION, redirige (PRG)
9. producto.php       -> Muestra tabla + alerta "Producto actualizado"
```

---

## Patrones de Diseno

### 1. Service Layer (Capa de Servicio)

`ApiService.php` encapsula toda la comunicacion HTTP. Las paginas nunca hacen cURL directamente — llaman metodos del servicio.

```php
// Pagina: solo logica de presentacion
$api = new ApiService();
$productos = $api->listar('producto', 50);

// ApiService: encapsula cURL, headers, JSON parsing
public function listar(string $tabla, ?int $limite = null): array
{
    $url = API_BASE_URL . "/api/{$tabla}";
    if ($limite) $url .= "?limite={$limite}";
    // ... cURL GET, json_decode, return $json['datos']
}
```

### 2. Post/Redirect/Get (PRG)

Despues de cada operacion POST, se redirige con `header('Location: ...')`. Esto evita que el usuario reenvie el formulario al refrescar la pagina.

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $api->crear('producto', $datos);
    $_SESSION['mensaje'] = $resultado['mensaje'];
    $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    header('Location: producto.php');  // Redirige (GET)
    exit;
}
```

### 3. Flash Messages (Mensajes de Sesion)

Las notificaciones se guardan en `$_SESSION` y se muestran una sola vez:

```php
// Despues de una operacion (en la pagina):
$_SESSION['mensaje'] = 'Producto creado correctamente';
$_SESSION['tipo'] = 'success';

// En header.php (se muestra y se borra):
<?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-<?= $_SESSION['tipo'] ?? 'info' ?> alert-dismissible">
        <?= $_SESSION['mensaje'] ?>
    </div>
    <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
<?php endif; ?>
```

### 4. Template Composition (Composicion de Templates)

Cada pagina incluye header y footer compartidos. El header maneja la sesion, sidebar y navegacion:

```php
// Cada pagina:
$paginaActual = 'producto';       // Para resaltar en el sidebar
$tituloPagina = 'Productos';      // Para el <title>
require_once '../includes/header.php';
// ... contenido de la pagina ...
require_once '../includes/footer.php';
```

### 5. Manual Join (Join Manual en PHP)

Las relaciones entre tablas se resuelven en PHP con arrays asociativos, no con SQL JOIN:

```php
// Construir mapa de lookup
$personas = $api->listar('persona');
$mapaPersonas = [];
foreach ($personas as $p) {
    $mapaPersonas[$p['codigo']] = $p['nombre'];
}

// Usar en la tabla HTML
foreach ($clientes as $c) {
    $nombrePersona = $mapaPersonas[$c['fkcodpersona']] ?? 'Desconocido';
    echo "<td>{$nombrePersona}</td>";
}
```

---

## Requisitos

| Requisito | Version |
|---|---|
| PHP | 7.0 o superior |
| Extension PHP | `curl` (habilitada por defecto en XAMPP) |
| Extension PHP | `session` (habilitada por defecto) |
| Servidor Web | Apache (XAMPP) u otro que ejecute PHP |
| Backend | ApiGenericaPhp corriendo en `localhost:8000` |

---

## Instalacion

### 1. Copiar el proyecto

Copiar la carpeta `FrontPhp_AppiGenericaPhp` dentro de `C:\xampp\htdocs\`.

### 2. Verificar el backend

Asegurarse de que **ApiGenericaPhp** esta instalado y funcionando:

```bash
# Iniciar la API (desde la carpeta ApiGenericaPhp):
C:\xampp\php\php.exe -S localhost:8000 -t public
```

### 3. Iniciar Apache

Desde el XAMPP Control Panel, iniciar Apache (boton Start).

### 4. Abrir en el navegador

```
http://localhost/FrontPhp_AppiGenericaPhp/pages/home.php
```

La pagina Home mostrara el estado de conexion con la API.

---

## Configuracion

### Archivo config.php

```php
define('API_BASE_URL', 'http://localhost:8000');
```

Este es el unico archivo de configuracion. Si la API esta en otro puerto o servidor, cambiar esta linea.

### Cambiar la URL de la API

| Escenario | Valor |
|---|---|
| API local (PHP built-in server) | `http://localhost:8000` |
| API en otro puerto | `http://localhost:9000` |
| API en otra maquina | `http://192.168.1.100:8000` |

---

## Paginas y Navegacion

### Sistema de Rutas

La navegacion usa query strings (parametros en la URL):

| URL | Que muestra |
|---|---|
| `pages/home.php` | Dashboard con estado de la API |
| `pages/producto.php` | Tabla de productos |
| `pages/producto.php?accion=nuevo` | Formulario para crear producto |
| `pages/producto.php?accion=editar&clave=PR001` | Formulario para editar PR001 |
| `pages/factura.php` | Tabla de facturas |
| `pages/factura.php?vista=formulario` | Formulario para crear factura |
| `pages/factura.php?vista=formulario&editar=123` | Formulario para editar factura 123 |
| `pages/factura.php?vista=ver&numero=123` | Detalle de factura 123 |

### Las 10 paginas CRUD

| Pagina | Entidad | Clave Primaria | Campos Principales |
|---|---|---|---|
| `home.php` | - | - | Dashboard, estado de API |
| `producto.php` | producto | codigo (string) | codigo, nombre, stock, valorunitario |
| `persona.php` | persona | codigo (string) | codigo, nombre, email, telefono |
| `usuario.php` | usuario | email (string) | email, contrasena |
| `empresa.php` | empresa | codigo (string) | codigo, nombre |
| `rol.php` | rol | id (int) | id, nombre |
| `ruta.php` | ruta | id (auto-int) | ruta, descripcion |
| `cliente.php` | cliente | id (auto-int) | fkcodpersona, fkcodempresa, credito |
| `vendedor.php` | vendedor | id (auto-int) | fkcodpersona, carnet, direccion |
| `factura.php` | factura + productosporfactura | numero (auto-int) | maestro-detalle completo |

### Sidebar de Navegacion

El sidebar aparece en todas las paginas (definido en `includes/header.php`). Resalta automaticamente la pagina activa comparando `$paginaActual`:

```php
<a class="nav-link <?= $paginaActual === 'producto' ? 'active' : '' ?>"
   href="<?= $baseUrl ?>/pages/producto.php">Producto</a>
```

En movil (< 641px), el sidebar se colapsa y aparece un boton hamburguesa (implementado con CSS puro, sin JavaScript).

---

## ApiService - Servicio de Comunicacion

`services/ApiService.php` es la unica clase del frontend. Encapsula todas las llamadas HTTP a la API.

### Metodos disponibles

| Metodo | Verbo HTTP | Endpoint API | Que hace |
|---|---|---|---|
| `listar($tabla, $limite)` | GET | `/api/{tabla}` | Trae todos los registros |
| `obtenerPorClave($tabla, $clave, $valor)` | GET | `/api/{tabla}/{clave}/{valor}` | Trae un registro por clave |
| `crear($tabla, $datos)` | POST | `/api/{tabla}` | Crea un registro |
| `actualizar($tabla, $clave, $valor, $datos)` | PUT | `/api/{tabla}/{clave}/{valor}` | Actualiza un registro |
| `eliminar($tabla, $clave, $valor)` | DELETE | `/api/{tabla}/{clave}/{valor}` | Elimina un registro |

### Ejemplo de uso en una pagina

```php
require_once '../services/ApiService.php';
$api = new ApiService();

// Listar productos (maximo 50)
$productos = $api->listar('producto', 50);

// Obtener un producto especifico
$producto = $api->obtenerPorClave('producto', 'codigo', 'PR001');

// Crear un producto
$resultado = $api->crear('producto', [
    'codigo' => 'PR_NUEVO',
    'nombre' => 'Webcam HD',
    'stock' => 50,
    'valorunitario' => 79990
]);

// Actualizar
$resultado = $api->actualizar('producto', 'codigo', 'PR_NUEVO', [
    'stock' => 99
]);

// Eliminar
$resultado = $api->eliminar('producto', 'codigo', 'PR_NUEVO');
```

### Como funciona internamente

```php
// Todas las llamadas siguen este patron:
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

// Para POST/PUT, agrega body JSON:
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$respuesta = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$json = json_decode($respuesta, true);

// Retorna datos o resultado con exito/mensaje
return $json['datos'] ?? [];
```

---

## Facturacion Maestro-Detalle

La pagina `factura.php` es la mas compleja. Maneja dos tablas relacionadas: `factura` (maestro) y `productosporfactura` (detalle).

### Tres vistas

```
factura.php                         -> Lista de facturas (tabla)
factura.php?vista=ver&numero=123    -> Detalle de factura 123 (solo lectura)
factura.php?vista=formulario        -> Crear factura nueva
factura.php?vista=formulario&editar=123 -> Editar factura 123
```

### Flujo para crear una factura

```
1. Usuario llena formulario: cliente, vendedor, productos con cantidades
2. POST con accion_post="crear"
3. Frontend:
   a) POST /api/factura               -> Crea factura con total=0
   b) GET  /api/factura                -> Busca la factura recien creada (ultimo numero)
   c) POST /api/productosporfactura    -> Crea cada linea de detalle (N llamadas)
4. Los TRIGGERS de MariaDB automaticamente:
   - Calculan el subtotal de cada linea (cantidad * valorunitario)
   - Descuentan el stock del producto
   - Recalculan el total de la factura (suma de subtotales)
5. Redirect a lista de facturas con mensaje de exito
```

### Flujo para editar una factura

```
1. DELETE todas las lineas de productosporfactura (trigger restaura stock)
2. PUT    actualiza factura (cliente, vendedor)
3. POST   crea las nuevas lineas de productosporfactura (trigger descuenta stock)
```

### Filas dinamicas de productos (JavaScript)

El formulario permite agregar multiples productos con un boton "Agregar Producto":

```javascript
function agregarProducto() {
    const container = document.getElementById('productos-container');
    const primeraFila = container.querySelector('.producto-fila');
    if (primeraFila) {
        const nuevaFila = primeraFila.cloneNode(true);
        nuevaFila.querySelector('select').selectedIndex = 0;
        nuevaFila.querySelector('input[type="number"]').value = 1;
        container.appendChild(nuevaFila);
    }
}
```

### Joins manuales para mostrar nombres

La tabla de facturas necesita mostrar nombres de clientes y vendedores, no solo IDs:

```php
// 1. Traer todas las tablas relacionadas
$facturas   = $api->listar('factura');
$clientes   = $api->listar('cliente');
$vendedores = $api->listar('vendedor');
$personas   = $api->listar('persona');
$productos  = $api->listar('productosporfactura');

// 2. Construir mapas de lookup
$mapaPersonas = [];
foreach ($personas as $p) {
    $mapaPersonas[$p['codigo']] = $p['nombre'];
}

$mapaClientes = [];
foreach ($clientes as $c) {
    $mapaClientes[$c['id']] = $mapaPersonas[$c['fkcodpersona']] ?? 'Desconocido';
}

// 3. Usar en la tabla HTML
foreach ($facturas as $f) {
    $nombreCliente = $mapaClientes[$f['fkidcliente']] ?? 'Desconocido';
    echo "<td>{$nombreCliente}</td>";
}
```

---

## Estructura del Proyecto

```
FrontPhp_AppiGenericaPhp/
|-- index.php                     # Redirige a pages/home.php
|-- config.php                    # URL base de la API (unica configuracion)
|
|-- assets/
|   +-- css/
|       +-- app.css               # Estilos custom: sidebar, responsive, layout (186 lineas)
|
|-- includes/
|   |-- header.php                # HTML head, sidebar, sesion, mensajes flash, Bootstrap CDN
|   +-- footer.php                # Cierra HTML, ob_end_flush()
|
|-- pages/
|   |-- home.php                  # Dashboard con verificacion de conexion a la API
|   |-- producto.php              # CRUD productos
|   |-- persona.php               # CRUD personas
|   |-- usuario.php               # CRUD usuarios (email/contrasena)
|   |-- empresa.php               # CRUD empresas
|   |-- rol.php                   # CRUD roles
|   |-- ruta.php                  # CRUD rutas
|   |-- cliente.php               # CRUD clientes (FK persona, empresa)
|   |-- vendedor.php              # CRUD vendedores (FK persona)
|   +-- factura.php               # CRUD facturas maestro-detalle (la mas compleja)
|
+-- services/
    +-- ApiService.php            # Servicio HTTP: 5 metodos cURL para consumir la API
```

### Que hace cada carpeta

| Carpeta | Proposito |
|---|---|
| `assets/css/` | Estilos custom (sidebar, layout responsive) |
| `includes/` | Componentes compartidos (header con sidebar, footer) |
| `pages/` | Una pagina PHP por entidad, cada una con su CRUD completo |
| `services/` | Capa de servicio para comunicacion con la API |

---

## Conceptos de PHP Usados

### Output Buffering (ob_start / ob_end_flush)

PHP normalmente envia el HTML al navegador linea por linea. Pero si ya envio HTML, no puede hacer `header('Location: ...')` porque los headers HTTP van ANTES del body.

`ob_start()` en el header acumula todo el HTML en un buffer. Asi podemos hacer redirect despues de haber "generado" HTML:

```php
// header.php
ob_start();          // Empieza a acumular (no envia nada todavia)
// ... todo el HTML se acumula en memoria ...

// pagina.php (despues de un POST)
header('Location: producto.php');  // Funciona porque nada fue enviado al navegador
exit;

// footer.php
ob_end_flush();      // Ahora si, envia todo el HTML acumulado al navegador
```

### Sesiones ($_SESSION)

Las sesiones permiten guardar datos entre peticiones HTTP (que son stateless):

```php
// Peticion 1 (POST - crear producto):
session_start();
$_SESSION['mensaje'] = 'Producto creado';
header('Location: producto.php');  // Redirige

// Peticion 2 (GET - mostrar tabla):
session_start();
echo $_SESSION['mensaje'];  // "Producto creado"
unset($_SESSION['mensaje']); // Se borra (solo se muestra una vez)
```

### cURL en PHP

cURL es la libreria de PHP para hacer peticiones HTTP (como si fuera un navegador invisible):

```php
$ch = curl_init('http://localhost:8000/api/producto');  // Crear sesion cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);         // No imprimir, retornar string
curl_setopt($ch, CURLOPT_TIMEOUT, 5);                    // Timeout 5 segundos
$respuesta = curl_exec($ch);                              // Ejecutar la peticion
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);        // Codigo HTTP (200, 404, etc.)
curl_close($ch);                                          // Cerrar sesion
$datos = json_decode($respuesta, true);                   // Convertir JSON a array PHP
```

### Superglobales

PHP llena automaticamente estos arrays antes de ejecutar su codigo:

| Superglobal | Que contiene | Ejemplo |
|---|---|---|
| `$_SERVER` | Info de la peticion | `$_SERVER['REQUEST_METHOD']` -> `"POST"` |
| `$_GET` | Parametros de la URL | `?accion=editar` -> `$_GET['accion']` |
| `$_POST` | Datos del formulario | `$_POST['nombre']` -> `"Juan"` |
| `$_SESSION` | Datos entre peticiones | `$_SESSION['mensaje']` -> `"Creado"` |

### Operador ?? (null coalescing)

"Si lo de la izquierda es null o no existe, usar lo de la derecha":

```php
$limite = $_GET['limite'] ?? null;       // Si no viene en la URL, null
$nombre = $registro['nombre'] ?? '';     // Si no existe la clave, string vacio
```

---

## Como Escalar Este Proyecto

### 1. Agregar autenticacion (login)

```php
// CREAR: pages/login.php (formulario email/contrasena)
// USAR: ApiService para POST /api/usuario/verificar-contrasena
// GUARDAR: $_SESSION['usuario'] con los datos del usuario logueado
// AGREGAR: verificacion en header.php:
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
```

### 2. Agregar una nueva entidad

Copiar cualquier pagina CRUD simple (ej: `producto.php`) y cambiar:

```php
$paginaActual = 'mi_entidad';       // Para el sidebar
$tituloPagina = 'Mi Entidad';       // Para el <title>
$tabla = 'mi_entidad';              // Tabla en la BD
$clave = 'id';                      // Clave primaria
// Actualizar los campos del formulario y la tabla HTML
```

Agregar el link en `includes/header.php`.

### 3. Agregar paginacion

```php
// Agregar parametro de offset a ApiService:
$productos = $api->listar('producto', $limite);

// Agregar controles de paginacion en la pagina:
$pagina = $_GET['pagina'] ?? 1;
$limite = 20;
// Mostrar botones Anterior / Siguiente
```

### 4. Agregar busqueda/filtrado

```php
// Filtrar en PHP (datasets pequenos):
$resultados = array_filter($productos, function($p) use ($busqueda) {
    return stripos($p['nombre'], $busqueda) !== false;
});
```

### 5. Agregar validacion client-side

```html
<!-- Agregar atributos HTML5 a los inputs: -->
<input type="text" name="codigo" required minlength="3" maxlength="20"
       pattern="[A-Za-z0-9_]+" title="Solo letras, numeros y guion bajo">
```

---

## Solucion de Problemas Comunes

### 1. "No se pudo conectar con la API"

**Sintoma**: La pagina Home muestra error de conexion.

**Solucion**:
- Verificar que la API este corriendo: `C:\xampp\php\php.exe -S localhost:8000 -t public` (desde la carpeta ApiGenericaPhp)
- Verificar que `config.php` tenga la URL correcta
- Probar en el navegador: `http://localhost:8000/api/producto`

### 2. Pagina en blanco

**Sintoma**: La pagina no muestra nada.

**Solucion**:
- Habilitar errores en PHP: agregar al inicio de la pagina:
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```
- Verificar que Apache esta corriendo (XAMPP Control Panel)
- Revisar logs: `C:\xampp\apache\logs\error.log`

### 3. "Call to undefined function curl_init()"

**Sintoma**: Error fatal al hacer cualquier operacion CRUD.

**Solucion**:
- Habilitar la extension cURL en `C:\xampp\php\php.ini`:
  ```ini
  extension=curl
  ```
- Reiniciar Apache

### 4. Los cambios no se guardan (formulario se reenvia)

**Sintoma**: Al refrescar la pagina, el navegador pregunta si quiere reenviar el formulario.

**Solucion**: Esto no deberia pasar gracias al patron PRG. Verificar que despues de cada POST haya:
```php
header('Location: pagina.php');
exit;
```

### 5. No aparece el sidebar en movil

**Sintoma**: En pantallas pequenas no hay forma de navegar.

**Solucion**: Buscar el boton hamburguesa (tres lineas) en la esquina superior. El sidebar se despliega al tocarlo. Si no aparece, verificar que `assets/css/app.css` se esta cargando.

### 6. Los nombres aparecen como "Desconocido"

**Sintoma**: En facturas o clientes, en vez de nombres aparece "Desconocido".

**Solucion**: La tabla `persona` esta vacia o la API no responde. Verificar:
- Que la BD tenga datos: `http://localhost:8000/api/persona`
- Que la API este corriendo

---

## Relaciones entre Entidades

```
persona (PK: codigo)
├── cliente (FK: fkcodpersona)
│   └── factura (FK: fkidcliente)
│       └── productosporfactura (FK: fknumfactura)
│           └── producto (FK: fkcodproducto)
├── vendedor (FK: fkcodpersona)
│   └── factura (FK: fkidvendedor)
└── empresa (opcional FK en cliente: fkcodempresa)

usuario (PK: email) - independiente
rol (PK: id) - independiente
ruta (PK: id) - independiente
```

---

## Tecnologias

| Tecnologia | Uso | Version |
|---|---|---|
| PHP | Lenguaje del frontend (server-side rendering) | 7.0+ |
| Bootstrap | Framework CSS (CDN) | 5.3.3 |
| cURL | Comunicacion HTTP con la API | Extension PHP |
| Apache | Servidor web (via XAMPP) | 2.4+ |
| JavaScript | Solo para filas dinamicas en factura | Vanilla (1 funcion) |

---

## Licencia

Este proyecto es de uso educativo.
