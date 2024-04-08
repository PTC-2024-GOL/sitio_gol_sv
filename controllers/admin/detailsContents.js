let SAVE_MODAL;
let SAVE_FORM,
    ID_CATEGORIA,
    EQUIPO,
    CATEGORIA,
    HORARIO,
    SEE_MODAL;
let SEARCH_FORM;

// Constantes para completar las rutas de la API.
const DETALLE_CONTENIDO_API = '';

async function loadComponent(path) {
    const response = await fetch(path);
    const text = await response.text();
    return text;
}

// Funcion para preparar el formulario al momento de abrirlo
/*
*   Función asíncrona para preparar el formulario al momento de elegir un horario.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const seeModal =async (id) => {
    try {
        // Se define un objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idCategoria', id);
        // Petición para obtener los datos del registro solicitado.
        const DATA = await fetchData(DETALLE_CONTENIDO_API, 'readOne', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra la caja de diálogo con su título.
            SEE_MODAL.show();
            MODAL_TITLE.textContent = 'Elegir horario';
            // Se prepara el formulario.
            SAVE_FORM.reset();
            // Se inicializan los campos con los datos.
            const ROW = DATA.dataset;
            ID_CATEGORIA.value = ROW.ID;
        } else {
            sweetAlert(2, DATA.error, false);
        }
    } catch (Error) {
        console.log(Error);
        SEE_MODAL.show();
        MODAL_TITLE.textContent = 'Elegir horario';
    }
}

async function cargarTabla(form = null) {
    const lista_datos = [
        {
            equipo: 'Leones',
            categoria: 'Categoria 13-14',
            id: 1,
        },
        {
            equipo: 'MAR',
            categoria: 'Categoria 15-16',
            id: 2,
        },
        {
            equipo: 'GOL',
            categoria: 'Categoria 12-13',
            id: 3,
        },
        {
            equipo: 'Powers guys',
            categoria: 'Categoria 14',
            id: 4,
        }
    ];
    const cargarTabla = document.getElementById('tabla_detalles_contenidos');

    try {
        cargarTabla.innerHTML = '';
        // Se verifica la acción a realizar.
        (form) ? action = 'searchRows' : action = 'readAll';
        console.log(form);
        // Petición para obtener los registros disponibles.
        const DATA = await fetchData(DETALLE_CONTENIDO_API, action, form);
        console.log(DATA);

        if (DATA.status) {
            // Mostrar elementos obtenidos de la API
            DATA.dataset.forEach(row => {
                const tablaHtml = `
                <tr>
                    <td>${row.EQUIPO}</td>
                    <td>${row.CATEGORIA}</td>
                    <td>
                        <button type="button" class="btn btn-warnig" onclick="seeModal(${row.ID})">
                        <img src="../../../resources/img/svg/icons_forms/reloj.png" width="30" height="30">
                        </button>
                    </td>
                </tr>
                `;
                cargarTabla.innerHTML += tablaHtml;
            });
        } else {
            sweetAlert(4, DATA.error, true);
        }
    } catch (error) {
        console.error('Error al obtener datos de la API:', error);
        // Mostrar materiales de respaldo
        lista_datos.forEach(row => {
           const tablaHtml = `
            <tr>
                <td>${row.equipo}</td>
                <td>${row.categoria}</td>
                
                <td>
                    <button type="button" class="btn btn-warnig" onclick="seeModal(${row.id})">
                    <img src="../../../resources/img/svg/icons_forms/reloj.png" width="30" height="30">
                    </button>
                </td>
            </tr>
            `;
            cargarTabla.innerHTML += tablaHtml;
        });
    }
}

// window.onload
window.onload = async function () {
    // Obtiene el contenedor principal
    const appContainer = document.getElementById('main');
    // Carga los componentes de manera síncrona
    const adminHtml = await loadComponent('../componentes/detailsContents.html');
    // Llamada a la función para mostrar el encabezado.
    loadTemplate();
    // Agrega el HTML del encabezado
    appContainer.innerHTML = adminHtml;
    cargarTabla();
    // Constantes para establecer los elementos del componente Modal.

    SEE_MODAL = new bootstrap.Modal('#seeModal'),
        MODAL_TITLE = document.getElementById('modalTitle2')

    // Constantes para establecer los elementos del formulario de guardar.
    SAVE_FORM = document.getElementById('seeForm'),
        ID_CATEGORIA = document.getElementById('idCategoria'),
        HORARIO = document.getElementById('horario');
    // Método del evento para cuando se envía el formulario de guardar.
    SAVE_FORM.addEventListener('submit', async (event) => {
        // Se evita recargar la página web después de enviar el formulario.
        event.preventDefault();
        // Se verifica la acción a realizar.
        (ID_CATEGORIA.value) ? action = 'select' : action = 'createRow';
        // Constante tipo objeto con los datos del formulario.
        const FORM = new FormData(SAVE_FORM);
        // Petición para guardar los datos del formulario.
        const DATA = await fetchData(DETALLE_CONTENIDO_API, action, FORM);
        //Aqui debo hacer la lógica de lo que sucederá cuando se le dé click a seleccionar horario.
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se cierra la caja de diálogo.
            SAVE_MODAL.hide();
            // Se muestra un mensaje de éxito.
            sweetAlert(1, DATA.message, true);
            // Se carga nuevamente la tabla para visualizar los cambios.
            cargarTabla();
        } else {
            sweetAlert(2, DATA.error, false);
            console.error(DATA.exception);
        }
    });
    // Constante para establecer el formulario de buscar.
    SEARCH_FORM = document.getElementById('searchForm');
    // Verificar si SEARCH_FORM está seleccionado correctamente
    console.log(SEARCH_FORM)
    // Método del evento para cuando se envía el formulario de buscar.
    SEARCH_FORM.addEventListener('submit', (event) => {
        // Se evita recargar la página web después de enviar el formulario.
        event.preventDefault();
        // Constante tipo objeto con los datos del formulario.
        const FORM = new FormData(SEARCH_FORM);
        console.log(SEARCH_FORM);
        console.log(FORM);
        // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
        cargarTabla(FORM);
    });
};
