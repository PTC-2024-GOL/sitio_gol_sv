<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla subtipología.
 */
class PagoHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $fecha = null;
    protected $cantidad = null;
    protected $tardio = null;
    protected $mora = null;
    protected $mes = null;
    protected $jugador = null;


    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    //Función para buscar una subtipología o varias.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT * FROM vista_pagos
        WHERE NOMBRE LIKE ?
        ORDER BY NOMBRE;';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    //Función para insertar una subtipología.
    public function createRow()
    {
        $sql = 'CALL insertar_pago(?,?,?,?,?,?);';
        $params = array(
            $this->fecha,
            $this->cantidad,
            $this->tardio,
            $this->mora,
            $this->mes,
            $this->jugador

        );
        return Database::executeRow($sql, $params);
    }

    //Función para leer todas las subtipología.
    public function readAll()
    {
        $sql = 'SELECT * FROM vista_pagos;';
        return Database::getRows($sql);
    }

    //Función para leer una tipología.
    public function readOne()
    {
        $sql = 'SELECT p.id_pago AS ID,
                p.fecha_pago AS FECHA,
                p.cantidad_pago AS CANTIDAD,
                p.pago_tardio AS TARDIO,
                p.mora_pago AS MORA,
                p.mes_pago AS MES,
                j.id_jugador AS NOMBRE
                FROM pagos p
                INNER JOIN jugadores j ON p.id_jugador = j.id_jugador
                WHERE p.id_pago LIKE ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }


    //Función para actualizar una SUBtipología.
    public function updateRow()
    {
        $sql = 'CALL actualizar_pago(?,?,?,?,?,?,?);';
        $params = array(
            $this->id,
            $this->fecha,
            $this->cantidad,
            $this->tardio,
            $this->mora,
            $this->mes,
            $this->jugador,
        );
        return Database::executeRow($sql, $params);
    }

    //Función para eliminar una tipología.
    public function deleteRow()
    {
        $sql = 'CALL eliminar_pago(?);';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
