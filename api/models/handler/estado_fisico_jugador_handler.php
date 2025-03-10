<?php

use Phpml\Regression\LeastSquares;

require('C:/xampp/htdocs/sitio_gol_sv/vendor/autoload.php');
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla Subcontenido.
 */
class EstadoFisicoJugadorHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $idJugador = null;
    protected $altura = null;
    protected $peso = null;
    protected $imc = null;


    //Función para buscar un Estado fisico o varios.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = "SELECT 
    id_estado_fisico_jugador,
    id_jugador,
    altura_jugador,
    peso_jugador,
    indice_masa_corporal,
    fecha_creacion,
    DATE_FORMAT(fecha_creacion, '%d de %M de %Y') AS fecha_creacion_format
    FROM estados_fisicos_jugadores
    WHERE fecha_creacion LIKE ? AND id_jugador = ?
    ORDER BY fecha_creacion DESC;";
        $params = array($value, $this->idJugador);
        return Database::getRows($sql, $params);
    }

    //Función para insertar un estado fisico. 

    public function createRow()
    {
        // Calcular el IMC
        $this->imc = ($this->peso * 0.45359237) / (($this->altura / 100) * ($this->altura / 100));
        $sql = 'INSERT INTO estados_fisicos_jugadores (id_jugador, altura_jugador, peso_jugador, indice_masa_corporal) VALUES(?, ?, ?, ?);';
        $params = array(
            $this->idJugador,
            $this->altura,
            $this->peso,
            $this->imc
        );
        return Database::executeRow($sql, $params);
    }

    //Función para leer todas las un Subcontenido o varios. 
    public function readAll()
    {
        $sql = "SELECT 
        e.id_estado_fisico_jugador,
        e.id_jugador,
        e.altura_jugador,
        e.peso_jugador,
        e.indice_masa_corporal,
        e.fecha_creacion,
        CONCAT(j.nombre_jugador, ' ', j.apellido_jugador) AS nombre_jugador,
        DATE_FORMAT(e.fecha_creacion, '%d de %M de %Y') AS fecha_creacion_format
        FROM estados_fisicos_jugadores e
        INNER JOIN
        jugadores j ON j.id_jugador = e.id_jugador
        WHERE e.id_jugador = ?
        ORDER BY e.fecha_creacion DESC;";
        $params = array($this->idJugador);
        return Database::getRows($sql, $params);
    }

    //Función para leer un Subcontenido o varios. 

    public function readOne()
    {
        $sql = "SELECT 
                id_estado_fisico_jugador,
                id_jugador,
                altura_jugador,
                peso_jugador,
                indice_masa_corporal,
                fecha_creacion,
                DATE_FORMAT(fecha_creacion, '%d de %M de %Y') AS fecha_creacion_format
                FROM estados_fisicos_jugadores WHERE id_estado_fisico_jugador = ?;";
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function readAllMobile()
    {
        $sql = "SELECT 
        e.id_estado_fisico_jugador,
        e.id_jugador,
        e.altura_jugador,
        e.peso_jugador,
        e.indice_masa_corporal,
        e.fecha_creacion,
        CONCAT(j.nombre_jugador, ' ', j.apellido_jugador) AS nombre_jugador,
        DATE_FORMAT(e.fecha_creacion, '%d de %M de %Y') AS fecha_creacion_format
        FROM estados_fisicos_jugadores e
        INNER JOIN
        jugadores j ON j.id_jugador = e.id_jugador
        WHERE e.id_jugador = ? ORDER BY fecha_creacion_format ASC LIMIT 1;";
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function readAllMobilePlayers()
    {
        $sql = "SELECT 
        e.id_estado_fisico_jugador,
        e.id_jugador,
        e.altura_jugador,
        e.peso_jugador,
        e.indice_masa_corporal,
        e.fecha_creacion,
        CONCAT(j.nombre_jugador, ' ', j.apellido_jugador) AS nombre_jugador,
        DATE_FORMAT(e.fecha_creacion, '%d de %M de %Y') AS fecha_creacion_format
        FROM estados_fisicos_jugadores e
        INNER JOIN
        jugadores j ON j.id_jugador = e.id_jugador
        WHERE e.id_jugador = ? ORDER BY fecha_creacion_format ASC LIMIT 1;";
        $params = array($_SESSION['idJugador']);
        return Database::getRow($sql, $params);
    }

    //Función para actualizar un Subcontenido o varios. 

    public function updateRow()
    {
        $this->imc = ($this->peso * 0.45359237) / (($this->altura / 100) * ($this->altura / 100));
        $sql = 'UPDATE estados_fisicos_jugadores SET peso_jugador = ?, altura_jugador = ?, indice_masa_corporal = ?, id_jugador = ? WHERE id_estado_fisico_jugador = ?;';
        $params = array(
            $this->peso,
            $this->altura,
            $this->imc,
            $this->idJugador,
            $this->id
        );
        return Database::executeRow($sql, $params);
    }

    //Función para eliminar un Subcontenido o varios. 

    public function deleteRow()
    {
        $sql = 'DELETE FROM estados_fisicos_jugadores WHERE id_estado_fisico_jugador = ?;';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    //Función para cargar gráfica historica del IMC para el jugador seleccionado.
    public function graphicImcByPlayer()
    {
        $sql = 'SELECT e.indice_masa_corporal AS IMC, 
                 CONCAT(j.nombre_jugador," ",j.apellido_jugador) AS NOMBRE,
                 CONCAT(DATE_FORMAT(e.fecha_creacion, "%e %M %Y")) AS FECHA 
                 FROM estados_fisicos_jugadores e
                 INNER JOIN jugadores j  ON j.id_jugador = e.id_jugador
                 WHERE e.id_jugador = ?
                 AND e.fecha_creacion >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                 ORDER BY e.fecha_creacion ASC;
         ';
        $params = array($this->idJugador);
        return Database::getRows($sql, $params);
    }


    //ESTE SI VARIA LA LINEA
    //Función para cargar gráfica de proyección del IMC para la siguiente semana utilizando regresión lineal.
    public function graphicPredictiveImc()
    {
        // Configurar la localización en español
        setlocale(LC_TIME, 'es_ES.UTF-8');

        // Mapa de traducción de meses
        $monthNames = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];
        // Consulta para obtener los datos de IMC y fecha
        $sql = 'SELECT e.indice_masa_corporal AS IMC, e.fecha_creacion AS FECHA 
            FROM estados_fisicos_jugadores e
            WHERE e.id_jugador = ?
            AND e.fecha_creacion >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)
            ORDER BY e.fecha_creacion ASC;';
        $params = array($this->idJugador);
        $rows = Database::getRows($sql, $params);

        if (empty($rows)) {
            return [];
        }

        // Preparar datos para la regresión
        $dates = [];
        $imcs = [];

        foreach ($rows as $row) {
            $date = new DateTime($row['FECHA']);
            $dates[] = $date->getTimestamp(); // Convertir fecha a timestamp
            $imcs[] = $row['IMC'];
        }

        $predictions = [];
        // Calcular la regresión para cada día de la semana
        for ($i = 1; $i <= 7; $i++) {
            $X = array_slice($dates, 0, count($dates) - ($i - 1)); // Reducir el tamaño de los datos en cada ciclo
            $y = array_slice($imcs, 0, count($imcs) - ($i - 1));

            // Crear el modelo de regresión lineal
            $regression = new LeastSquares();
            $regression->train(array_map(function ($timestamp) {
                return [$timestamp];
            }, $X), $y);

            // Predecir el IMC para el día
            $timestamp = end($dates) + $i * 24 * 60 * 60; // Sumar días en segundos
            $predictedIMC = $regression->predict([$timestamp]);

            // Asegurarse de que la nota no baje de 0 y supere 100
            $predictedIMC = max(0, min($predictedIMC, 100));
            
            // Convertir timestamp a fecha en español
            $dateTime = new DateTime();
            $dateTime->setTimestamp($timestamp);
            $day = $dateTime->format('d');
            $month = (int) $dateTime->format('m');
            $year = $dateTime->format('Y');
            $monthName = $monthNames[$month];

            $date = "$day de $monthName de $year";

            $predictions[] = [
                'fecha' => $date,
                'imc' => $predictedIMC
            ];
        }

        return $predictions;
    }
}
