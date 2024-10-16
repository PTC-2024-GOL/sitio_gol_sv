/*
* Controlador de uso general en las páginas web del sitio privado.
* Sirve para manejar la plantilla del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'services/technics/tecnicos.php';
// Constante para establecer el elemento del contenido principal.
const MAIN = document.querySelector('main');
MAIN.style.paddingTop = '50px';
MAIN.style.paddingBottom = '100px';
MAIN.classList.add('container');

/* Función asíncrona para cargar el encabezado y pie del documento.
* Parámetros: ninguno.
* Retorno: ninguno.
*/
const loadTemplate = async () => {

// Se agrega el encabezado de la página web antes del contenido principal.
MAIN.insertAdjacentHTML('beforebegin', `
<header class="margen">
    <nav class="navbar bg-skyBlue-pastel-color fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler " type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-flex align-items-center">
                <img src="../../../resources/img/png/soccer.png" width="25" height="25">
                <a class="navbar-brand fw-semibold titulo ms-2" href="#" id='title'></a>
            </div>
            <!-- Imagen de perfil y nombre -->
            <div class="d-none d-sm-block">
                <div class="row align-items-center justify-content-center">
                    <div class="col-auto ">
                        <div>
                            <p class="mb-0 float-end fw-semibold" id="name"></p>
                        </div>
                        <div>
                            <p class="text-body-secondary small float-end">Técnico</p>
                        </div>
                    </div>
                    <div class="col">
                        <a class="nav-link" href="profile.html">
                        <img src="../../../resources/img/svg/avatar.svg" id="imagen" class="rounded-circle" width="55px" height="55px">
                        </a>
                    </div>
                </div>
            </div>
            <!-- Cuerpo -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <!-- Cuerpo del nav -->
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <!-- Fila que contiene el logo centrado en la parte superior del menu -->
                        <div class="row justify-content-center mb-5">
                            <div class="col-auto">
                                <img src="../../../resources/img/svg/logos/logo_blanco.svg" width="140px" alt="">
                            </div>
                        </div>
                        <div class="container ms-2">
                            <!-- Etiqueta para el dashboard -->
                            <li class="nav-item">
                                <a class="nav-link active text-light" aria-current="page" href="dashboard.html">
                                    <img src="../../../resources/img/svg/icons_menu/dashboard.svg" class="me-3"
                                        alt="">Dashboard</a>
                            </li>
                            <!-- Etiqueta para el usuario -->
                            <li class="nav-item">
                                <a class="nav-link active text-light" aria-current="page" href="players.html">
                                    <img src="../../../resources/img/svg/icons_menu/users.svg" class="me-3"
                                        alt="">Jugadores</a>
                            </li>
                            <!-- Etiqueta para el estadisticas -->
                            <li class="nav-item dropdown">
                                <a class="nav-link active text-light" href="journeys.html" aria-current="page">
                                    <img src="../../../resources/img/svg/icons_menu/estadisticas.svg" class="me-3" alt="">
                                    Jornadas
                                </a>
                            </li>
                            <!-- Etiqueta para el entrenamientos -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-light" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="../../../resources/img/svg/icons_menu/entrenamientos.svg" class="me-3"
                                        alt="">
                                    Entrenamientos
                                </a>
                                <ul class="dropdown-menu bg-transparent">
                                    <li><a class="dropdown-item text-light" href="schedules.html">Horarios</a></li>
                                    <li><a class="dropdown-item text-light" href="contents.html">Momentos de juego</a></li>
                                    <li><a class="dropdown-item text-light" href="sub_contents.html">Principios</a></li>
                                    <li><a class="dropdown-item text-light" href="tasks.html">Tareas</a></li>
                                    <li><a class="dropdown-item text-light" href="details_contents.html">Contenidos por entrenamientos</a></li>
                                    <li><a class="dropdown-item text-light" href="player_characteristics.html">Características de jugadores</a></li>
                                    <li><a class="dropdown-item text-light" href="assists.html">Asistencia</a></li>
                                </ul>
                            </li>
                            <!-- Etiqueta para el equipos -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-light" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="../../../resources/img/svg/icons_menu/equipos.svg" class="me-3" alt="">
                                    Equipos
                                </a>
                                <ul class="dropdown-menu bg-transparent">
                                    <li><a class="dropdown-item text-light" href="soccer_team.html">Equipos</a></li>
                                    <li><a class="dropdown-item text-light" href="rivals.html">Rivales</a></li>
                                    <li><a class="dropdown-item text-light" href="templates_name.html">Plantillas</a></li>
                                    <li><a class="dropdown-item text-light" href="matches_participations1.html">Participaciones</a></li>
                                    <li><a class="dropdown-item text-light" href="categories.html">Categorías</a></li>
                                    <li><a class="dropdown-item text-light" href="matches.html">Partidos</a></li>
                                </ul>
                            </li>
                            <!-- Etiqueta para el registro medico -->
                            <li class="nav-item dropdown">
                                <a class="nav-link text-light" href="medical_records.html" aria-current="page">
                                    <img src="../../../resources/img/svg/icons_menu/registro_medico.svg" class="me-3"
                                        alt="">
                                    Registro médico
                                </a>
                            </a>
                            </li>

                            <div class="contenedor p-3 rounded-3">
                                <!-- Etiqueta para contactanos -->
                                <li class="nav-item">
                                    <a class="nav-link active text-light" aria-current="page" href="contact_us.html">
                                        <img src="../../../resources/img/svg/icons_menu/contactanos.svg" class="me-3"
                                            alt="">
                                        Contáctanos</a>
                                </li>
                                <!-- Etiqueta para log out -->
                                <li class="nav-item">
                                    <a class="nav-link active text-light" aria-current="page" onclick="logOut()">
                                        <img src="../../../resources/img/svg/icons_menu/log_out.svg" class="me-3" alt="">
                                        Cerrar sesión</a>
                                </li>
                            </div>
                        </div>
                </div>
                </ul>
            </div>
        </div>
        </div>
    </nav>
</header>
`);

const DATA = await fetchData(USER_API, 'getUser');
// Se verifica si el usuario está autenticado, de lo contrario se envía a iniciar sesión.
if (DATA.session){
    if (DATA.status){
        // Obtenemos los elementos del html
        const userName = document.getElementById('name');
        const img = document.getElementById('imagen');

        //Asignamos los datos traidos de la api a nuestro elementos html.
        userName.textContent = DATA.nombre.split(' ')[0] + ' ' + DATA.apellido.split(' ')[0] // Split nos sirve para cortar un string y que solo aparezca en este caso el primer nombre y primer apellido.
        img.src = `${SERVER_URL}images/tecnicos/${DATA.foto}`;
    } else {
        await sweetAlert(3, DATA.error, false, 'index.html')
    }
}else{
    if(location.pathname.endsWith('index.html')){
        console.log('index.html');
    } else{
        location.href = 'index.html';
    }
}

}