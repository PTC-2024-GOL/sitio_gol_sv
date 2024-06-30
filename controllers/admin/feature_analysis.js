

let SAVE_MODAL,
    MODAL_TITLE;
let GRAPHIC_MODAL,
    MODAL_TITLE2;
let SAVE_FORM,
    ID_ANALISIS,
    JUGADOR,
    ENTRENAMIENTO;
let SEARCH_FORM;

// Constantes para completar las rutas de la API.
const API = 'services/admin/caracteristicas_analisis.php';
const JUGADOR_API = 'services/admin/jugadores.php';
const CARACTERISTICAS_API = 'services/admin/caracteristicas.php';

// Constante tipo objeto para obtener los parámetros disponibles en la URL.
let PARAMS = new URLSearchParams(location.search);

async function loadComponent(path) {
    const response = await fetch(path);
    const text = await response.text();
    return text;
}
/*
*   Función para preparar el formulario al momento de insertar un registro.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openCreate = (id) => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Crear análisis del jugador';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    JUGADOR.value = id;
    ENTRENAMIENTO.value = PARAMS.get('id');
}

/*
*   Función para abrir la gráfica al momento.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openGraphic = () => {
    // Se muestra la caja de diálogo con su título.
    GRAPHIC_MODAL.show();
    MODAL_TITLE2.textContent = 'Gráfica de análisis del jugador';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    graficoBarrasAnalisis();
}


// Funcion para preparar el formulario al momento de abrirlo

const seeModal = async (id) => {
    try {
        // Se define un objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idAnalisis', id);
        // Petición para obtener los datos del registro solicitado.
        const DATA = await fetchData(API, 'readOne', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra la caja de diálogo con su título.
            SAVE_MODAL.show();
            MODAL_TITLE.textContent = 'Actualizar análisis del jugador';
            // Se prepara el formulario.
            SAVE_FORM.reset();
            // Se inicializan los campos con los datos.
            const ROW = DATA.dataset;
            ID_EQUIPO.value = ROW.ID;
            NOMBRE_EQUIPO.value = ROW.NOMBRE;
            TELEFONO_EQUIPO.value = ROW.TELEFONO;
            ID_CUERPO_TECNICO.value = ROW.ID_CUERPO_TECNICO;
            ID_ADMINISTRADOR.value = ROW.ID_ADMINISTRADOR;
            ID_CATEGORIA.value = ROW.ID_CATEGORIA;
            LOGO_EQUIPO.value = ROW.LOGO;
        } else {
            sweetAlert(2, DATA.error, false);
        }
    } catch (Error) {
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Análisis del jugador';
        SAVE_FORM.reset();

    }
}


/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
    try {
        // Se define un objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idAnalisis', id);
        // Petición para obtener los datos del registro solicitado.
        const DATA = await fetchData(API, 'readOne', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra la caja de diálogo con su título.
            SAVE_MODAL.show();
            MODAL_TITLE.textContent = 'Actualizar análisis del jugador';
            // Se prepara el formulario.
            SAVE_FORM.reset();
            // Se inicializan los campos con los datos.
            const ROW = DATA.dataset;
            ID_EQUIPO.value = ROW.ID;
            NOMBRE_EQUIPO.value = ROW.NOMBRE;
            TELEFONO_EQUIPO.value = ROW.TELEFONO;
            ID_CUERPO_TECNICO.value = ROW.ID_CUERPO_TECNICO;
            ID_ADMINISTRADOR.value = ROW.ID_ADMINISTRADOR;
            ID_CATEGORIA.value = ROW.ID_CATEGORIA;
            LOGO_EQUIPO.value = ROW.LOGO;
        } else {
            sweetAlert(2, DATA.error, false);
        }
    } catch (Error) {
        console.log(Error);
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar análisis del jugador';
        ID_ANALISIS.value = id;
        JUGADOR.value = PARAMS.get('id')
    }

}
/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el análisis?');
    try {
        // Se verifica la respuesta del mensaje.
        if (RESPONSE) {
            // Se define una constante tipo objeto con los datos del registro seleccionado.
            const FORM = new FormData();
            FORM.append('idAnalisis', id);
            console.log(id);
            // Petición para eliminar el registro seleccionado.
            const DATA = await fetchData(API, 'deleteRow', FORM);
            console.log(DATA.status);
            // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
            if (DATA.status) {
                // Se muestra un mensaje de éxito.
                await sweetAlert(1, DATA.message, true);
                // Se carga nuevamente la tabla para visualizar los cambios.
                cargarTabla();
            } else {
                sweetAlert(2, DATA.error, false);
            }
        }
    }
    catch (Error) {
        console.log(Error + ' Error al cargar el mensaje');
    }
}

async function cargarNav() {
    try {
        // Petición para obtener las características usando fetchData
        const data = await fetchData(CARACTERISTICAS_API, 'readAll');

        if (data.status) {
            const caracteristicas = data.dataset;

            // Agrupar las características por clasificación
            const grupos = caracteristicas.reduce((acc, caracteristica) => {
                const { CLASIFICACION } = caracteristica;
                if (!acc[CLASIFICACION]) {
                    acc[CLASIFICACION] = [];
                }
                acc[CLASIFICACION].push(caracteristica);
                return acc;
            }, {});

            // Crear la estructura de las tabs
            const navTabs = document.createElement('ul');
            navTabs.className = 'nav nav-tabs mt-4 justify-content-center';
            navTabs.id = 'classificationTabs';
            navTabs.role = 'tablist';

            const tabContent = document.createElement('div');
            tabContent.className = 'tab-content';
            tabContent.id = 'classificationTabsContent';

            let first = true;
            for (const clasificacion in grupos) {
                const tabId = `tab-${clasificacion.toLowerCase()}`;
                const tabPaneId = `pane-${clasificacion.toLowerCase()}`;

                // Crear el tab
                const li = document.createElement('li');
                li.className = 'nav-item';
                li.role = 'presentation';

                const button = document.createElement('button');
                button.className = `nav-link ${first ? 'active' : ''} text-dark`;
                button.id = `${tabId}`;
                button.setAttribute('data-bs-toggle', 'tab');
                button.setAttribute('data-bs-target', `#${tabPaneId}`);
                button.type = 'button';
                button.role = 'tab';
                button.setAttribute('aria-controls', `${tabPaneId}`);
                button.setAttribute('aria-selected', `${first}`);
                button.innerText = clasificacion;

                li.appendChild(button);
                navTabs.appendChild(li);

                // Crear el contenido del tab
                const tabPane = document.createElement('div');
                tabPane.className = `tab-pane fade ${first ? 'show active' : ''}`;
                tabPane.id = `${tabPaneId}`;
                tabPane.role = 'tabpanel';
                tabPane.setAttribute('aria-labelledby', `${tabId}`);

                const row = document.createElement('div');
                row.className = 'row';

                grupos[clasificacion].forEach(caracteristica => {
                    const col = document.createElement('div');
                    col.className = 'col-sm-12 col-md-6 mb-3 mt-2';

                    const label = document.createElement('label');
                    label.className = 'form-label';
                    label.setAttribute('for', caracteristica.NOMBRE);
                    label.innerText = caracteristica.NOMBRE;

                    const input = document.createElement('input');
                    input.id = caracteristica.NOMBRE;
                    input.type = 'number';
                    input.name = caracteristica.NOMBRE;
                    input.className = 'form-control';
                    input.min = '1';
                    input.max = '10';
                    input.placeholder = 'Ingrese la nota';
                    input.setAttribute('data-id-caracteristica-jugador', caracteristica.ID);

                    col.appendChild(label);
                    col.appendChild(input);
                    row.appendChild(col);
                });

                tabPane.appendChild(row);
                tabContent.appendChild(tabPane);

                first = false;
            }

            const navSteps = document.getElementById('navSteps');
            navSteps.innerHTML = ''; // Limpiar el contenido previo
            navSteps.appendChild(navTabs);
            navSteps.appendChild(tabContent);
        }
    } catch (error) {
        console.error('Error al cargar las características:', error);
    }
}
async function buscarAnalisis(FORM) {
    const cargarTabla = document.getElementById('tabla_analisis');

    cargarTabla.innerHTML = '';
    // Se define un objeto con los datos de la categoría seleccionada.
    FORM.append('idEntrenamiento', PARAMS.get('id'));
    console.log(FORM);
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(API, "searchRows", FORM);
    console.log(DATA);

    if (DATA.status) {
        // Mostrar elementos obtenidos de la API
        DATA.dataset.forEach(row => {
            const tablaHtml = `
            <tr>
                <td>${row.PROMEDIO}</td>
                <td>${row.JUGADOR}</td>
                <td>
                    <button type="button" class="btn transparente" onclick="openCreate(${row.IDJ})">
                    <img src="../../../resources/img/svg/icons_forms/cuerpo_tecnico.svg" width="18px" height="18px">
                    </button>
                </td>
                <td>
                    <button type="button" class="btn transparente" onclick="openGraphic(${row.IDJ})">
                    <img src="../../../resources/img/svg/icons_forms/Frame.svg" width="18" height="18">
                    </button>
                    <button type="button" class="btn transparente" onclick="openUpdate(${row.IDJ})">
                    <img src="../../../resources/img/svg/icons_forms/pen 1.svg" width="18" height="18">
                    </button>
                    <button type="button" class="btn transparente" onclick="openDelete(${row.IDJ})">
                    <img src="../../../resources/img/svg/icons_forms/trash 1.svg" width="18" height="18">
                    </button>
                </td>
            </tr>
                `;
            cargarTabla.innerHTML += tablaHtml;
        });
    } else {
        sweetAlert(4, DATA.error, true);
    }
}

async function cargarTabla() {
    const lista_datos = [
        {
            promedio: 7.45,
            jugador: 'Mateo',
            caracteristicas: 1,
            id: 1,
        },
        {
            promedio: 8.45,
            jugador: 'Sochi',
            caracteristicas: 1,
            id: 2,
        },
        {
            promedio: 9.45,
            jugador: 'Guayito',
            caracteristicas: 1,
            id: 3,
        },
        {
            promedio: 5.45,
            jugador: 'Carlos',
            caracteristicas: 1,
            id: 4,
        }
    ];
    const cargarTabla = document.getElementById('tabla_analisis');

    try {
        cargarTabla.innerHTML = '';
        // Se define un objeto con los datos de la categoría seleccionada.
        const FORM = new FormData();
        FORM.append('idEntrenamiento', PARAMS.get('id'));
        console.log(FORM);
        // Petición para obtener los registros disponibles.
        const DATA = await fetchData(API, "readAll", FORM);
        console.log(DATA);

        if (DATA.status) {
            // Mostrar elementos obtenidos de la API
            DATA.dataset.forEach(row => {
                const tablaHtml = `
            <tr>
                <td>${row.PROMEDIO}</td>
                <td>${row.JUGADOR}</td>
                <td>
                    <button type="button" class="btn transparente" onclick="openCreate(${row.IDJ})">
                    <img src="../../../resources/img/svg/icons_forms/cuerpo_tecnico.svg" width="18px" height="18px">
                    </button>
                </td>
                <td>
                    <button type="button" class="btn transparente" onclick="openGraphic(${row.IDJ})">
                    <img src="../../../resources/img/svg/icons_forms/Frame.svg" width="18" height="18">
                    </button>
                    <button type="button" class="btn transparente" onclick="openUpdate(${row.IDJ})">
                    <img src="../../../resources/img/svg/icons_forms/pen 1.svg" width="18" height="18">
                    </button>
                    <button type="button" class="btn transparente" onclick="openDelete(${row.IDJ})">
                    <img src="../../../resources/img/svg/icons_forms/trash 1.svg" width="18" height="18">
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
                <td>${row.promedio}</td>
                <td>${row.jugador}</td>
                <td>
                    <button type="button" class="btn transparente" onclick="seeModal()">
                    <img src="../../../resources/img/svg/icons_forms/cuerpo_tecnico.svg" width="18px" height="18px">
                    </button>
                </td>
                <td>
                    <button type="button" class="btn transparente" onclick="openGraphic(${row.id})">
                    <img src="../../../resources/img/svg/icons_forms/Frame.svg" width="18" height="18">
                    </button>
                    <button type="button" class="btn transparente" onclick="openUpdate(${row.id})">
                    <img src="../../../resources/img/svg/icons_forms/pen 1.svg" width="18" height="18">
                    </button>
                    <button type="button" class="btn transparente" onclick="openDelete(${row.id})">
                    <img src="../../../resources/img/svg/icons_forms/trash 1.svg" width="18" height="18">
                    </button>
                </td>
            </tr>
            `;
            cargarTabla.innerHTML += tablaHtml;
        });
    }
}

/*
*   Función asíncrona para mostrar un gráfico de barras con la cantidad de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const graficoBarrasAnalisis = async () => {
    /*
*   Lista de datos de ejemplo en caso de error al obtener los datos reales.
*/
    const datosEjemplo = [
        {
            caracteristica: 'Fuerza',
            nota: 7
        },
        {
            caracteristica: 'Resistencia',
            nota: 5
        },
        {
            caracteristica: 'Agilidad',
            nota: 7
        },
        {
            caracteristica: 'Velocidad',
            nota: 2
        }
    ];

    let caracteristicas = [];
    let notas = [];
    datosEjemplo.forEach(filter => {
        caracteristicas.push(filter.caracteristica);
        notas.push(filter.nota);
    });
    // Si ocurre un error, se utilizan los datos de ejemplo definidos arriba.
    barGraph('analisis', caracteristicas, notas, 'Análisis de características');

}

async function cargarSearch() {
    try {
        fillSelect(JUGADOR_API, 'readAll', 'search');
    } catch {
        console.log('No se pudo cargar el select de esta forma.')
    }
}

// window.onload
window.onload = async function () {
    // Obtiene el contenedor principal
    const appContainer = document.getElementById('main');
    // Carga los components de manera síncrona
    const equiposHtml = await loadComponent('../components/feaute_analysis.html');
    // Llamada a la función para mostrar el encabezado.
    loadTemplate();
    // Agrega el HTML del encabezado
    appContainer.innerHTML = equiposHtml;
    //Agrega el encabezado de la pantalla
    const titleElement = document.getElementById('title');
    titleElement.textContent = 'Análisis de las características';
    cargarTabla();
    cargarSearch();
    // Constantes para establecer los elementos del componente Modal.
    SAVE_MODAL = new bootstrap.Modal('#saveModal'),
        MODAL_TITLE = document.getElementById('modalTitle');

    GRAPHIC_MODAL = new bootstrap.Modal('#graphicModal'),
        MODAL_TITLE2 = document.getElementById('modalTitle3')

    // Constantes para establecer los elementos del formulario de guardar.
    SAVE_FORM = document.getElementById('saveForm'),
        ID_ANALISIS = document.getElementById('idAnalisis'),
        JUGADOR = document.getElementById('jugador'),
        ENTRENAMIENTO = document.getElementById('entrenamiento');
    // Método del evento para cuando se envía el formulario de guardar.
    SAVE_FORM.addEventListener('submit', async (event) => {
        // Evitar recargar la página al enviar el formulario
        event.preventDefault();
    
        // Determinar la acción a realizar (crear o actualizar)
        const action = (ID_ANALISIS.value) ? 'updateRow' : 'createRow';
    
        // Crear un objeto FormData con los datos del formulario
        const formData = new FormData(SAVE_FORM);
    
        // Crear un arreglo para las características
        const caracteristicas = [];
    
        // Recorrer los inputs del formulario y agregar las características
        SAVE_FORM.querySelectorAll('input[type="number"]').forEach(input => {
            const idCaracteristicaJugador = input.getAttribute('data-id-caracteristica-jugador');
            const notaCaracteristicaAnalisis = input.value;
    
            caracteristicas.push({
                id_caracteristica_jugador: idCaracteristicaJugador,
                nota_caracteristica_analisis: notaCaracteristicaAnalisis
            });
        });
    
        // Agregar los datos adicionales al FormData
        formData.append('caracteristicas', JSON.stringify(caracteristicas));
    
        // Enviar la solicitud para guardar los datos
        const DATA = await fetchData(API, action, formData);
    
        // Comprobar si la respuesta es satisfactoria
        if (DATA.status) {
            // Cerrar la caja de diálogo
            SAVE_MODAL.hide();
            // Mostrar un mensaje de éxito
            sweetAlert(1, DATA.message, true);
            // Recargar la tabla para visualizar los cambios
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
        buscarAnalisis(FORM);
    });
    cargarNav();
};

