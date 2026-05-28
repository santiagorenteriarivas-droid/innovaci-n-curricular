<?php
namespace ApiGenericaPhp\Servicios;

use ApiGenericaPhp\Servicios\Abstracciones\IServicioCrud;                
use ApiGenericaPhp\Repositorios\Abstracciones\IRepositorioLecturaTabla;  
use ApiGenericaPhp\Servicios\Abstracciones\IPoliticaTablasProhibidas;    

class ServicioCrud implements IServicioCrud
{
    private IRepositorioLecturaTabla $repositorioLectura;       
    private IPoliticaTablasProhibidas $politicaTablasProhibidas; 

    public function __construct(
        IRepositorioLecturaTabla $repositorioLectura,
        IPoliticaTablasProhibidas $politicaTablasProhibidas
    ) {
        $this->repositorioLectura = $repositorioLectura;
        $this->politicaTablasProhibidas = $politicaTablasProhibidas;
    }

    public function listarAsync(string $nombreTabla, ?string $esquema, ?int $limite): array
    {
        $this->validarTabla($nombreTabla, true);
        $esquemaNormalizado = $this->normalizar($esquema);
        $limiteNormalizado  = ($limite === null || $limite <= 0) ? null : $limite;

        return $this->repositorioLectura->obtenerFilasAsync($nombreTabla, $esquemaNormalizado, $limiteNormalizado);
    }

    public function obtenerPorClaveAsync(string $nombreTabla, ?string $esquema, string $nombreClave, string $valor): array
    {
        $this->validarTabla($nombreTabla, true);
        $this->validarNoVacio($nombreClave, "El nombre de la clave no puede estar vacío.");
        $this->validarNoVacio($valor, "El valor no puede estar vacío.");

        $esquemaNormalizado = $this->normalizar($esquema);
        return $this->repositorioLectura->obtenerPorClaveAsync($nombreTabla, $esquemaNormalizado, trim($nombreClave), trim($valor));
    }

    public function crearAsync(string $nombreTabla, ?string $esquema, array $datos, ?string $camposEncriptar = null): bool
    {
        $this->validarTabla($nombreTabla, false);
        if (empty($datos)) {
            throw new \InvalidArgumentException("Los datos no pueden estar vacíos.");
        }

        $esquemaNormalizado = $this->normalizar($esquema);
        $camposNormalizados = $this->normalizar($camposEncriptar);

        return $this->repositorioLectura->crearAsync($nombreTabla, $esquemaNormalizado, $datos, $camposNormalizados);
    }

    public function actualizarAsync(string $nombreTabla, ?string $esquema, string $nombreClave, string $valorClave, array $datos, ?string $camposEncriptar = null): int
    {
        $this->validarTabla($nombreTabla, false);
        $this->validarNoVacio($nombreClave, "El nombre de la clave no puede estar vacío.");
        $this->validarNoVacio($valorClave, "El valor de la clave no puede estar vacío.");
        if (empty($datos)) {
            throw new \InvalidArgumentException("Los datos a actualizar no pueden estar vacíos.");
        }

        $esquemaNormalizado = $this->normalizar($esquema);
        $camposNormalizados = $this->normalizar($camposEncriptar);

        return $this->repositorioLectura->actualizarAsync(
            $nombreTabla, $esquemaNormalizado, trim($nombreClave), trim($valorClave), $datos, $camposNormalizados
        );
    }

    public function eliminarAsync(string $nombreTabla, ?string $esquema, string $nombreClave, string $valorClave): int
    {
        $this->validarTabla($nombreTabla, false);
        $this->validarNoVacio($nombreClave, "El nombre de la clave no puede estar vacío.");
        $this->validarNoVacio($valorClave, "El valor de la clave no puede estar vacío.");

        $esquemaNormalizado = $this->normalizar($esquema);
        return $this->repositorioLectura->eliminarAsync($nombreTabla, $esquemaNormalizado, trim($nombreClave), trim($valorClave));
    }

    public function verificarContrasenaAsync(string $nombreTabla, ?string $esquema, string $campoUsuario, string $campoContrasena, string $valorUsuario, string $valorContrasena): array
    {
        $this->validarTabla($nombreTabla, true);
        $this->validarNoVacio($campoUsuario, "El campo de usuario no puede estar vacío.");
        $this->validarNoVacio($campoContrasena, "El campo de contraseña no puede estar vacío.");
        $this->validarNoVacio($valorUsuario, "El valor de usuario no puede estar vacío.");
        $this->validarNoVacio($valorContrasena, "La contraseña no puede estar vacía.");

        $esquemaNormalizado = $this->normalizar($esquema);

        $hashAlmacenado = $this->repositorioLectura->obtenerHashContrasenaAsync(
            $nombreTabla, $esquemaNormalizado, trim($campoUsuario), trim($campoContrasena), trim($valorUsuario)
        );

        if ($hashAlmacenado === null) {
            return ['codigo' => 404, 'mensaje' => 'Usuario no encontrado'];
        }

        if (password_verify($valorContrasena, $hashAlmacenado) || $valorContrasena === $hashAlmacenado) {
            // Obtener el registro del usuario para retornarlo en el payload
            $usuario = $this->repositorioLectura->obtenerPorClaveAsync($nombreTabla, $esquemaNormalizado, trim($campoUsuario), trim($valorUsuario));
            if (!empty($usuario)) {
                $usuarioData = $usuario[0];
                unset($usuarioData[$campoContrasena]);
                if (isset($usuarioData['password'])) {
                    unset($usuarioData['password']);
                }
                return [
                    'codigo' => 200,
                    'mensaje' => 'Credenciales válidas',
                    'usuario' => $usuarioData
                ];
            }
            return ['codigo' => 200, 'mensaje' => 'Credenciales válidas'];
        } else {
            return ['codigo' => 401, 'mensaje' => 'Contraseña incorrecta'];
        }
    }

    // 🔧 Métodos auxiliares
    private function validarTabla(string $nombreTabla, bool $soloLectura): void
    {
        $this->validarNoVacio($nombreTabla, "El nombre de la tabla no puede estar vacío.");
        if (!$this->politicaTablasProhibidas->esTablaPermitida($nombreTabla)) {
            $accion = $soloLectura ? "consultada" : "modificada";
            throw new \RuntimeException("Acceso denegado: La tabla '{$nombreTabla}' está restringida y no puede ser {$accion}.");
        }
    }

    private function validarNoVacio(string $valor, string $mensaje): void
    {
        if (empty(trim($valor))) {
            throw new \InvalidArgumentException($mensaje);
        }
    }

    private function normalizar(?string $valor): ?string
    {
        return (empty($valor) || empty(trim($valor))) ? null : trim($valor);
    }
}
