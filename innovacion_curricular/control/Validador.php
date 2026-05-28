<?php

class Validador {

    /**
     * Valida que un email tenga el formato correcto.
     *
     * @param string $email El email a validar
     * @return bool True si el email es válido, false en caso contrario
     */
    public static function validarEmail(string $email): bool {
        // Elimina espacios en blanco al inicio y final
        $email = trim($email);

        // Valida el formato del email usando la función nativa de PHP
        // Ejemplo válido: usuario@dominio.com
        // Ejemplo inválido: usuario@dominio, @dominio.com, usuario.com
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Validación adicional de longitud (máximo 100 caracteres por BD)
        if (strlen($email) > 100) {
            return false;
        }

        return true;
    }

    /**
     * Valida que una contraseña cumpla con los requisitos de seguridad.
     *
     * Requisitos:
     * - Mínimo 8 caracteres
     * - Máximo 100 caracteres (límite de la base de datos)
     *
     * @param string $password La contraseña a validar
     * @param array &$errores Array para almacenar mensajes de error específicos
     * @return bool True si la contraseña es válida, false en caso contrario
     */
    public static function validarPassword(string $password, array &$errores = []): bool {
        $valido = true;

        // Verificar longitud mínima
        if (strlen($password) < 8) {
            $errores[] = "La contraseña debe tener al menos 8 caracteres";
            $valido = false;
        }

        // Verificar longitud máxima (límite de BD)
        if (strlen($password) > 100) {
            $errores[] = "La contraseña no puede exceder 100 caracteres";
            $valido = false;
        }

        // Opcional: Verificar que contenga al menos una letra
        // (Comentado para no ser muy restrictivo en ambiente educativo)
        // if (!preg_match('/[a-zA-Z]/', $password)) {
        //     $errores[] = "La contraseña debe contener al menos una letra";
        //     $valido = false;
        // }

        // Opcional: Verificar que contenga al menos un número
        // (Comentado para no ser muy restrictivo en ambiente educativo)
        // if (!preg_match('/[0-9]/', $password)) {
        //     $errores[] = "La contraseña debe contener al menos un número";
        //     $valido = false;
        // }

        return $valido;
    }

    /**
     * Sanitiza un string para prevenir XSS (Cross-Site Scripting).
     *
     * @param string $valor El valor a sanitizar
     * @return string El valor sanitizado
     */
    public static function sanitizarString(string $valor): string {
        // Elimina espacios en blanco al inicio y final
        $valor = trim($valor);

        // Elimina barras invertidas si magic_quotes está habilitado (legacy)
        $valor = stripslashes($valor);

        // Convierte caracteres especiales HTML para prevenir XSS
        // Ejemplo: <script> se convierte en &lt;script&gt;
        $valor = htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');

        return $valor;
    }

    /**
     * Valida que un array de IDs solo contenga números enteros positivos.
     * Útil para validar arrays de roles, permisos, etc.
     *
     * @param array $ids Array de IDs a validar
     * @return bool True si todos los IDs son válidos, false en caso contrario
     */
    public static function validarArrayIds(array $ids): bool {
        // Verificar que no esté vacío
        if (empty($ids)) {
            return false;
        }

        // Verificar que cada elemento sea un número entero positivo
        foreach ($ids as $id) {
            // filter_var con FILTER_VALIDATE_INT valida que sea un número entero
            // options con min_range asegura que sea positivo
            if (filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Valida y sanitiza un número entero.
     *
     * @param mixed $valor El valor a validar
     * @param int $min Valor mínimo permitido (opcional)
     * @param int $max Valor máximo permitido (opcional)
     * @return int|false El número entero si es válido, false en caso contrario
     */
    public static function validarEntero($valor, int $min = null, int $max = null) {
        // Opciones de validación
        $options = [];
        if ($min !== null) {
            $options['min_range'] = $min;
        }
        if ($max !== null) {
            $options['max_range'] = $max;
        }

        // Validar que sea un entero dentro del rango especificado
        $resultado = filter_var($valor, FILTER_VALIDATE_INT, ['options' => $options]);

        return $resultado;
    }

    /**
     * Genera un mensaje de error formateado para mostrar al usuario.
     *
     * @param array $errores Array de mensajes de error
     * @return string HTML formateado con los errores
     */
    public static function formatearErrores(array $errores): string {
        if (empty($errores)) {
            return '';
        }

        $html = '<ul style="margin: 0; padding-left: 20px;">';
        foreach ($errores as $error) {
            $html .= '<li>' . htmlspecialchars($error) . '</li>';
        }
        $html .= '</ul>';

        return $html;
    }
}
?>