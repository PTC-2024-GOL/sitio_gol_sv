<?php
// Se incluye la clase del modelo.
require_once('../../models/data/participaciones_partidos_data.php');
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $participacion = new ParticipacionesPartidosData();
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
    switch ($_GET['action']) {
            // Leer todos los jugadores de un equipo
        case 'participationMatch':
            if (!$participacion->setIdPartido($_POST['idPartido'])) {
                $result['error'] = $participacion->getDataError();
            } elseif ($result['dataset'] = $participacion->participationMatch()) {
                $result['status'] = 1;
            } else {
                $result['error'] = 'Aún no hay jugadores agregados a este equipo';
            }
            break;
            // Leer todos los jugadores de un equipo
        case 'alineacionPartido':
            if (!$participacion->setIdPartido($_POST['idPartido'])) {
                $result['error'] = $participacion->getDataError();
            } elseif ($result['dataset'] = $participacion->alineacionPartido()) {
                $result['status'] = 1;
            } else {
                $result['error'] = 'Aún no hay jugadores agregados a este equipo';
            }
            break;
        case 'readAllParticipation':
            if (!$participacion->setIdEquipo($_POST['idEquipo'])) {
                $result['error'] = $participacion->getDataError();
            } elseif ($result['dataset'] = $participacion->readAllParticipationPublic()) {
                $result['status'] = 1;
            } else {
                $result['error'] = 'Aún no hay jugadores agregados a este equipo';
            }
            break;
        case 'filterAllParticipationPublic':
            if (!$participacion->setIdEquipo($_POST['idEquipo']) or
                !$participacion->setIdPosicion($_POST['idPosicion'])
            ) {
                $result['error'] = $participacion->getDataError();
            } elseif ($result['dataset'] = $participacion->filterAllParticipationPublic()) {
                $result['status'] = 1;
            } else {
                $result['error'] = 'Aún no hay jugadores agregados a este equipo';
            }
            break;
        case 'positionParticipation':
            if ($result['dataset'] = $participacion->readPositionParticipation()) {
                $result['status'] = 1;
                $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
            } else {
                $result['error'] = 'No existen equipos registrados';
                $result['status'] = 0;
            }
            break;
        default:
            $result['error'] = 'Acción no disponible dentro de la sesión';
    }
    // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
    $result['exception'] = Database::getException();
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('Content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al conttipos de lesionador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
