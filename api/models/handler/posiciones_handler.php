<?php

// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla tipos de jugadas.
 */

class PosicionesHandler{

    //Declaracion de variables aqui
    protected $idPosicion = null;
    protected $posicion = null;
    protected $areaJuego = null;

    /*
    *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */

    //Función para buscar una posición.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT * FROM posiciones
        WHERE posicion LIKE ? OR area_de_juego LIKE ?
        ORDER BY posicion;';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    //Función para insertar una posición.
    public function createRow()
    {
        $sql = 'CALL sp_insertar_posicion(?,?);';
        $params = array(
            $this->posicion,
            $this->areaJuego
        );
        return Database::executeRow($sql, $params);
    }

    //Función para leer todas las posiciones.
    public function readAll()
    {
        $sql = 'SELECT * FROM posiciones
        ORDER BY posicion;';
        return Database::getRows($sql);
    }

    //Función para leer una posición.
    public function readOne()
    {
        $sql = 'SELECT * FROM posiciones
        WHERE id_posicion LIKE ?';
        $params = array($this->idPosicion);
        return Database::getRow($sql, $params);
    }

    //Función para actualizar una posición.
    public function updateRow()
    {
        $sql = 'CALL sp_actualizar_posicion(?,?,?);';
        $params = array(
            $this->idPosicion,
            $this->posicion,
            $this->areaJuego
        );
        return Database::executeRow($sql, $params);
    }

    //Función para eliminar una posición.
    public function deleteRow()
    {
        $sql = 'CALL sp_eliminar_posicion(?);';
        $params = array($this->idPosicion);
        return Database::executeRow($sql, $params);
    }

    //Función para generar una gráfica de jugadores por posicón
    public function graphic()
    {
        $sql = 'SELECT COUNT(j.id_jugador) AS TOTAL, p.posicion AS POSICION 
                FROM jugadores j
                INNER JOIN posiciones p ON j.id_posicion_principal = p.id_posicion
                GROUP BY p.posicion
                ORDER BY TOTAL DESC;';
        return Database::getRows($sql);
    }

}

