// Espera a que el contenido del DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
    iniciarApp(); // Inicia la aplicación
});

// Función para inicializar funciones
function iniciarApp() {
    tabs(); // Inicializa las pestañas
    mostrarSeccion(); // Muestra la sección correspondiente
    botonesPaginacion(); // Configura los botones de paginación
    paginaAnterior(); // Configura la navegación a la página anterior
    paginaSiguiente(); // Configura la navegación a la siguiente página
    consultarAPI(); // Realiza la consulta a la API en el backend
    nombreCliente(); // Obtiene el nombre del cliente
    seleccionarFecha(); // Permite seleccionar la fecha
    seleccionarHora(); // Permite seleccionar la hora
    mostrarResumen(); // Muestra un resumen de la cita
    clienteId()
    console.log(cita)
}

// Objeto con los datos de la cita
const cita = {
    id: '', //id del cliente
    nombre: '', // Nombre del cliente
    fecha: '', // Fecha de la cita
    hora: '', // Hora de la cita
    servicios: [] // Servicios seleccionados para la cita
}

// Declarando variable global para saber en qué paso estoy
let paso = 1; // Paso actual

// Número de secciones mínimo y máximo
const pasoInicial = 1; // Paso inicial
const pasoFinal = 3; // Paso final

// Función para saber en qué tab le doy clic
function tabs() {
    let botones = document.querySelectorAll(".tabs button"); // Selecciona todos los botones de las pestañas
    botones.forEach(boton => {
        boton.addEventListener('click', (e) => {
            paso = parseInt(e.target.dataset.paso); // Obtiene el paso asociado al botón
            mostrarSeccion(); // Cambia las secciones con los tabs
            botonesPaginacion(); // Actualiza los botones de paginación
        });
    });
}

// Función para mostrar cada sección, evalúa el paso actual
function mostrarSeccion() {
    let seccionConMostrar = document.querySelector(".mostrar"); // Selecciona la sección actualmente visible
    if (seccionConMostrar) {
        seccionConMostrar.classList.remove("mostrar"); // Oculta la sección visible
    }

    let quitarResaltado = document.querySelector(".actual"); // Selecciona la pestaña actualmente resaltada
    if (quitarResaltado) {
        quitarResaltado.classList.remove("actual"); // Quita el resaltado de la pestaña
    }

    let SA = `#paso-${paso}`; // Selecciona la nueva sección a mostrar
    let seccionActual = document.querySelector(SA);
    seccionActual.classList.add("mostrar"); // Muestra la nueva sección

    let resaltarSeccion = document.querySelector(`[data-paso="${paso}"]`); // Selecciona la pestaña correspondiente al paso
    resaltarSeccion.classList.add("actual"); // Resalta la pestaña
}

// Cambiar comportamiento de los botones de paginación
function botonesPaginacion() {
    let botonAnterior = document.querySelector("#anterior"); // Selecciona el botón de anterior
    let botonSiguiente = document.querySelector("#siguiente"); // Selecciona el botón de siguiente
    // Hacer que los botones aparezcan según cada sección
    if (paso === 1) {
        botonAnterior.classList.add("ocultarNA"); // Oculta el botón anterior en el primer paso
        botonSiguiente.classList.remove("ocultarNA"); // Muestra el botón siguiente
    } else if (paso === 2) {
        botonAnterior.classList.remove("ocultarNA"); // Muestra el botón anterior
        botonSiguiente.classList.remove("ocultarNA"); // Muestra el botón siguiente
    } else if (paso === 3) {
        botonAnterior.classList.remove("ocultarNA"); // Muestra el botón anterior
        botonSiguiente.classList.add("ocultarNA"); // Oculta el botón siguiente
        mostrarResumen(); // Muestra el resumen en el último paso
    }
}

// Cambiar con los botones las páginas
function paginaAnterior() {
    let botonAnterior = document.querySelector("#anterior"); // Selecciona el botón de anterior
    botonAnterior.addEventListener('click', () => {
        paso--; // Disminuye el paso
        if (paso < pasoInicial) return; // Evita que el paso sea menor que el inicial
        mostrarSeccion(); // Vuelve a asignar el paso
        botonesPaginacion(); // Actualiza los botones de paginación
    });
}

// Cambiar con los botones las páginas
function paginaSiguiente() {
    let botonSiguiente = document.querySelector("#siguiente"); // Selecciona el botón de siguiente
    botonSiguiente.addEventListener('click', () => {
        paso++; // Aumenta el paso
        if (paso > pasoFinal) return; // Evita que el paso sea mayor que el final
        mostrarSeccion(); // Vuelve a asignar el paso
        botonesPaginacion(); // Actualiza los botones de paginación
    });
}

// Consultando API
async function consultarAPI() {
    try {
        const url = `/api/servicios`; // URL de la API
        const resultado = await fetch(url); // Realiza la petición a la API
        const servicios = await resultado.json(); // Convierte la respuesta a JSON
        mostrarServicios(servicios); // Muestra los servicios en la interfaz
    } catch (error) {
        console.log(error); // Manejo de errores
    }
}

// Función para mostrar los servicios
function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        // servicio es la iteración de un solo elemento
        const { id, nombre, precio } = servicio; // Desestructuración del servicio

        // Creando elementos nombreServicio
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add("nombre-servicio"); // Añade clase al párrafo
        nombreServicio.textContent = `${nombre}`; // Establece el texto del párrafo

        // Creando elementos precioServicio
        const precioServicio = document.createElement('P');
        precioServicio.classList.add("precio-servicio"); // Añade clase al párrafo
        precioServicio.textContent = `$/. ${precio}`; // Establece el texto del párrafo

        // Creando elementos servicioDiv
        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add("servicio"); // Añade clase al div

        // Asignando atributo personalizado al servicioDiv
        servicioDiv.dataset.idServicio = id;
        servicioDiv.appendChild(nombreServicio); // Añade el nombre del servicio al div
        servicioDiv.appendChild(precioServicio); // Añade el precio del servicio al div
        servicioDiv.onclick = () => {
            seleccionarServicio(servicio); // Selecciona el servicio al hacer clic
        };
        const serviciosB = document.querySelector("#servicios"); // Selecciona el contenedor de servicios
        serviciosB.appendChild(servicioDiv); // Añade el div del servicio al contenedor
    });
}

// Creando función para agregar elementos al objeto
function seleccionarServicio(servicio) {
    const { servicios } = cita; // Obtiene el array de servicios de la cita
    const { id } = servicio; // Obtiene el id del servicio seleccionado

    let servicioSelect = document.querySelector(`[data-id-servicio="${id}"]`); // Selecciona el servicio en el DOM

    // Comprobar si existe el servicio seleccionado
    let existe = servicios.some(verificar => verificar.id === id); // Verifica si el servicio ya existe

    if (existe) {
        // Si el servicio ya existe, lo elimina del array
        cita.servicios = servicios.filter(eliminiar => eliminiar.id !== id);
    } else {
        // Si el servicio no existe, lo añade al array
        cita.servicios = [...servicios, servicio];
    }
    servicioSelect.classList.toggle("seleccionado"); // Cambia la clase del servicio seleccionado
}

// Función para obtener el nombre del cliente
function nombreCliente() {
    cita.nombre = document.querySelector("#nombre").value; // Almacena el nombre del cliente
}

// Función para obtener el nombre del cliente
function clienteId() {
    cita.id = document.querySelector("#id").value; // Almacena el nombre del cliente
}

// Función para crear una alerta
function crearAlerta(mensaje, tipo, elemento, desaparece = true) {
    const alerta = document.createElement("P"); // Crea un nuevo párrafo para la alerta
    const imprimir = document.querySelector(elemento); // Selecciona el elemento donde se mostrará la alerta
    alerta.textContent = mensaje; // Establece el mensaje de la alerta
    alerta.classList.add("alerta"); // Añade clase para el estilo
    alerta.classList.add(tipo); // Añade clase según el tipo de alerta (error, éxito, etc.)

    borrarAlertaPrevia(); // Borra alertas anteriores
    imprimir.appendChild(alerta); // Añade la nueva alerta al DOM
    if (desaparece) {
        borrarAlertaActual(alerta); // Borra la alerta después de un tiempo
    }
}

// Función para seleccionar la fecha
function seleccionarFecha() {
    let fecha = document.querySelector("#fecha"); // Selecciona el campo de fecha
    fecha.addEventListener('input', (e) => {
        let fechaSeleccionada = new Date(e.target.value); // Crea un objeto Date con la fecha seleccionada
        let dia = fechaSeleccionada.getUTCDay(); // Obtiene el día de la semana
        if (dia === 6 || dia === 0) {
            crearAlerta("No se puede seleccionar sábados ni domingos", "error", ".formulario"); // Crea alerta si se selecciona fin de semana
            fecha.value = ""; // Limpia el campo de fecha
        } else {
            cita.fecha = e.target.value; // Almacena la fecha en el objeto cita
        }
    });
}

// Función para seleccionar la hora
function seleccionarHora() {
    const hora = document.querySelector("#hora"); // Selecciona el campo de hora
    hora.addEventListener('input', (e) => {
        const horaCita = e.target.value; // Extrae la hora
        const hora = horaCita.split(":")[0]; // Divide la hora en horas y minutos
        if (hora <= 10 || hora >= 20) {
            e.target.value = ""; // Limpia el campo si la hora no es válida
            crearAlerta("El horario seleccionado no es válido", "error", ".formulario"); // Crea alerta de hora inválida
            cita.hora = ""; // Limpia el campo de hora en el objeto cita
        } else {
            cita.hora = horaCita; // Almacena la hora en el objeto cita
        }
    });
}

// Función para borrar la alerta previa
function borrarAlertaPrevia() {
    const alertaPrevia = document.querySelector(".alerta"); // Selecciona la alerta previa
    if (alertaPrevia) {
        alertaPrevia.remove(); // Elimina la alerta previa
    }
}

// Función para borrar la alerta actual después de un tiempo
function borrarAlertaActual(alerta) {
    setTimeout(() => {
        alerta.remove(); // Elimina la alerta después de 3 segundos
    }, 3000);
}

// Función para mostrar un resumen de la cita
function mostrarResumen() {
    let resumen = document.querySelector(".contenido-resumen")
    let citaCliente = document.querySelector(".cliente-cita")
    resumen.innerHTML = ""
    citaCliente.innerHTML = ""

    if (Object.values(cita).includes("") || cita.servicios.length === 0) {
        crearAlerta("Faltan datos para poder procesar su cita", "error", ".contenido-resumen", false);
        return;
    }

    const headingServicios = document.createElement("H3")
    headingServicios.textContent = "Resumen de servicios"
    resumen.appendChild(headingServicios)

    const { nombre, fecha, hora, servicios } = cita

    servicios.forEach(servicio => {
        const { nombre, precio } = servicio

        const contenedorServicio = document.createElement("DIV")
        contenedorServicio.classList.add("contenedor-servicio")

        const textoServicio = document.createElement('P')
        textoServicio.textContent = nombre

        const precioServicio = document.createElement('P')
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`

        contenedorServicio.appendChild(textoServicio)
        contenedorServicio.appendChild(precioServicio)

        resumen.appendChild(contenedorServicio)
    });

    const clienteDatos = document.querySelector(".cliente-cita")

    const headinCliente = document.createElement("H3")
    headinCliente.textContent = "Resumen de cita"
    clienteDatos.appendChild(headinCliente)

    const nomCliente = document.createElement('P')
    nomCliente.innerHTML = `<span>Nombre:</span> ${nombre}`

    // Formatear la fecha
    const fechaFormateada = formatearFecha(fecha)

    const fechaCita = document.createElement('P')
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`

    const horaCita = document.createElement('P')
    horaCita.innerHTML = `<span>Hora:</span> ${hora}`

    // crearBoton
    const botonReservar = document.createElement("BUTTON")
    botonReservar.classList.add("boton")
    botonReservar.textContent = "Reservar cita"
    botonReservar.onclick = () => {
        reservarCita()
    }
    clienteDatos.appendChild(nomCliente)
    clienteDatos.appendChild(fechaCita)
    clienteDatos.appendChild(horaCita)
    clienteDatos.appendChild(botonReservar)
}

function formatearFecha(fecha) {
    const opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    const fechaObj = new Date(fecha)
    fechaObj.setDate(fechaObj.getDate() + 1) // Agregar un día
    return fechaObj.toLocaleDateString('es-ES', opcionesFecha)
}

// conectarse a la api para mandar datos
async function reservarCita() {

    const { id, nombre, fecha, hora, servicios } = cita //IMPORTANTE!!!  servicios debe ir igual en el post del controlador de api!!!

    // obtener solo los id de los servicios, porque la BD contiene lo demás
    const idServicios = servicios.map(server => server.id)

    // primer paso, definir el from data para enviar datos a la api
    const datos = new FormData()
    // agregando datos al datos-- A LA TABLA CITAS
    datos.append('fecha', fecha)
    datos.append('hora', hora)
    datos.append('usuarioId', id)
    // dato de otra tabla
    datos.append('servicios', idServicios)

    try {
        const url = `/api/citas`
        // tercer paso, fetch a la url para poder conectarnos
        const respuesta = await fetch(url, {
            // definimos el metodo a usar
            method: "post",
            body: datos
        })
        // cuarto paso, .json() al resultado para terminar de conectar
        const resultado = await respuesta.json()
        console.log(resultado)

        if (resultado.resultado) {
            Swal.fire({
                icon: "success",
                title: "Cita correctamente",
                text: "Tu cita fue creada correctamente",
                button: "OK"
            }).then(() => {
                setTimeout(() => {
                    window.location.reload()
                }, 3000);

            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error al procesar su cita",
          });
    }
}