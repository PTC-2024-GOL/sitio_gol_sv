/*
*   CONTROLADOR DE USO GENERAL EN TODAS LAS PÁGINAS WEB.
*/
// Constante para establecer la ruta base del servidor.
const SERVER_URL = 'http://localhost/sitio_gol_sv/api/';

/*
*   Función para mostrar un mensaje de confirmación. Requiere la librería sweetalert para funcionar.
*   Parámetros: message (mensaje de confirmación).
*   Retorno: resultado de la promesa.
*/
const confirmAction = (message) => {
    return swal({
        title: 'Advertencia',
        text: message,
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            cancel: {
                text: 'No',
                value: false,
                visible: true
            },
            confirm: {
                text: 'Sí',
                value: true,
                visible: true
            }
        }
    });
}

const confirmUpdateAction = (message) => {
    return swal({
        title: 'Aviso',
        text: message,
        icon: 'info',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            cancel: {
                text: 'No',
                value: false,
                visible: true
            },
            confirm: {
                text: 'Sí',
                value: true,
                visible: true
            }
        }
    });
}

/*
*   Función asíncrona para manejar los mensajes de notificación al usuario. Requiere la librería sweetalert para funcionar.
*   Parámetros: type (tipo de mensaje), text (texto a mostrar), timer (uso de temporizador) y url (valor opcional con la ubicación de destino).
*   Retorno: ninguno.
*/
const sweetAlert = async (type, text, timer, url = null) => {
    // Se compara el tipo de mensaje a mostrar.
    switch (type) {
        case 1:
            title = 'Éxito';
            icon = 'success';
            break;
        case 2:
            title = 'Error';
            icon = 'error';
            break;
        case 3:
            title = 'Advertencia';
            icon = 'warning';
            break;
        case 4:
            title = 'Aviso';
            icon = 'info';
    }
    // Se define un objeto con las opciones principales para el mensaje.
    let options = {
        title: title,
        text: text,
        icon: icon,
        closeOnClickOutside: false,
        closeOnEsc: false,
        button: {
            text: 'Aceptar'
        }
    };
    // Se verifica el uso del temporizador.
    (timer) ? options.timer = 3000 : options.timer = null;
    // Se muestra el mensaje.
    await swal(options);
    // Se direcciona a una página web si se indica.
    (url) ? location.href = url : undefined;
}

/*
*   Función asíncrona para cargar las opciones en un select de formulario.
*   Parámetros: filename (nombre del archivo), action (acción a realizar), select (identificador del select en el formulario) y selected (dato opcional con el valor seleccionado).
*   Retorno: ninguno.
*/
const fillSelect = async (filename, action, select, selected = null) => {
    // Petición para obtener los datos.
    const DATA = await fetchData(filename, action);
    let content = '';
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje.
    if (DATA.status) {
        content += '<option value="" selected>Seleccione una opción</option>';
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se obtiene el dato del primer campo.
            value = Object.values(row)[0];
            // Se obtiene el dato del segundo campo.
            text = Object.values(row)[1];
            // Se verifica cada valor para enlistar las opciones.
            if (value != selected) {
                content += `<option value="${value}">${text}</option>`;
            } else {
                content += `<option value="${value}" selected>${text}</option>`;
            }
        });
    } else {
        content += '<option>No hay opciones disponibles</option>';
    }
    // Se agregan las opciones a la etiqueta select mediante el id.
    document.getElementById(select).innerHTML = content;
}

/*
*   Función asíncrona para cargar las opciones en un select de formulario con un arreglo de imagenes.
*   Parámetros: filename (nombre del archivo), action (acción a realizar), select (identificador del select en el formulario) y selected (dato opcional con el valor seleccionado).
*   Retorno: un arreglo de objetos compuesto de la siguinte forma [{id: 1, imagen: 'dfs.png'},{id: 1, imagen: 'dfs.png'}].
*/
const fillSelectImage = async (filename, action, select, selected = null) => {
     // Petición para obtener los datos.
     const DATA = await fetchData(filename, action, null);
     let content = '';
     let imagenes = [];
     // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje.
     if (DATA.status) {
         content += '<option value="" selected>Seleccione una opción</option>';
         // Se recorre el conjunto de registros fila por fila a través del objeto row.
         DATA.dataset.forEach(row => {
             // Se obtiene el dato del primer campo.
             let value = Object.values(row)[0];
             // Se obtiene el dato del segundo campo.
             let text = Object.values(row)[1];
             // Se obtiene el dato del tercer campo.
             let imagen = Object.values(row)[2];
 
             imagenes.push({id: value, imagen: imagen});
             // Se verifica cada valor para enlistar las opciones.
             if (value != selected) {
                 content += `<option value="${value}">${text}</option>`;
             } else {
                 content += `<option value="${value}" selected>${text}</option>`;
             }
         });
     } else {
         content += '<option value="0">No hay opciones disponibles</option>';
     }
     // Se agregan las opciones a la etiqueta select mediante el id.
     document.getElementById(select).innerHTML = content;
 
     // Retornar el arreglo de objetos con las imágenes.
     return imagenes;
}

/*
*   Función asíncrona para cargar las opciones en un select de formulario con post, en su mayroía
*   se usa para funciones en el que el select depende de algun id, es decir, selec especificos.
*   Parámetros: filename (nombre del archivo), action (acción a realizar), select (identificador del select en el formulario) y selected (dato opcional con el valor seleccionado).
*   Retorno: ninguno.
*/
const fillSelectPost = async (filename, action, select, form, selected = null) => {
    // Petición para obtener los datos.
    const DATA = await fetchData(filename, action, form);
    let content = '';
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje.
    if (DATA.status) {
        content += '<option value="" selected>Seleccione una opción</option>';
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se obtiene el dato del primer campo.
            value = Object.values(row)[0];
            // Se obtiene el dato del segundo campo.
            text = Object.values(row)[1];
            // Se verifica cada valor para enlistar las opciones.
            if (value != selected) {
                content += `<option value="${value}">${text}</option>`;
            } else {
                content += `<option value="${value}" selected>${text}</option>`;
            }
        });
    } else {
        content += '<option value="0">No hay opciones disponibles</option>';
    }
    // Se agregan las opciones a la etiqueta select mediante el id.
    document.getElementById(select).innerHTML = content;
}

// /*
// *   Función para mostrar la opcion seleccionada de un formulario en base a opciones ya predefinidas.
// *   Parámetros: selectedElement (identificar el id del select en el forms), valueToSelect (Opcion que queremos mostrar en el select, comparara si esta opcion se encuentra en el select).
// *   Retorno: ninguno.
// */
function preselectOption(selectElement, valueToSelect) {
    // Seleccionar el elemento select
    const select = document.getElementById(selectElement);

    // Recorrer las opciones del select
    for (const option of select.options) {
        if (option.value === valueToSelect) {
            // Seleccionar la opción coincidente
            option.selected = true;

            // Si hay un input asociado, asignar el valor seleccionado
            const associatedInput = document.getElementById(`${selectElement}-input`);
            if (associatedInput) {
                associatedInput.value = valueToSelect;
            }
            break; // Detener el bucle una vez encontrada la opción
        }
    }
}


/*
*   Función para generar un gráfico de barras verticales. Requiere la librería chart.js para funcionar.
*   Parámetros: canvas (identificador de la etiqueta canvas), xAxis (datos para el eje X), yAxis (datos para el eje Y), legend (etiqueta para los datos) y title (título del gráfico).
*   Retorno: ninguno.
*/
const barGraph = (canvas, xAxis, yAxis, legend, title) => {
    // Se declara un arreglo para guardar códigos de colores en formato hexadecimal.
    let colors = [];
    // Se generan códigos hexadecimales de 6 cifras de acuerdo con el número de datos a mostrar y se agregan al arreglo.
    xAxis.forEach(() => {
        colors.push('#' + (Math.random().toString(16)).substring(2, 8));
    });
    // Se crea una instancia para generar el gráfico con los datos recibidos.
    new Chart(document.getElementById(canvas), {
        type: 'bar',
        data: {
            labels: xAxis,
            datasets: [{
                label: legend,
                data: yAxis,
                backgroundColor: colors
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: title
                },
                legend: {
                    display: false
                }
            }
        }
    });
}

const lineGraph = (canvas, xAxis, yAxis, legend, title) => {
    // Se declara un arreglo para guardar códigos de colores en formato hexadecimal.
    let colors = [];
    // Se generan códigos hexadecimales de 6 cifras de acuerdo con el número de datos a mostrar y se agregan al arreglo.
    xAxis.forEach(() => {
        colors.push('#' + (Math.random().toString(16)).substring(2, 8));
    });
    // Se crea una instancia para generar el gráfico con los datos recibidos.
    new Chart(document.getElementById(canvas), {
        type: 'line',
        data: {
            labels: xAxis,
            datasets: [{
                label: legend,
                data: yAxis,
                backgroundColor: colors
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: title
                },
                legend: {
                    display: false
                }
            }
        }
    });
}

/*
*   Función para generar un gráfico de pastel. Requiere la librería chart.js para funcionar.
*   Parámetros: canvas (identificador de la etiqueta canvas), legends (valores para las etiquetas), values (valores de los datos) y title (título del gráfico).
*   Retorno: ninguno.
*/
const DoughnutGraph = (canvas, legends, values, title) => {
    // Se declara un arreglo para guardar códigos de colores en formato hexadecimal.
    let colors = [];
    // Se generan códigos hexadecimales de 6 cifras de acuerdo con el número de datos a mostrar y se agregan al arreglo.
    values.forEach(() => {
        colors.push('#' + (Math.random().toString(16)).substring(2, 8));
    });
    // Se crea una instancia para generar el gráfico con los datos recibidos.
    new Chart(document.getElementById(canvas), {
        type: 'doughnut',
        data: {
            labels: legends,
            datasets: [{
                data: values,
                backgroundColor: colors
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: title
                }
            }
        }
    });
}

/*
*   Función asíncrona para cerrar la sesión del usuario.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const logOut = async () => {
    // Se muestra un mensaje de confirmación y se captura la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Está seguro de cerrar la sesión?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Petición para eliminar la sesión.
        const DATA = await fetchData(USER_API, 'logOut');
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            sweetAlert(1, DATA.message, true, 'index.html');
        } else {
            sweetAlert(2, DATA.exception, false);
        }
    }
}

/*
*   Función asíncrona para intercambiar datos con el servidor.
*   Parámetros: filename (nombre del archivo), action (accion a realizar) y form (objeto opcional con los datos que serán enviados al servidor).
*   Retorno: constante tipo objeto con los datos en formato JSON.
*/
const fetchData = async (filename, action, form = null) => {
    // Se define una constante tipo objeto para establecer las opciones de la petición.
    const OPTIONS = {};
    // Se determina el tipo de petición a realizar.
    if (form) {
        OPTIONS.method = 'post';
        OPTIONS.body = form;
    } else {
        OPTIONS.method = 'get';
    }
    
    try {
        // Se declara una constante tipo objeto con la ruta específica del servidor.
        const PATH = new URL(SERVER_URL + filename);
        // Se agrega un parámetro a la ruta con el valor de la acción solicitada.
        PATH.searchParams.append('action', action);
        // Se define una constante tipo objeto con la respuesta de la petición.
        const RESPONSE = await fetch(PATH.href, OPTIONS);
        // Se retorna el resultado en formato JSON.
        return await RESPONSE.json();
    } catch (error) {
        // Se muestra un mensaje en la consola del navegador web cuando ocurre un problema.
        console.log(error);
    }
}
