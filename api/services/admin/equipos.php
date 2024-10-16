<?php
// Se incluye la clase del modelo.
require_once('../../models/data/equipos_data.php');
// Se incluye la clase de validación.
require_once('../../helpers/spiderWeb.php');
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $equipo = new EquiposData;
    // Se instancia la clase de validación.
    $spider = new SpiderWeb();
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
   // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
   if (isset($_SESSION['idAdministrador']) and Validator::validateSessionTime() and $spider->validateKey($_GET['key'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            // Buscar
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $equipo->searchRows()) {
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
                    !$equipo->setNombreEquipo($_POST['nombreEquipo']) or
                    !$equipo->setGeneroEquipo($_POST['generoEquipo']) or
                    !$equipo->setTelefono($_POST['telefonoEquipo']) or
                    !$equipo->setIdCuerpoTecnico($_POST['idCuerpoTecnico']) or
                    !$equipo->setIdCategoria($_POST['idCategoria']) or
                    !$equipo->setLogoEquipo($_FILES['logoEquipo'])
                ) {
                    $result['error'] = $equipo->getDataError();
                } elseif ($equipo->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Equipo creado correctamente';
                    // Se asigna el estado del archivo después de insertar.
                    $result['fileStatus'] = Validator::saveFile($_FILES['logoEquipo'], $equipo::RUTA_IMAGEN);
                } else {
                    $result['error'] = 'Ocurrió un problema al crear al equipo';
                }
                break;
            // Leer todos
            case 'readAll':
                if ($result['dataset'] = $equipo->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen equipos registrados';
                }
                break;
            // Leer uno
            case 'readOne':
                if (!$equipo->setId($_POST['idEquipo'])) {
                    $result['error'] = $equipo->getDataError();
                } elseif ($result['dataset'] = $equipo->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Equipo inexistente';
                }
                break;
            // Leer cuerpo tecnico del equipo
            case 'readAllStaff':
                if (!$equipo->setId($_POST['idEquipo'])) {
                    $result['error'] = $equipo->getDataError();
                } elseif ($result['dataset'] = $equipo->readAllStaff()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Equipo inexistente';
                }
                break;
            // Leer todos los equipos filtrados por genero
            case 'readAllByGender':
                if (!$equipo->setGeneroEquipo($_POST['generoEquipo'])) {
                    $result['error'] = $equipo->getDataError();
                } elseif ($result['dataset'] = $equipo->readAllByGender()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Equipos inexistente';
                }
                break;
            // Actualizar
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$equipo->setId($_POST['idEquipo']) or
                    !$equipo->setFilename() or
                    !$equipo->setNombreEquipo($_POST['nombreEquipo']) or
                    !$equipo->setGeneroEquipo($_POST['generoEquipo']) or
                    !$equipo->setTelefono($_POST['telefonoEquipo']) or
                    !$equipo->setIdCuerpoTecnico($_POST['idCuerpoTecnico']) or
                    !$equipo->setIdCategoria($_POST['idCategoria']) or
                    !$equipo->setLogoEquipo($_FILES['logoEquipo'], $equipo->getFilename())

                ) {
                    $result['error'] = $equipo->getDataError();
                } elseif ($equipo->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Equipo modificado correctamente';
                    // Se asigna el estado del archivo después de actualizar.
                    $result['fileStatus'] = Validator::changeFile($_FILES['logoEquipo'], $equipo::RUTA_IMAGEN, $equipo->getFilename());
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el equipo';
                }
                break;
            // Eliminar
            case 'deleteRow':
                if (
                    !$equipo->setId($_POST['idEquipo'])
                ) {
                    $result['error'] = $equipo->getDataError();
                } elseif ($equipo->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Equipo eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el equipo.';
                }
                break;
            // Leer uno
            case 'countTeamsByCategory':
                if ($result['dataset'] = $equipo->countTeamsByCategory()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Aún no hay equipos en esta categoría';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna a la petición.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
