<?php
// Se incluye la clase del modelo.
require_once('../../models/data/horarios_categoria_data.php');
// Se incluye la clase de validación.
require_once('../../helpers/spiderWeb.php');
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $horacat = new HorariosCateData();
    // Se instancia la clase de validación.
    $spider = new SpiderWeb();
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador']) and Validator::validateSessionTime() and $spider->validateKey($_GET['key'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            // Buscar
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $horacat->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            // Crear
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$horacat->setCategoria($_POST['categoriaHora']) or
                    !$horacat->setHorario($_POST['horarioCat'])
                ) {
                    $result['error'] = $horacat->getDataError();
                } elseif ($horacat->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Horario de la categoría creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el horario de la categoría';
                }
                break;
            // Leer todos
            case 'readAll':
                if ($result['dataset'] = $horacat->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen horarios de categorías registrados';
                }
                break;
            // Leer uno
            case 'readOne':
                if (!$horacat->setIdHorarioCate($_POST['idHorarioCate'])) {
                    $result['error'] = $horacat->getDataError();
                } elseif ($result['dataset'] = $horacat->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Horario de categoría inexistente';
                }
                break;
            // Leer uno
            case 'onlyDetail':
                if (!$horacat->setCategoria($_POST['idCategoria'])) {
                    $result['error'] = $horacat->getDataError();
                } elseif ($result['dataset'] = $horacat->onlyDetail()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Detalle de horario de la categoría inexistente';
                }
                break;
            // Actualizar
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$horacat->setIdHorarioCate($_POST['idHorarioCate']) or
                    !$horacat->setCategoria($_POST['categoriaHora']) or
                    !$horacat->setHorario($_POST['horarioCat'])
                ) {
                    $result['error'] = $horacat->getDataError();
                } elseif ($horacat->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Horario de categoría modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el horario de la categoría';
                }
                break;
            // Eliminar
            case 'deleteRow':
                if (
                    !$horacat->setIdHorarioCate($_POST['idHorarioCate'])
                ) {
                    $result['error'] = $horacat->getDataError();
                } elseif ($horacat->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Horario de categoría eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el horario de la categoría';
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
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
