<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../auxiliares/validator.php');
// Se incluye la clase padre.
require_once('../../modelos/handler/caracteristicas_jugaores_handler.php.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla clientes.
 */
class CaracteristicasJugaoresData extends CaracteristicasJugadoresHandler
{
    // Atributo genérico para manejo de errores.
    private $data_error = null;
    // Atributo para almacenar el nombre del archivo de imagen.
    private $filename = null;

    /*
     *  Métodos para validar y asignar valores de los atributos.
     */

    // Validación y asignación del ID de la caracteristica del jugador.
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador de la caracteristica del jugador es incorrecto';
            return false;
        }
    }
    
    // Validación y asignación del nombre de la caracteristica del jugador
    public function setNombre($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'El nombre de la característica debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->nombre = $value;
            return true;
        } else {
            $this->data_error = 'El nombre debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // validación y asignacion de la clasificacion de la caracteristica del jugador
    public function setClasificacion($value)
    {
        if (Validator::validateString($value)) {
            $this->clasificacion = $value;
            return true;
        } else {
            $this->data_error = 'El identificador de la clasificación es incorrecto';
            return false;
        }
    }


    // Método para obtener el error de los datos.
    public function getDataError()
    {
        return $this->data_error;
    }

    // Método para obtener el nombre del archivo de imagen.
    public function getFilename()
    {
        return $this->filename;
    }
}