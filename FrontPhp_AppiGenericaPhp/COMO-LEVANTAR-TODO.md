# COMO LEVANTAR TODO EL SISTEMA

## Hacelo andar YA (copiar y pegar)

```
╔══════════════════════════════════════════════════════════════════════════╗
║                                                                        ║
║   1.  Abrir XAMPP Control Panel                                        ║
║       -> MySQL  -> [Start]                                             ║
║       -> Apache -> [Start]                                             ║
║                                                                        ║
║   2.  Abrir una terminal (PowerShell, CMD, o Git Bash)                 ║
║       y ejecutar:                                                      ║
║                                                                        ║
║       cd C:\xampp\htdocs\ApiGenericaPhp                                ║
║       C:\xampp\php\php.exe -S localhost:8000 -t public                 ║
║                                                                        ║
║       (NO cerrar esa terminal)                                         ║
║                                                                        ║
║   3.  Abrir en el navegador:                                           ║
║                                                                        ║
║       http://localhost/FrontPhp_AppiGenericaPhp/pages/home.php         ║
║                                                                        ║
║   Listo.                                                               ║
║                                                                        ║
╚══════════════════════════════════════════════════════════════════════════╝
```

Si es la PRIMERA VEZ y la base de datos no existe, antes del paso 1 ejecutar:

```bash
C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 -e "source C:/xampp/htdocs/ApiGenericaPhp/script_bd/bdfacturas_mariadb.sql"
```

---

## Ahora si, la explicacion

```
╔══════════════════════════════════════════════════════════════════════════╗
║                                                                        ║
║   Este sistema tiene 3 PIEZAS que deben estar corriendo A LA VEZ:      ║
║                                                                        ║
║       1. MariaDB/MySQL   (la base de datos)                            ║
║       2. La API          (el backend, puerto 8000)                     ║
║       3. Apache          (el frontend, puerto 80)                      ║
║                                                                        ║
║   Si FALTA alguna, el sistema NO funciona.                             ║
║                                                                        ║
╚══════════════════════════════════════════════════════════════════════════╝
```

---

## El diagrama completo

```
    TU NAVEGADOR                        TU PC (localhost)
   ┌──────────────┐
   │  Chrome /    │
   │  Firefox /   │        ┌─────────────────────────────────────────────────────────────┐
   │  Edge        │        │                                                             │
   │              │        │   XAMPP Control Panel                                       │
   │  Escribis:   │        │   ┌───────────────────────────────────────────────────────┐ │
   │  localhost/   │        │   │                                                       │ │
   │  FrontPhp_.../│  ───►  │   │  [1] MySQL/MariaDB ──── puerto 3306                  │ │
   │  pages/       │        │   │       La base de datos. Sin esto no hay datos.        │ │
   │  home.php     │        │   │       Se prende desde XAMPP (boton Start en MySQL).   │ │
   │              │        │   │                                                       │ │
   │              │        │   │  [2] Apache ──────────── puerto 80                    │ │
   │              │  ◄───  │   │       Sirve el FRONTEND (las paginas PHP).             │ │
   │  Ves la      │  HTML  │   │       Se prende desde XAMPP (boton Start en Apache).   │ │
   │  pagina web  │        │   │       Lee los archivos de C:\xampp\htdocs\             │ │
   │              │        │   │                                                       │ │
   │              │        │   └───────────────────────────────────────────────────────┘ │
   │              │        │                                                             │
   │              │        │   Terminal / CMD / PowerShell (aparte)                      │
   │              │        │   ┌───────────────────────────────────────────────────────┐ │
   │              │        │   │                                                       │ │
   │              │        │   │  [3] PHP Built-in Server ── puerto 8000               │ │
   │              │        │   │       Sirve la API (el backend).                       │ │
   │              │        │   │       Se prende con un comando en la terminal.         │ │
   │              │        │   │       NO es parte de XAMPP, corre aparte.              │ │
   │              │        │   │                                                       │ │
   │              │        │   └───────────────────────────────────────────────────────┘ │
   └──────────────┘        └─────────────────────────────────────────────────────────────┘
```

---

## PASO 0: Solo la primera vez (crear la base de datos)

Si es la PRIMERA vez que levantas el sistema, necesitas crear la base de datos.

### 0.1 Prender MySQL desde XAMPP

Abrir el **XAMPP Control Panel** y hacer clic en **Start** en la fila de **MySQL**.

```
┌──────────────────────────────────────────────────┐
│  XAMPP Control Panel                             │
│                                                  │
│  Module        │  PID   │  Port  │  Actions      │
│  ─────────────────────────────────────────────── │
│  Apache        │        │        │  [Start]      │
│  MySQL         │  1234  │  3306  │  [Stop] ◄── verde = prendido  │
│  FileZilla     │        │        │  [Start]      │
│                                                  │
└──────────────────────────────────────────────────┘
```

### 0.2 Ejecutar el script de la base de datos

Abrir una terminal (CMD, PowerShell, o Git Bash) y ejecutar:

```bash
C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 -e "source C:/xampp/htdocs/ApiGenericaPhp/script_bd/bdfacturas_mariadb.sql"
```

Esto crea:
- La base de datos `bdfacturas_mariadb_local`
- 12 tablas
- 5 triggers
- 5 stored procedures
- Datos de ejemplo (productos, personas, etc.)

### 0.3 Verificar que funciono

```bash
C:\xampp\mysql\bin\mysql.exe -u root -e "USE bdfacturas_mariadb_local; SHOW TABLES;"
```

Deberia mostrar 12 tablas. Si las ves, la BD esta lista.

---

## PASO 1: Prender MySQL (la base de datos)

```
╔══════════════════════════════════════════════════╗
║  XAMPP Control Panel -> MySQL -> [Start]         ║
╚══════════════════════════════════════════════════╝
```

1. Abrir el **XAMPP Control Panel** (buscar "xampp" en el menu de Windows)
2. En la fila de **MySQL**, hacer clic en **Start**
3. Debe ponerse en **verde** y mostrar el puerto **3306**

**Si falla**: otro programa esta usando el puerto 3306, o MySQL ya esta corriendo.

---

## PASO 2: Prender Apache (el frontend)

```
╔══════════════════════════════════════════════════╗
║  XAMPP Control Panel -> Apache -> [Start]         ║
╚══════════════════════════════════════════════════╝
```

1. En el mismo XAMPP Control Panel, en la fila de **Apache**, hacer clic en **Start**
2. Debe ponerse en **verde** y mostrar los puertos **80, 443**

**Si falla**: otro programa esta usando el puerto 80 (comun: Skype, IIS, otro servidor web).

### Verificar

Abrir el navegador e ir a:

```
http://localhost/
```

Si ves la pagina de bienvenida de XAMPP, Apache esta funcionando.

---

## PASO 3: Prender la API (el backend)

```
╔══════════════════════════════════════════════════════════════════════════╗
║                                                                        ║
║  ESTE PASO ES EL QUE MAS SE OLVIDA                                    ║
║                                                                        ║
║  La API NO se prende desde XAMPP.                                      ║
║  Se prende con un COMANDO en una TERMINAL.                             ║
║  La terminal debe QUEDAR ABIERTA mientras uses el sistema.             ║
║                                                                        ║
╚══════════════════════════════════════════════════════════════════════════╝
```

### 3.1 Abrir una terminal

Abrir **CMD**, **PowerShell**, o **Git Bash**. Cualquiera sirve.

### 3.2 Ir a la carpeta de la API

```bash
cd C:\xampp\htdocs\ApiGenericaPhp
```

### 3.3 Ejecutar el servidor PHP

```bash
C:\xampp\php\php.exe -S localhost:8000 -t public
```

Deberia mostrar algo como:

```
[Fri Apr 10 10:00:00 2026] PHP 8.x.x Development Server (http://localhost:8000) started
```

### 3.4 NO CERRAR LA TERMINAL

```
╔══════════════════════════════════════════════════════════════════════════╗
║                                                                        ║
║   LA TERMINAL DEBE QUEDAR ABIERTA.                                     ║
║                                                                        ║
║   Si la cerras, la API se apaga y el frontend no puede cargar datos.   ║
║   Podes minimizarla, pero NO cerrarla.                                 ║
║                                                                        ║
╚══════════════════════════════════════════════════════════════════════════╝
```

### Verificar

Abrir el navegador e ir a:

```
http://localhost:8000/
```

Si ves un mensaje de bienvenida de la API (JSON o texto), la API esta funcionando.

Tambien podes probar:

```
http://localhost:8000/api/producto
```

Deberia devolver un JSON con productos.

---

## PASO 4: Abrir el sistema

```
╔══════════════════════════════════════════════════════════════════════════╗
║                                                                        ║
║   http://localhost/FrontPhp_AppiGenericaPhp/pages/home.php             ║
║                                                                        ║
╚══════════════════════════════════════════════════════════════════════════╝
```

La pagina Home muestra el estado de la conexion con la API:
- **Verde**: Todo conectado, listo para usar
- **Rojo**: La API no responde (volver al Paso 3)

---

## Resumen visual

```
┌─────────────────────────────────────────────────────────────────────┐
│                         ORDEN DE ENCENDIDO                         │
│                                                                     │
│    PASO 1          PASO 2          PASO 3          PASO 4           │
│                                                                     │
│  ┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────────────┐  │
│  │ MySQL   │    │ Apache  │    │  API    │    │   Navegador     │  │
│  │         │    │         │    │         │    │                 │  │
│  │  XAMPP  │ -> │  XAMPP  │ -> │Terminal │ -> │ localhost/      │  │
│  │ [Start] │    │ [Start] │    │ php -S  │    │ FrontPhp_.../   │  │
│  │         │    │         │    │ :8000   │    │ pages/home.php  │  │
│  │ :3306   │    │ :80     │    │ :8000   │    │                 │  │
│  └─────────┘    └─────────┘    └─────────┘    └─────────────────┘  │
│                                                                     │
│  Base datos      Paginas web     Backend API    Abrir en Chrome     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## Orden de apagado (cuando termines)

El orden inverso:

1. **Cerrar la terminal** donde corre la API (Ctrl+C o cerrar la ventana)
2. **XAMPP** -> Apache -> **Stop**
3. **XAMPP** -> MySQL -> **Stop**

---

## Chequeo rapido: "algo no anda"

```
┌──────────────────────────────────────────────────────────────────────┐
│  PROBLEMA                         │  VERIFICAR                      │
│──────────────────────────────────────────────────────────────────────│
│                                   │                                 │
│  La pagina no carga               │  Apache esta prendido?          │
│  (error de conexion en Chrome)    │  XAMPP -> Apache -> verde?      │
│                                   │                                 │
│──────────────────────────────────────────────────────────────────────│
│                                   │                                 │
│  La pagina carga pero sin datos   │  La API esta corriendo?         │
│  (tablas vacias, errores)         │  La terminal esta abierta?      │
│                                   │  Probar: localhost:8000         │
│                                   │                                 │
│──────────────────────────────────────────────────────────────────────│
│                                   │                                 │
│  La API responde pero sin datos   │  MySQL esta prendido?           │
│  (JSON con datos vacios)          │  XAMPP -> MySQL -> verde?       │
│                                   │  La BD existe?                  │
│                                   │                                 │
│──────────────────────────────────────────────────────────────────────│
│                                   │                                 │
│  "php" no se reconoce             │  Usar ruta completa:            │
│                                   │  C:\xampp\php\php.exe -S ...    │
│                                   │                                 │
│──────────────────────────────────────────────────────────────────────│
│                                   │                                 │
│  Puerto 8000 en uso               │  Ya hay otra instancia?         │
│                                   │  Cerrarla, o usar otro puerto:  │
│                                   │  php -S localhost:8001 -t public│
│                                   │  (y cambiar config.php)         │
│                                   │                                 │
│──────────────────────────────────────────────────────────────────────│
│                                   │                                 │
│  Puerto 80 en uso                 │  Otro programa lo ocupa?        │
│  (Apache no prende)               │  Skype, IIS, otro servidor web  │
│                                   │  Cerrar ese programa primero    │
│                                   │                                 │
└──────────────────────────────────────────────────────────────────────┘
```

---

## Que pasa cuando todo esta prendido

```
   USUARIO                APACHE (:80)              API (:8000)            MYSQL (:3306)
     │                        │                         │                       │
     │  1. Abro home.php      │                         │                       │
     │ ────────────────────►  │                         │                       │
     │                        │  2. PHP ejecuta         │                       │
     │                        │     home.php            │                       │
     │                        │                         │                       │
     │                        │  3. cURL: GET /api/     │                       │
     │                        │ ────────────────────►   │                       │
     │                        │                         │  4. SELECT ...        │
     │                        │                         │ ──────────────────►   │
     │                        │                         │                       │
     │                        │                         │  5. Filas             │
     │                        │                         │ ◄──────────────────   │
     │                        │  6. JSON con datos      │                       │
     │                        │ ◄────────────────────   │                       │
     │                        │                         │                       │
     │                        │  7. PHP genera HTML     │                       │
     │                        │     con los datos       │                       │
     │                        │                         │                       │
     │  8. HTML completo      │                         │                       │
     │ ◄────────────────────  │                         │                       │
     │                        │                         │                       │
     │  9. Veo la pagina      │                         │                       │
     │     con la tabla       │                         │                       │
     │     de productos       │                         │                       │
     ▼                        ▼                         ▼                       ▼
```

---

## Comando rapido (copiar y pegar)

Si ya tenes la BD creada y XAMPP prendido (MySQL + Apache), solo necesitas este comando:

```bash
cd C:\xampp\htdocs\ApiGenericaPhp && C:\xampp\php\php.exe -S localhost:8000 -t public
```

Y luego abrir: `http://localhost/FrontPhp_AppiGenericaPhp/pages/home.php`
