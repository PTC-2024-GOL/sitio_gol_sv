<?php
/*
 *	Clase para validar todos los datos de entrada del lado del servidor.
 */
class Validator
{
    /*
     *   Atributos para manejar algunas validaciones.
     */
    private static $filename = null;
    private static $search_value = null;
    private static $password_error = null;
    private static $file_error = null;
    private static $search_error = null;

    // Método para obtener el error al validar una contraseña.
    public static function getPasswordError()
    {
        return self::$password_error;
    }

    // Método para obtener el nombre del archivo validado.
    public static function getFilename()
    {
        return self::$filename;
    }

    // Método para obtener el error al validar un archivo.
    public static function getFileError()
    {
        return self::$file_error;
    }

    // Método para obtener el valor de búsqueda.
    public static function getSearchValue()
    {
        return self::$search_value;
    }

    // Método para obtener el error al validar una búsqueda.
    public static function getSearchError()
    {
        return self::$search_error;
    }

    /*
     *   Método para sanear todos los campos de un formulario (quitar los espacios en blanco al principio y al final).
     *   Parámetros: $fields (arreglo con los campos del formulario).
     *   Retorno: arreglo con los campos saneados del formulario.
     */
    public static function validateForm($fields)
    {
        foreach ($fields as $index => $value) {
            $value = trim($value);
            $fields[$index] = $value;
        }
        return $fields;
    }

    /*
     *   Método para validar un número natural como por ejemplo llave primaria, llave foránea, entre otros.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateNaturalNumber($value)
    {
        // Se verifica que el valor sea un número entero mayor o igual a uno.
        if (filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)))) {
            return true;
        } else {
            return false;
        }
    }

    public static function validatePositiveNumber($value)
    {
        // Se verifica que el valor sea un número entero mayor o igual a 0.
        if ($value >= 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function validatePositiveNumber2($value)
    {
        // Se verifica que el valor sea un número entero mayor a 0 y menor a 100.
        if ($value > 0 && $value < 100) {
            return true;
        } else {
            return false;
        }
    }


    /*
         *   Método para validar un archivo de imagen.
         *   Parámetros: $file (archivo de un formulario) y $dimension (medida mínima para la imagen).
         *   Retorno: booleano (true si el archivo es correcto o false en caso contrario).
         */
    public static function validateImageFile($file, $dimension)
    {
        if (is_uploaded_file($file['tmp_name'])) {
            // Se obtienen los datos de la imagen.
            $image = getimagesize($file['tmp_name']);
            // Se comprueba si el archivo tiene un tamaño mayor a 2MB.
            if ($file['size'] > 2097152) {
                self::$file_error = 'El tamaño de la imagen debe ser menor a 2MB';
                return false;
            } elseif ($image['mime'] == 'image/jpeg' || $image['mime'] == 'image/png') {
                // Se obtiene la extensión del archivo (.jpg o .png) y se convierte a minúsculas.
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                // Se establece un nombre único para el archivo.
                self::$filename = uniqid() . '.' . $extension;
                return true;
            } else {
                self::$file_error = 'El tipo de imagen debe ser jpg o png';
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un correo electrónico.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateEmail($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un dato booleano.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateBoolean($value)
    {
        if ($value == 1 || $value == 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar una cadena de texto (letras, digitos, espacios en blanco y signos de puntuación).
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateString($value)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        if (preg_match('/^[a-zA-Z0-9ñÑáÁéÉíÍóÓúÚ\s\,\;\.]+$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
 *   Método para validar una cadena de texto (letras, dígitos, espacios en blanco, signos de puntuación y guiones).
 *   Parámetros: $value (dato a validar).
 *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
 */
    public static function validateStringText($value)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        if (preg_match('/^[a-zA-Z0-9ñÑáÁéÉíÍóÓúÚ\s\,\;\.\-]+$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un dato alfabético (letras y espacios en blanco).
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateAlphabetic($value)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        if (preg_match('/^[a-zA-ZñÑáÁéÉíÍóÓúÚ\s]+$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un dato alfanumérico (letras, dígitos y espacios en blanco).
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateAlphanumeric($value)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        if (preg_match('/^[a-zA-Z0-9ñÑáÁéÉíÍóÓúÚ\s]+$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar la longitud de una cadena de texto.
     *   Parámetros: $value (dato a validar), $min (longitud mínima) y $max (longitud máxima).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateLength($value, $min, $max)
    {
        // Se verifica la longitud de la cadena de texto.
        if (strlen($value) >= $min && strlen($value) <= $max) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un dato monetario.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateMoney($value)
    {
        // Se verifica que el número tenga una parte entera y como máximo dos cifras decimales.
        if (preg_match('/^[0-9]+(?:\.[0-9]{1,2})?$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar una contraseña.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validatePassword($value)
    {
        // Se verifica la longitud mínima.
        if (strlen($value) < 8) {
            self::$password_error = 'Clave menor a 8 caracteres';
            return false;
        } elseif (strlen($value) > 72) {
            self::$password_error = 'Clave mayor a 72 caracteres';
            return false;
        } elseif (preg_match('/\s/', $value)) {
            self::$password_error = 'Clave contiene espacios en blancos ';
            return false;
        } elseif (!preg_match('/\W/', $value)) {
            self::$password_error = 'Clave debe contener al menos un caracter especial';
            return false;
        } elseif (!preg_match('/\d/', $value)) {
            self::$password_error = 'Clave debe contener al menos un dígito';
            return false;
        } elseif (!preg_match('/[a-z]/', $value)) {
            self::$password_error = 'Clave debe contener al menos una letra en minúsculas';
            return false;
        } elseif (preg_match('/[A-Z]/', $value)) {
            return true;
        } else {
            self::$password_error = 'Clave debe contener al menos una letra en mayusculas';
            return false;
        }
    }

    /*
     *   Método para validar el formato del DUI (Documento Único de Identidad).
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateDUI($value)
    {
        // Se verifica que el número tenga el formato 00000000-0.
        if (preg_match('/^[0-9]{8}[-][0-9]{1}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un número telefónico.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validatePhone($value)
    {
        // Se verifica que el número tenga el formato 0000-0000 y que inicie con 2, 6 o 7.
        if (preg_match('/^[2,6,7]{1}[0-9]{3}[-][0-9]{4}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar una fecha.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateDate($value)
    {
        // Se dividen las partes de la fecha y se guardan en un arreglo con el siguiene orden: año, mes y día.
        $date = explode('-', $value);
        if (checkdate($date[1], $date[2], $date[0])) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un valor de búsqueda.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateSearch($value)
    {
        if (trim($value) == '') {
            self::$search_error = 'Ingrese un valor para buscar';
            return false;
        } elseif (str_word_count($value) > 3) {
            self::$search_error = 'La búsqueda contiene más de 3 palabras';
            return false;
        } elseif (self::validateString($value)) {
            self::$search_value = $value;
            return true;
        } else {
            self::$search_error = 'La búsqueda contiene caracteres prohibidos';
            return false;
        }
    }

    public static function validateSearch2($value)
    {
        if (trim($value) == '') {
            self::$search_error = 'Ingrese un valor para buscar';
            return false;
        } elseif (str_word_count($value) > 3) {
            self::$search_error = 'La búsqueda contiene más de 3 palabras';
            return false;
        } elseif (self::validateStringText($value)) {
            self::$search_value = $value;
            return true;
        } else {
            self::$search_error = 'La búsqueda contiene caracteres prohibidos';
            return false;
        }
    }

    /*
     *   Método para validar un archivo al momento de subirlo al servidor.
     *   Parámetros: $file (archivo), $path (ruta del archivo) y $name (nombre del archivo).
     *   Retorno: booleano (true si el archivo fue subido al servidor o false en caso contrario).
     */
    public static function saveFile($file, $path)
    {
        if (!$file) {
            return false;
        } elseif (move_uploaded_file($file['tmp_name'], $path . self::$filename)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar el cambio de un archivo en el servidor.
     *   Parámetros: $file (archivo), $path (ruta del archivo) y $old_filename (nombre del archivo anterior).
     *   Retorno: booleano (true si el archivo fue subido al servidor o false en caso contrario).
     */
    public static function changeFile($file, $path, $old_filename)
    {
        if (!self::saveFile($file, $path)) {
            return false;
        } elseif (self::deleteFile($path, $old_filename)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un archivo al momento de borrarlo del servidor.
     *   Parámetros: $path (ruta del archivo) y $filename (nombre del archivo).
     *   Retorno: booleano (true si el archivo fue borrado del servidor o false en caso contrario).
     */
    public static function deleteFile($path, $filename)
    {
        if ($filename == 'default.png') {
            return true;
        } elseif (@unlink($path . $filename)) {
            return true;
        } else {
            return false;
        }
    }

    public static function validateSessionTime()
    {
        //Tiempo en segundos para dar vida a la sesión.
        $inactivo = 1800; //Tiempo en segundos.

        //Calculamos tiempo de vida inactivo.
        $vida_session = time() - $_SESSION['tiempo'];

        //Compraración para redirigir página, si la vida de sesión sea mayor a el tiempo insertado en inactivo.
        if ($vida_session > $inactivo) {
            //Destruimos sesión.
            session_destroy();
            return false;
        } else { // si no ha caducado la sesion, actualizamos
            $_SESSION['tiempo'] = time();
            return true;
        }
    }

    public static function validateAttemptsTime($tiempo)
    {
        if ($tiempo != null) {
            //Tiempo en segundos para dar vida a la sesión.
            $temporizador = 30; //Tiempo en segundos.

            //Calculamos tiempo de vida inactivo.
            $cuenta = time() - $tiempo;

            //Compraración para redirigir página, si la vida de sesión sea mayor a el tiempo insertado en inactivo.
            if ($cuenta > $temporizador) {
                //Destruimos sesión.
                return true;
            } else { // si no ha caducado la sesion, actualizamos
                return false;
            }
        } else {
            return true;
        }
    }


    /*
     *   Método para validar un formato de hora (HH:MM).
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateTime($value)
    {
        // Se verifica que el formato sea HH:MM usando expresiones regulares.
        if (preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar una fecha (mayor a 18 años).
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateDateBirthday($value)
    {
        // Se dividen las partes de la fecha y se guardan en un arreglo en el siguiene orden: año, mes y día.
        $datev = strtotime($value);
        $datem = strtotime('-18 years', time());
        $datea = strtotime('-122 years', time());
        if ($datev > $datem) {
            return false;
        } elseif ($datev < $datea) {
            return false;
        } else {
            $date = explode('-', $value);
            if (checkdate($date[1], $date[2], $date[0])) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*
     *   Método para validar un número decimal positivo.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validatePositiveDecimal($value)
    {
        // Se verifica que el valor sea un número decimal positivo.
        if (filter_var($value, FILTER_VALIDATE_FLOAT) && $value > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function validatePlayerDateBirthday($value)
    {
        // Se dividen las partes de la fecha y se guardan en un arreglo en el siguiene orden: año, mes y día.
        $datev = strtotime($value);
        $datem = strtotime('-6 years', time());
        $datea = strtotime('-30 years', time());
        if ($datev > $datem) {
            return false;
        } elseif ($datev < $datea) {
            return false;
        } else {
            $date = explode('-', $value);
            if (checkdate($date[1], $date[2], $date[0])) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function validatePositiveDecimal2($value)
    {
        // Se verifica que el valor sea un número decimal positivo.
        if (filter_var($value, FILTER_VALIDATE_FLOAT) && $value > 0 && $value < 11) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para un dato de tipo DATETIME
     *   Prametros: $value (dato a validar)
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario)
     *   Este método valida el formato de la fecha y la hora en formato ISO 8601
     */
    public static function validateDateTime($value)
    {
        // Verifica que el formato sea correcto usando expresiones regulares: YYYY-MM-DDTHH:MM[:SS]
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(2[0-3]|[01][0-9]):([0-5][0-9])(?::([0-5][0-9]))?$/', $value, $matches)) {
            // Extrae las partes de la fecha y la hora
            $year = $matches[1];
            $month = $matches[2];
            $day = $matches[3];
            $hour = isset($matches[4]) ? $matches[4] : '01';
            $minute = isset($matches[5]) ? $matches[5] : '00';
            // Si los segundos no están presentes, se asigna '00'
            $second = isset($matches[6]) ? $matches[6] : '00';

            // Verifica la validez de la fecha
            if (checkdate($month, $day, $year)) {
                // Retorna la fecha en el formato que MySQL acepta: YYYY-MM-DD HH:MM:SS
                return "$year-$month-$day $hour:$minute:$second";
            }
        }
        return false;
    }
}
