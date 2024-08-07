let SEE_MODAL,
    MODAL_TITLE1;

let SEARCH_FORM;
let CUERPO_TECNICO;
let SELECT;

// Constantes para completar las rutas de la API.
const EQUIPO_API = 'services/technics/equipos.php';

async function loadComponent(path) {
    const response = await fetch(path);
    const text = await response.text();
    return text;
}

// Funcion para preparar el formulario al momento de abrirlo

const seeModal = async (id)  => {
    CUERPO_TECNICO.innerHTML = '';
    SEE_MODAL.show();
    MODAL_TITLE1.textContent = 'Cuerpo Técnico del equipo';
    const FORM = new FormData();
    FORM.append('idEquipo', id);
    const DATA = await fetchData(EQUIPO_API, 'readAllStaff', FORM);
    if (DATA.status) {
        DATA.dataset.forEach(ROW => {
            const completeName = ROW.nombre_tecnico + ' ' + ROW.apellido_tecnico;
            const tablaHtml = `
                <div class="col-sm-12 col-md-6 mb-3 text-center">
                    <div class="shadow rounded-3 p-3">
                        <img src="${SERVER_URL}images/tecnicos/${ROW.foto_tecnico}" height="60" width="65" class="circulo">
                        <p class="fw-semibold mb-2 mt-2">${ROW.nombre_rol_tecnico}</p>
                        <hr/>
                        <p class="mb-0">${completeName}</p>
                        <small class="fw-light">${ROW.correo_tecnico}</small>
                    </div>
                <div/>
                `;
            CUERPO_TECNICO.innerHTML += tablaHtml;
        })
    } else {
        await sweetAlert(3, DATA.error, true);
    }
}


// Manejo para la paginacion
const soccerTeamByPage = 10;
let currentPage = 1;
let soccerTeam = [];

function showSoccerTeam(page) {
    const start = (page - 1) * soccerTeamByPage;
    const end = start + soccerTeamByPage;
    const soccerTeamsPage = soccerTeam.slice(start, end);

    const fillTable = document.getElementById('tabla_equipos');
    fillTable.innerHTML = '';
    soccerTeamsPage.forEach(row => {
        const tablaHtml = `
                <tr>
                    <td><img src="${SERVER_URL}images/equipos/${row.logo_equipo}" height="50" width="50" class="circulo"></td>
                    <td>${row.nombre_equipo}</td>
                    <td>${row.telefono_contacto}</td>
                    <td>${row.nombre_categoria}</td>
                    <td>${row.genero_equipo}</td>
                    <td>
                        <button type="button" class="btn btn-warnig" onclick="seeModal(${row.id_equipo})">
                        <img src="../../../resources/img/svg/icons_forms/cuerpo_tecnico.svg" width="30" height="30">
                        </button>
                    </td>
                </tr>
                `;
        fillTable.innerHTML += tablaHtml;
    });

    updatePaginate();
}

async function cargarTabla(form = null) {
    const cargarTabla = document.getElementById('tabla_equipos');

    try {
        cargarTabla.innerHTML = '';
        // Se verifica la acción a realizar.
        (form) ? action = 'searchRows' : action = 'readAll';
        // Petición para obtener los registros disponibles.
        const DATA = await fetchData(EQUIPO_API, action, form);

        if (DATA.status) {
            SELECT.value = 'Filtrar por género';
            soccerTeam = DATA.dataset;
            showSoccerTeam(currentPage);
        } else {
            await sweetAlert(3, DATA.error, true);
        }
    } catch (error) {
        console.error('Error al obtener datos de la API:', error);
    }
}

// Función para actualizar los contlesiones de paginación
function updatePaginate() {
    const paginacion = document.querySelector('.pagination');
    paginacion.innerHTML = '';

    const totalPaginas = Math.ceil(soccerTeam.length / soccerTeamByPage);

    if (currentPage > 1) {
        paginacion.innerHTML += `<li class="page-item"><a class="page-link text-bs-dark" href="#" onclick="nextPage(${currentPage - 1})">Anterior</a></li>`;
    }

    for (let i = 1; i <= totalPaginas; i++) {
        paginacion.innerHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link text-bs-dark" href="#" onclick="nextPage(${i})">${i}</a></li>`;
    }

    if (currentPage < totalPaginas) {
        paginacion.innerHTML += `<li class="page-item"><a class="page-link text-bs-dark" href="#" onclick="nextPage(${currentPage + 1})">Siguiente</a></li>`;
    }
}

// Función para cambiar de página
function nextPage(newPage) {
    currentPage = newPage;
    showSoccerTeam(currentPage);
}

//Funcion que permite filtrar a los jugadores por su genero.
const FilterByGender = async () => {

    const FORM = new FormData();
    FORM.append('generoEquipo', SELECT.value);

    const DATA = await fetchData(EQUIPO_API, 'readAllByGender', FORM);

    if (DATA.status) {
        soccerTeam = DATA.dataset;
        showSoccerTeam(currentPage);
    } else {
        console.log('Elige otra opción de filtrado')
    }
}

// window.onload
window.onload = async function () {
    // Obtiene el contenedor principal
    const appContainer = document.getElementById('main');
    // Carga los components de manera síncrona
    const equiposHtml = await loadComponent('../components/soccer_team.html');
    // Llamada a la función para mostrar el encabezado.
    loadTemplate();

    // Agrega el HTML del encabezado
    appContainer.innerHTML = equiposHtml;
    //Agrega el encabezado de la pantalla
    const titleElement = document.getElementById('title');
    titleElement.textContent = 'Equipos';

    //Select para el filtrado por generos
    SELECT = document.getElementById('select');

    await cargarTabla();
    SEE_MODAL = new bootstrap.Modal('#seeModal'),
        MODAL_TITLE1 = document.getElementById('modalTitle1')

    // Constantes para ver los miembros del cuerpo tecnico
    CUERPO_TECNICO = document.getElementById('cuerpoTecnicoEquipo');


    // Constante para establecer el formulario de buscar.
    SEARCH_FORM = document.getElementById('searchForm');

    // Método del evento para cuando se envía el formulario de buscar.
    SEARCH_FORM.addEventListener('submit', (event) => {
        // Se evita recargar la página web después de enviar el formulario.
        event.preventDefault();
        // Constante tipo objeto con los datos del formulario.
        const FORM = new FormData(SEARCH_FORM);

        // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
        cargarTabla(FORM);
    });
};
