<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla plantillas.
 */
class JugadoresHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $dorsalJ = null;
    protected $nombreJ = null;
    protected  $apellidoJ = null;
    protected $estatusJ = null;
    protected $nacimientoJ = null;
    protected $generoJ = null;
    protected $perfilJ = null;
    protected $becado = null;
    protected $posicionPrincipal = null;
    protected $posicionSecundaria = null;
    protected $claveJ = null;
    protected $fotoJ = null;
    protected $alias = null;

    protected $telefono = null;
    protected $telefono_emergencia = null;
    protected $correoJ = null;
    protected $tipo_sangreJ = null;
    protected $observacion_medica = null;

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../images/jugadores/';

    
    /*
     *  Métodos para gestionar la cuenta del Jugador.
     */

    //Función para chequear el usuario de un admministrador en el login, sin el procedimiento almacenado.
    
    public function checkUser($username, $password)
    {
        $sql = 'SELECT id_jugador, correo_jugador, 
                clave_jugador, estatus_jugador, 
                foto_jugador, alias_jugador, 
                nombre_jugador, apellido_jugador
                FROM jugadores
                WHERE (BINARY alias_jugador = ? OR BINARY correo_jugador = ?)';
        $params = array($username,$username);
        $data = Database::getRow($sql, $params);
        if (password_verify($password, $data['clave_jugador'])) {
            $this->id = $data['id_jugador'];
            $this->correoJ = $data['correo_jugador'];
            $this->alias = $data['alias_jugador'];
            $this->estatusJ = $data['estatus_jugador'];
            $this->fotoJ = $data['foto_jugador'];
            $this->nombreJ = $data['nombre_jugador'];
            $this->apellidoJ = $data['apellido_jugador'];
            return true;
        } else {
            return false;
        }
    }

    // Función que chequea el estado
    public function checkStatus()
    {
        if ($this->estatusJ == "Activo") {
            $_SESSION['idJugador'] = $this->id;
            $_SESSION['correoJugador'] = $this->correoJ;
            $_SESSION['fotoJugador'] = $this->fotoJ;
            $_SESSION['aliasJugador'] = $this->alias;
            $_SESSION['nombreJugador'] = $this->nombreJ;
            $_SESSION['apellidoJugador'] = $this->apellidoJ;
            return true;
        } else {
            return false;
        }
    }

    //Función para chequear la contraseña de un admministrador.
    public function checkPassword($password)
    {
        $sql = 'SELECT clave_jugador AS CLAVE
                FROM jugadores
                WHERE id_jugador = ?';
        $params = array($_SESSION['idJugador']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['CLAVE'])) {
            return true;
        } else {
            return false;
        }
    }

    //Función para cambiar la contraseña de un admministrador.
    public function changePassword()
    {
        $sql = 'UPDATE jugadores
                SET clave_jugador = ?, fecha_clave = NOW()
                WHERE id_jugador = ?';
        $params = array($this->claveJ, $_SESSION['idJugador']);
        return Database::executeRow($sql, $params);
    }


    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    //Función para buscar un jugador.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT * FROM vista_jugadores
        WHERE nombre_jugador LIKE ? OR apellido_jugador LIKE ?
        ORDER BY fecha_creacion;';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    //Función para insertar un jugador.
    public function createRow()
    {
        $sql = 'CALL insertar_jugador (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $params = array(
            $this->dorsalJ,
            $this->nombreJ,
            $this->apellidoJ,
            $this->estatusJ,
            $this->nacimientoJ,
            $this->generoJ,
            $this->perfilJ,
            $this->becado,
            $this->posicionPrincipal,
            $this->posicionSecundaria,
            $this->claveJ,
            $this->fotoJ,
            $this->telefono,
            $this->telefono_emergencia,
            $this->correoJ,
            $this->tipo_sangreJ,
            $this->observacion_medica
        );
        return Database::executeRow($sql, $params);
    }


    //Función para mostrar todos los jugadores
    public function readAll()
    {
        $sql = 'SELECT * FROM vista_jugadores ORDER BY fecha_creacion;';
        return Database::getRows($sql);
    }

    //Función para mostrar todos los jugadores filtrados por el genero
    public function readAllByGender()
    {
        $sql = 'SELECT * FROM vista_jugadores WHERE genero_jugador = ? ORDER BY fecha_creacion;';
        $params = array($this->generoJ);
        return Database::getRows($sql, $params);
    }


    //Función para mostrar uno de los jugadores
    public function readOne()
    {
        $sql = 'SELECT * FROM vista_jugadores
                WHERE id_jugador= ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function readFilename()
    {
        $sql = 'SELECT foto_jugador
                FROM jugadores
                WHERE id_jugador = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }


    //Función para actualizar jugadores
    public function updateRow()
    {
        $sql = 'CALL actualizar_jugador (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $params = array(
            $this->id,
            $this->dorsalJ,
            $this->nombreJ,
            $this->apellidoJ,
            $this->estatusJ,
            $this->nacimientoJ,
            $this->generoJ,
            $this->perfilJ,
            $this->becado,
            $this->posicionPrincipal,
            $this->posicionSecundaria,
            $this->fotoJ,
            $this->telefono,
            $this->telefono_emergencia,
            $this->correoJ,
            $this->tipo_sangreJ,
            $this->observacion_medica
        );
        return Database::executeRow($sql, $params);
    }

    //Función para eliminar una jugador.
    public function deleteRow()
    {
        $sql = 'DELETE FROM jugadores WHERE id_jugador = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    ///FUNCIONES PARA MOVIL DE JugadorS - PANTALLA DE RENDIMIENTO

    public function matchesByPlayer()
    {
        $sql = 'SELECT COUNT(id_participacion) AS partidos FROM participaciones_partidos 
                WHERE id_jugador = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function promByPlayer()
    {
        $sql = 'SELECT round(AVG(nota_caracteristica_analisis),1) AS notaGlobal FROM caracteristicas_analisis 
                WHERE id_jugador = ?;';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function notesByPlayer()
    {
        $sql = 'SELECT * FROM notas_por_jugador WHERE id_jugador = ?';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    public function promByPlayerMobilePlayers()
    {
        $sql = 'SELECT round(AVG(nota_caracteristica_analisis),1) AS notaGlobal FROM caracteristicas_analisis 
                WHERE id_jugador = ?;';
        $params = array($_SESSION['idJugador']);
        return Database::getRow($sql, $params);
    }

    public function matchesByPlayeMobilePLayers()
    {
        $sql = 'SELECT COUNT(id_participacion) AS partidos FROM participaciones_partidos 
                WHERE id_jugador = ?';
        $params = array($_SESSION['idJugador']);
        return Database::getRow($sql, $params);
    }

    public function notesByPlayerMobilePlayers()
    {
        $sql = 'SELECT * FROM notas_por_jugador WHERE id_jugador = ?';
        $params = array($_SESSION['idJugador']);
        return Database::getRows($sql, $params);
    }

    public function readOneMobile()
    {
        $sql = 'SELECT * FROM vista_jugadores
                WHERE id_jugador= ?';
        $params = array($_SESSION['idJugador']);
        return Database::getRow($sql, $params);
    }

    public function readOneStats()
    {
        $sql = 'SELECT  COALESCE(SUM(goles), 0) AS TOTAL_GOLES,
		COALESCE(SUM(asistencias), 0) AS TOTAL_ASISTENCIAS,
        COALESCE(SUM(minutos_jugados), 0) AS MINUTOS_JUGADOS,
        COUNT(id_partido) AS TOTAL_PARTIDOS,
        COALESCE(SUM(CASE WHEN da.amonestacion = "Tarjeta amarilla" THEN da.numero_amonestacion ELSE 0 END),0) AS TARJETAS_AMARILLAS,
        COALESCE(SUM(CASE WHEN da.amonestacion = "Tarjeta roja" THEN da.numero_amonestacion ELSE 0 END),0) AS TARJETAS_ROJAS
        FROM participaciones_partidos p
        LEFT JOIN detalles_amonestaciones da ON da.id_participacion = p.id_participacion
        WHERE id_jugador = ? AND (titular = 1 OR sustitucion = 1);';
        $params = array($_SESSION['idJugador']);
        return Database::getRow($sql, $params);
    }
    
    public function maximosGoleadores()
    {
        $sql = 'SELECT * FROM vista_maximos_goleadores 
        WHERE IDE = (SELECT id_equipo FROM plantillas_equipos WHERE id_jugador = ? LIMIT 1) AND TOTAL_GOLES > 0 LIMIT 5;';
        $params = array($_SESSION['idJugador']);
        return Database::getRows($sql, $params);
    }
    
    public function maximosAsistentes()
    {
        $sql = 'SELECT * FROM vista_maximos_asistentes 
        WHERE IDE = (SELECT id_equipo FROM plantillas_equipos WHERE id_jugador = ? LIMIT 1) AND TOTAL_ASISTENCIAS > 0 LIMIT 5;';
        $params = array($_SESSION['idJugador']);
        return Database::getRows($sql, $params);
    }

    public function partidosJugados()
    {
        $sql = 'SELECT 
        pt.id_partido,
        DATE_FORMAT(pt.fecha_partido, "%e de %M del %Y") AS fecha,
        pt.fecha_partido,
        pt.localidad_partido,
        pt.resultado_partido,
        r.logo_rival,
        e.logo_equipo,
        e.nombre_equipo,
        r.nombre_rival AS nombre_rival,
        pt.tipo_resultado_partido,
        e.id_equipo,
        r.id_rival,
        e.id_categoria,
        p.id_jugador 
        FROM participaciones_partidos p 
        INNER JOIN partidos pt ON pt.id_partido = p.id_partido
        INNER JOIN equipos e ON e.id_equipo = pt.id_equipo
        INNER JOIN rivales r ON r.id_rival = pt.id_rival
        WHERE id_jugador = ? AND (titular = 1 OR sustitucion = 1)
        ORDER BY pt.fecha_partido DESC;';
        $params = array($_SESSION['idJugador']);
        return Database::getRows($sql, $params);
    }

    public function golesMarcados()
    {
        $sql = 'SELECT 
        pt.id_partido,
        DATE_FORMAT(pt.fecha_partido, "%e de %M del %Y") AS fecha,
        pt.fecha_partido,
        pt.localidad_partido,
        pt.resultado_partido,
        r.logo_rival,
        r.nombre_rival AS nombre_rival,
        pt.tipo_resultado_partido,
        dg.cantidad_tipo_gol AS CANTIDAD,
        tg.nombre_tipo_gol AS TIPO
        FROM participaciones_partidos p 
        INNER JOIN partidos pt ON pt.id_partido = p.id_partido
        INNER JOIN equipos e ON e.id_equipo = pt.id_equipo
        INNER JOIN rivales r ON r.id_rival = pt.id_rival
        LEFT JOIN detalles_goles dg ON dg.id_participacion = p.id_participacion
        INNER JOIN tipos_goles tg ON dg.id_tipo_gol = tg.id_tipo_gol
        WHERE id_jugador = ? AND (titular = 1 OR sustitucion = 1) AND p.goles > 0
        ORDER BY pt.fecha_partido DESC;';
        $params = array($_SESSION['idJugador']);
        return Database::getRows($sql, $params);
    }

    public function asistenciasHechas()
    {
        $sql = 'SELECT 
        pt.id_partido,
        DATE_FORMAT(pt.fecha_partido, "%e de %M del %Y") AS fecha,
        pt.fecha_partido,
        pt.localidad_partido,
        pt.resultado_partido,
        r.logo_rival,
        r.nombre_rival AS nombre_rival,
        pt.tipo_resultado_partido,
        p.asistencias
        FROM participaciones_partidos p 
        INNER JOIN partidos pt ON pt.id_partido = p.id_partido
        INNER JOIN equipos e ON e.id_equipo = pt.id_equipo
        INNER JOIN rivales r ON r.id_rival = pt.id_rival
        WHERE id_jugador = ? AND (titular = 1 OR sustitucion = 1) AND p.asistencias > 0
        ORDER BY pt.fecha_partido DESC;';
        $params = array($_SESSION['idJugador']);
        return Database::getRows($sql, $params);
    }

    public function minutosJugados()
    {
        $sql = 'SELECT 
        pt.id_partido,
        DATE_FORMAT(pt.fecha_partido, "%e de %M del %Y") AS fecha,
        pt.fecha_partido,
        pt.localidad_partido,
        pt.resultado_partido,
        r.logo_rival,
        r.nombre_rival AS nombre_rival,
        pt.tipo_resultado_partido,
        p.minutos_jugados
        FROM participaciones_partidos p 
        INNER JOIN partidos pt ON pt.id_partido = p.id_partido
        INNER JOIN equipos e ON e.id_equipo = pt.id_equipo
        INNER JOIN rivales r ON r.id_rival = pt.id_rival
        WHERE id_jugador = ? AND (titular = 1 OR sustitucion = 1) AND p.minutos_jugados > 0
        ORDER BY pt.fecha_partido DESC;';
        $params = array($_SESSION['idJugador']);
        return Database::getRows($sql, $params);
    }

    public function contarTarjetasAmarillas()
    {
        $sql = 'SELECT 
        pt.id_partido,
        DATE_FORMAT(pt.fecha_partido, "%e de %M del %Y") AS fecha,
        pt.fecha_partido,
        pt.localidad_partido,
        pt.resultado_partido,
        r.logo_rival,
        r.nombre_rival AS nombre_rival,
        pt.tipo_resultado_partido,
        p.minutos_jugados,
        da.amonestacion,
        da.numero_amonestacion
        FROM participaciones_partidos p 
        INNER JOIN partidos pt ON pt.id_partido = p.id_partido
        INNER JOIN equipos e ON e.id_equipo = pt.id_equipo
        INNER JOIN rivales r ON r.id_rival = pt.id_rival
        LEFT JOIN detalles_amonestaciones da ON da.id_participacion = p.id_participacion
        WHERE id_jugador = ? AND da.amonestacion = "Tarjeta amarilla"
        ORDER BY pt.fecha_partido DESC;';
        $params = array($_SESSION['idJugador']);
        return Database::getRows($sql, $params);
    }

    public function contarTarjetasRojas()
    {
        $sql = 'SELECT 
        pt.id_partido,
        DATE_FORMAT(pt.fecha_partido, "%e de %M del %Y") AS fecha,
        pt.fecha_partido,
        pt.localidad_partido,
        pt.resultado_partido,
        r.logo_rival,
        r.nombre_rival AS nombre_rival,
        pt.tipo_resultado_partido,
        p.minutos_jugados,
        da.amonestacion,
        da.numero_amonestacion
        FROM participaciones_partidos p 
        INNER JOIN partidos pt ON pt.id_partido = p.id_partido
        INNER JOIN equipos e ON e.id_equipo = pt.id_equipo
        INNER JOIN rivales r ON r.id_rival = pt.id_rival
        LEFT JOIN detalles_amonestaciones da ON da.id_participacion = p.id_participacion
        WHERE id_jugador = ? AND da.amonestacion = "Tarjeta roja"
        ORDER BY pt.fecha_partido DESC;';
        $params = array($_SESSION['idJugador']);
        return Database::getRows($sql, $params);
    }


    public function graphicMobile()
    {
        $sql = 'SELECT cj.clasificacion_caracteristica_jugador AS caracteristica,
        ROUND(AVG(C.nota_caracteristica_analisis), 2) AS promedio
        FROM caracteristicas_analisis c
        INNER JOIN caracteristicas_jugadores cj ON c.id_caracteristica_jugador = cj.id_caracteristica_jugador
        WHERE c.id_jugador = ? GROUP BY cj.clasificacion_caracteristica_jugador;';
        $params = array($_SESSION['idJugador']);
        return Database::getRows($sql, $params);
    }
}
