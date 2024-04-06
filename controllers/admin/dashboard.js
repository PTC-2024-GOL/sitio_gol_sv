
async function loadComponent(path) {
    const response = await fetch(path);
    const text = await response.text();
    return text;
}

window.onload = async function () {
    // Obtiene el contenedor principal
    const appContainer = document.getElementById('main');
    // Carga los componentes de manera síncrona
    const lesionHtml = await loadComponent('../componentes/dashboard.html');
    // Llamada a la función para mostrar el encabezado.
    loadTemplate();
    // Agrega el HTML del encabezado
    appContainer.innerHTML = lesionHtml;
    //Agrega el encabezado de la pantalla
    const titleElement = document.getElementById('title');
    titleElement.textContent = 'Dashboard'; 

    //Agrega el nombre del admin que ha iniciado sesion
    const adminName = document.getElementById('nombreAdmin');
    adminName.textContent ='José Gonzáles';
    
    console.log(adminName.text)
}