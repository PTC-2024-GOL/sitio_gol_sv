
let SAVE_MODAL;
let SAVE_FORM,
    JUGADOR,
    SUBTEMA,
    TAREA,
    CANTIDAD_CONTENIDO,
    MINUTOS_TAREA,
    IDDETALLE_CONTENIDO,
    JUGADORES;
let SEARCH_FORM;

// Constantes para completar las rutas de la API.
const SD_CONTENTS_API = '';
const JUGADORES_API = '';

const lista_datos = [
    {
        jugadores: "Mario Alboran",
        id: 1,
    },
    {
        jugadores: 'Marco Polo',
        id: 2,
    },
    {
        jugadores: 'Susan Abigail',
        id: 3,
    },
    {
        jugadores: 'Agustin De Tarso',
        id: 4,
    }
];


//Función asíncrona para cargar un componente HTML.
async function loadComponent(path) {
    const response = await fetch(path);
    const text = await response.text();
    return text;
}
// Función para poblar un combobox (select) con opciones
const fillSelected = (data, action, selectId, selectedValue = null) => {
    const selectElement = document.getElementById(selectId);

    // Limpiar opciones previas del combobox
    selectElement.innerHTML = '';

    // Crear opción por defecto
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Selecciona a quién se asignará';
    selectElement.appendChild(defaultOption);

    // Llenar el combobox con los datos proporcionados
    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id; // Suponiendo que hay una propiedad 'id' en los datos
        option.textContent = item.jugadores; // Cambia 'jugadores' al nombre de la propiedad que deseas mostrar en el combobox
        selectElement.appendChild(option);
    });

    // Seleccionar el valor especificado si se proporciona
    if (selectedValue !== null) {
        selectElement.value = selectedValue;
    }
};
/*
*   Función para preparar el formulario al momento de insertar un registro.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/


const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Agregar detalle';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    // Llenar el combobox de jugadores
    fillSelected(lista_datos, 'readAll', 'generador');
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
        FORM.append('iddetallecontenido', id);
        // Petición para obtener los datos del registro solicitado.
        const DATA = await fetchData(SD_CONTENTS_API, 'readOne', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra la caja de diálogo con su título.
            SAVE_MODAL.show();
            MODAL_TITLE.textContent = 'Actualizar detalle';
            // Se prepara el formulario.
            SAVE_FORM.reset();
            // Se inicializan los campos con los datos.
            const ROW = DATA.dataset;
            IDDETALLE_CONTENIDO.value = ROW.ID;
            SUBCONTENIDO.value = ROW.SUBCONTENIDO;
            TAREA.value = ROW.TAREA;
            MINUTOS_TAREA.value = ROW.MINUTOS_TAREA;
            CANTIDAD_CONTENIDO.value = ROW.CANTIDAD_CONTENIDO;
            // Llenar el combobox de jugadores
            fillSelected(lista_datos, 'readAll', 'generador', ROW.JUGADORES);
        } else {
            sweetAlert(2, DATA.error, false);
        }
    } catch (Error) {
        console.error(Error);
        // En caso de error, llenar el combobox con datos simulados
        SAVE_MODAL.show();
        fillSelected(lista_datos, 'readAll', 'generador');
        MODAL_TITLE.textContent = 'Actualizar detalle';
    }
}

/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el esta asignación?');
    try {
        // Se verifica la respuesta del mensaje.
        if (RESPONSE) {
            // Se define una constante tipo objeto con los datos del registro seleccionado.
            const FORM = new FormData();
            FORM.append('iddetallecontenido', id);
            console.log(id);
            // Petición para eliminar el registro seleccionado.
            const DATA = await fetchData(SD_CONTENTS_API, 'deleteRow', FORM);
            console.log(DATA.status);
            // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
            if (DATA.status) {
                // Se muestra un mensaje de éxito.
                await sweetAlert(1, DATA.message, true);
                // Se carga nuevamente la tabla para visualizar los cambios.
                fillTable();
            } else {
                sweetAlert(2, DATA.error, false);
            }
        }
    }
    catch (Error) {
        console.log(Error + ' Error al cargar el mensaje');
    }

}

/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (formulario de búsqueda).
*   Retorno: ninguno.
*/
async function fillTable(form = null) {
    const lista_datos = [
        {
            subcontenido: "Juegos lúdicos",
            tarea: 'Coordinación',
            jugador: 'Paolo',
            id: 1,
        },
        {
            tarea: "Trabajo preventivo",
            subcontenido: 'Agilidad',
            jugador: 'Marco',
            id: 2,
        },
        {
            tarea: "Circuitos/ Fisicos sin balón",
            subcontenido: 'Toma de decisiciones',
            jugador: 'Susan',
            id: 3,
        },
        {
            tarea: "Circuitos/ Fisicos con balón",
            subcontenido: 'Toma de decisiciones',
            jugador: 'Agustin',
            id: 4,
        }
    ];
    const cargarTabla = document.getElementById('tabla_especificos_detalles_contenidos');

    try {
        cargarTabla.innerHTML = '';
        // Se verifica la acción a realizar.
        (form) ? action = 'searchRows' : action = 'readAll';
        console.log(form);
        // Petición para obtener los registros disponibles.
        const DATA = await fetchData(SD_CONTENTS_API, action, form);
        console.log(DATA);

        if (DATA.status) {
            // Mostrar elementos obtenidos de la API
            DATA.dataset.forEach(row => {
                const tablaHtml = `
                <tr>
                    <td>${row.JUGADOR}</td>
                    <td>${row.SUBCONTENIDO}</td>
                    <td>${row.TAREA}<td>
                    <td>
                        <button type="button" class="btn btn-outline-success" onclick="openUpdate(${row.ID})">
                        <img src="../../recursos/img/svg/icons_forms/pen 1.svg" width="30" height="30">
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="openDelete(${row.ID})">
                            <i class="bi bi-trash-fill"></i>
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
                    <td>${row.jugador}</td>
                    <td>${row.subcontenido}</td>
                    <td>${row.tarea}<td>
                    <td>
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

// window.onload
window.onload = async function () {
    // Obtiene el contenedor principal
    const appContainer = document.getElementById('main');
    // Carga los components de manera síncrona
    const subcontenidosHtml = await loadComponent('../components/specific_details_contents.html');
    // Llamada a la función para mostrar el encabezado.
    loadTemplate();
    // Agrega el HTML del encabezado
    appContainer.innerHTML = subcontenidosHtml;
    //Agrega el encabezado de la pantalla
    const titleElement = document.getElementById('title');
    titleElement.textContent = 'Detalles contenido específico';
    fillSelected(lista_datos, 'readAll', 'generador');
    fillTable();
    // Constantes para establecer los elementos del componente Modal.
    SAVE_MODAL = new bootstrap.Modal('#saveModal'),
        MODAL_TITLE = document.getElementById('modalTitle');

    // Constantes para establecer los elementos del formulario de guardar.
    SAVE_FORM = document.getElementById('saveForm'),
        ID_SUBCONTENIDO = document.getElementById('iddetallecontenido'),
        SUBCONTENIDO = document.getElementById('subcontenido'),
        TAREA = document.getElementById('tarea');
    CANTIDAD_CONTENIDO = document.getElementById('cantidadEquipo');
    MINUTOS_TAREA = document.getElementById('minutostarea');
    // Método del evento para cuando se envía el formulario de guardar.
    SAVE_FORM.addEventListener('submit', async (event) => {
        // Se evita recargar la página web después de enviar el formulario.
        event.preventDefault();
        // Se verifica la acción a realizar.
        (ID_SUBCONTENIDO.value) ? action = 'updateRow' : action = 'createRow';
        // Constante tipo objeto con los datos del formulario.
        const FORM = new FormData(SAVE_FORM);
        // Petición para guardar los datos del formulario.
        const DATA = await fetchData(SD_CONTENTS_API, action, FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se cierra la caja de diálogo.
            SAVE_MODAL.hide();
            // Se muestra un mensaje de éxito.
            sweetAlert(1, DATA.message, true);
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTable();
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
        fillTable(FORM);
    });

    // Listener para el cambio en el select de jugadores
    const selectJugador = document.getElementById('generador');
    selectJugador.addEventListener('change', (event) => {
        const selectedJugadorId = event.target.value;
        const jugadorSeleccionado = lista_datos.find(jugador => jugador.id === parseInt(selectedJugadorId));
        if (jugadorSeleccionado) {
            // Crear un nuevo contenedor de fila para el input y el botón
            const rowContainer = document.createElement('div');
            rowContainer.classList.add('row', 'py-1');

            // Crear un nuevo input para el nombre del jugador
            const nombreJugadorInput = document.createElement('input');
            nombreJugadorInput.id = 'nombreAdministrador_' + jugadorSeleccionado.id;
            nombreJugadorInput.type = 'text';
            nombreJugadorInput.name = 'nombreAdministrador_' + jugadorSeleccionado.id;
            nombreJugadorInput.classList.add( 'col-9', 'rounded-3', 'me-2');
            nombreJugadorInput.disabled = true;
            nombreJugadorInput.value = jugadorSeleccionado.jugadores;

            // Crear un nuevo botón de eliminar
            const botonEliminar = document.createElement('button');
            botonEliminar.id = 'btnEliminar_' + jugadorSeleccionado.id;
            botonEliminar.type = 'button';
            botonEliminar.classList.add('btn', 'transparente', 'col-1');
            botonEliminar.onclick = function () {
                eliminarInput(jugadorSeleccionado.id);
            };

            // Crear la imagen para el botón de eliminar
            const imagenEliminar = document.createElement('img');
            imagenEliminar.src = '../../../resources/img/svg/icons_forms/trash 1.svg';
            imagenEliminar.width = 10;
            imagenEliminar.height = 10;

            // Agregar la imagen al botón de eliminar
            botonEliminar.appendChild(imagenEliminar);

            // Agregar el input y el botón al contenedor de fila
            rowContainer.appendChild(nombreJugadorInput);
            rowContainer.appendChild(botonEliminar);

            // Obtener el contenedor de los nombres de los jugadores
            const nombresDeLosJugadoresDiv = document.getElementById('nombresDeLosJugadores');

            // Agregar el contenedor de fila al contenedor de nombres de los jugadores
            nombresDeLosJugadoresDiv.appendChild(rowContainer);
        }
    });

    // Función para eliminar el input del nombre seleccionado
    function eliminarInput(idJugador) {
        const inputEliminar = document.getElementById('nombreAdministrador_' + idJugador);
        const btnEliminar = document.getElementById('btnEliminar_' + idJugador);
        if (inputEliminar && btnEliminar) {
            inputEliminar.remove();
            btnEliminar.remove();
        }
    }

};