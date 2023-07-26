let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

//creacion de objeto cita
const cita = { 
    id: '', //este es el id del usuario desde SESSION
    nombre: '',
    fecha: '', 
    hora: '',
    servicios: []
}
document.addEventListener('DOMContentLoaded', function(){ //inicializa cuando el dom esté cargado
    iniciarApp();
});

function iniciarApp(){
    mostrarSeccion(); //Muestra y oculta las secciones 
    tabs(); //Cambia la sección cuando se presionen los tabs
    botonesPaginador(); //agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); // Consulta la API en el backend de php
    idCliente(); //aññade el id del cliente al objeto cita
    nombreCliente(); //añade el nobre del cliente al objeto de cita
    seleccionarFecha(); //añade la fecha de la cita en el objeto
    seleccionarHora(); //añade la hora de la cita en el objeto

    mostrarResumen(); //Muestra el resumen de la cita
}
function mostrarSeccion(){
    //ocultar la sección que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){ //la primera vez no hay clase mostrar, se tiene que agregar en el paso siguiente
        seccionAnterior.classList.remove('mostrar');
    }
    
    //Seleccionar la seección con el paso
    const seccion = document.querySelector(`#paso-${paso}`); //se quiere acceder al id de paso-1, paso-2, paso-3
    seccion.classList.add('mostrar'); //muestra  la seccion 

    //quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }

    //Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`); //[] es el selector de atributo personalizados
    tab.classList.add('actual');
}
function tabs(){ //agrega o cambia la variable de paso segun el tab seleccionado
    const botones = document.querySelectorAll('.tabs button'); //selecciona la clase tabs y la etiqueta button
    botones.forEach( (boton) =>{ //itera en cada boton por clicks
         boton.addEventListener('click', function(e){ //e es el evento que se registra al dar clic
            paso=parseInt( e.target.dataset.paso); //convierte un string a entero
            mostrarSeccion(); //muestra la seccion de acuerdo a paso
            botonesPaginador(); //manipula que desaparezcan los botones
            // if(paso ===3){
            //     mostrarResumen(); //valida que el objeto cita esté lleno
            // }

         });
    })
}

function botonesPaginador(){ //oculta los botones segun avance o retorceda el paginador
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');
    if(paso === 1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }else if( paso === 3){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    }else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }
    mostrarSeccion(); //hace aparecer las secciones
}
function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function(){
        if(paso <= pasoInicial) return; //la variable paso ya no pasa de 1
        paso--;
        // console.log(paso);
        botonesPaginador(); //oculta y aparece los botones
    });
}
function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function(){
        if(paso >= pasoFinal) return; //la variable paso ya no pasa de 1
        paso++;
        // console.log(paso);
        botonesPaginador(); //oculta y aparece los botones
    });
}

async function consultarAPI(){ //inicializa una función asíncrona
    
    try{
        const url = `${location.origin}/api/servicios`; //url que tiene la aPI
        const resultado = await fetch(url); //fetch permite consumir el servicio
        //despues del await, el siguiente codigo no se ejecuta hasta que realice el fetch
        const servicios = await resultado.json();
        mostrarServicios(servicios);
        
        

    }catch(error){ //si hay un error enla consulta lo imprime
        console.log(error);
    }
}
function mostrarServicios(servicios){ //servicios es todo el conjunto de servicios
    servicios.forEach(servicio => { //arreglo.foreach(valor => {callback) //servicio es un solo servicio
        // console.log(servicio); //imprime cada servicio que contiene id, nombre y precio
        const {id, nombre, precio} = servicio;
        /**scripting */
        const nombreServicio= document.createElement('P'); //crea un parrafo por cada nombre
        nombreServicio.classList.add('nombre-servicio'); //agrega una clase
        nombreServicio.textContent = nombre;    //agrega como contenido cada elemento nombre
 
        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV'); // crea un elemento <div>
        servicioDiv.classList.add('servicio'); //agrega clase para dar estilos
        servicioDiv.dataset.idServicio = id; //crea un atributo personalizado data-id-servicio = id
        //evento
        servicioDiv.onclick = function(){
            // console.log(servicio);
            seleccionarServicio(servicio);
        }
        
        // console.log(servicioDiv); //imprime varios <div class="servicio" data-id-servicio="1">
        servicioDiv.appendChild(nombreServicio); //agrega el parrafo nombreServicio a un Div
        servicioDiv.appendChild(precioServicio); //agrega el parrafo precio al div
        
        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio){ //"id": "1", "nombre": "Corte de Cabello Mujer", "precio": "80.00"
    const {id} = servicio; //extrae el id del servicio actual
    const {servicios} = cita; //extrae el atributo array de objetos servicios del objeto cita
    //identificar el elemento al que se le da clic
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`); //selecciona el div con el atributo personalizado del servicio actual
    //Comprobar su un servicio ya fue agregado
    if(servicios.some( agregado => agregado.id === id)){ //busca en cada objeto del arreglo servicios si algun elemento tiene un atributo con el mismo id de servicio
        //eliminarlo
        cita.servicios = servicios.filter(agregado => agregado.id !== id); //elimina todos los elementos que tengan un id diferente
        divServicio.classList.remove('seleccionado'); //ya que coincide, debe borrarse la clase
    }else{
        //agregarlo 
        cita.servicios = [...servicios, servicio]; //en cita->servicios agrega todo servicios (argumento) más servicio
        divServicio.classList.add('seleccionado'); //agrega una clase al div seleccionado
    }
    // console.log(cita);
}

function idCliente(){ //asigna el value de #id a cita
    cita.id = document.querySelector('#id').value; //selecciona el value en el id #nombre   
}

function nombreCliente(){
    //asigna al objeto sus atributos
    cita.nombre = document.querySelector('#nombre').value; //selecciona el value en el id #nombre
    
}
function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha'); //se selecciona el id de fecha
    inputFecha.addEventListener('input', function(e){ //Cuando se selecciona la fecha se ejecuta la funcion anonima
        const dia = new Date(e.target.value).getUTCDay();
        
        if( [6, 0].includes(dia) ){ //determina si el array [6,0] incluye a dia
            e.target.value = ''; //forza el value a que esté vacío
            // console.log('Sábados y Domingos no abrimos'); 
            mostrarAlerta('Fines de semana no permitidos', 'error', '.formulario');
        }else{
            // console.log('correcto');
            cita.fecha = e.target.value;
        }
    });
}   

function seleccionarHora(){
    const inputHora = document.querySelector('#hora'); //selecciona el id #hora
    inputHora.addEventListener('input', function(e){

        const horaCita = e.target.value; //asigna el valor del input hora
        const hora = horaCita.split(":")[0]; //asigna en un array los elementos separador por :. Guarda el primer elemento
        if(hora < 10 || hora > 18){
            // console.log('Horas no validas');
            mostrarAlerta('Hora no Válida', 'error', '.formulario');
            e.target.value = ''; //forza el value a que no seleccione nada
        }else{
            // console.log('Hora válida');
            cita.hora = e.target.value;
            // console.log(cita); 
        }
    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){ //crea una alerta

    //evitar que se creen muchas alertas
    const alertaPrevia = document.querySelector('.alerta'); //selecciona la clase de la alerta
    if(alertaPrevia){ //si la alerta ya existe, sale de la funcion mostrarAlerta
        alertaPrevia.remove();
    }
    //scriptin para crear la alerta
    const alerta = document.createElement('DIV'); //crea una div
    alerta.textContent = mensaje;   //agrega al div el argumento de mensaje
    alerta.classList.add('alerta');  //agrega la clase alerta que se encuentra en css
    alerta.classList.add(tipo) ;  //agrega el tipo de alerta (error o exito) error
    // console.log(alerta);
    const referencia = document.querySelector(elemento); //selecciona el id paso2 y su parrafo
    referencia.appendChild(alerta);

    if(desaparece){
        //eliminar la alerta
        setTimeout(() => { //funcion se ejecuta 3seg después
            alerta.remove(); 
        }, 3000);
    }
}

function mostrarResumen(){
    const resumen = document.querySelector('.contenido-resumen'); //mostrará el contenido del resumen 
    //limpiar el contenido de resumen
    while(resumen.firstChild){ // selescciona el primer contenido de resumen
        resumen.removeChild(resumen.firstChild); //elimina el contenido
    }
    //si no se ha llenado l os inputs aparece alerta
    if( Object.values(cita).includes('') || cita.servicios.length === 0){
        // console.log('Hacen falta datos o servicios');
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false);
        return; //sale de la función para que no se ejecute el codigo siguiente
    }
    // console.log('Todo bien');
    //mostrar resumen usando scripting
    //se hace destructiring a cita
    const {nombre, fecha, hora, servicios} = cita; //destructiring a cita
    
    
    //heading para Servicios en Resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios'; 
    resumen.appendChild(headingServicios);

    //se itera sobre servicios ya que es un arreglo
    servicios.forEach(servicio =>{ //servicio es un solo servicio
        const {id, precio, nombre} = servicio; //destructuring al objeto servicio
        const contenedorServicio = document.createElement('DIV'); //crea un div
        contenedorServicio.classList.add('contenedor-servicio'); //se grega clase para css para incrustar el servicio
        
        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre; //agrega el contenido de nombre del servicio
        
        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $ ${precio}`;
        
        contenedorServicio.appendChild(textoServicio); //agrega el nombre al div creado
        contenedorServicio.appendChild(precioServicio); //agrega el precio al div creado
 
        resumen.appendChild(contenedorServicio); //agrega el div creado a contenido-resumen


    }); //despues de agregar los servicios se agrega el cliente

    //heading para Cita en Resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita'; 
    resumen.appendChild(headingCita);

    //scripting para los datos de cliente
    const nombreCliente = document.createElement('P'); //crea parrafo para nombre
    nombreCliente.innerHTML = `<span>Nombre: </span> ${nombre}`; //usa template string para crear html

    //formatear la fecha en español
    const fechaObj = new Date(fecha); //Date Thu Jul 13 2023 18:00:00 GMT-0600 (hora estándar central)
    const mes = fechaObj.getMonth(); //extrae el mes empezando desde 0
    const dia = fechaObj.getDate() + 2; //extrae el día empezando desde 0. se suma 2 para compensarlas dos instancias
    const año = fechaObj.getFullYear(); //extrae el año 

    const fechaUTC =new Date( Date.UTC(año, mes, dia)); //vuelve a instancia la fecha a partir del 
    const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'} //configura las opciones de formateado de fecha
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);
    // console.log(cita);
    const fechaCita = document.createElement('P');//crea parrado para fecha
    fechaCita.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`; //usa template string para crear html

    const horaCita = document.createElement('P'); //crea parrafo para hora
    horaCita.innerHTML = `<span>Hora: </span> ${hora} Horas`; //usa template string para crear html

    //boton para crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent='Reservar Cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente); //agrega los elementos a contenido-resumen
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);
}
//funcion asincrona, porque no se sabe cuanto tardará en conectarse al servidor
async function reservarCita(){ //funcion para enviar la información de la cita
    
    const {nombre, fecha, hora, servicios, id } = cita;

    //se extraen los ids de los servicios
    const idServicios = servicios.map(servicio => servicio.id); //unción flecha que toma un parámetro llamado servicio y devuelve el valor de la propiedad id de ese parámetro.
    // console.log(idServicios);

    const datos = new FormData(); //crea un objeto de campo y valores para su envío en XMLHttpRequest.send
    //estos datos se van a enviar vía POST como se declaraen APIController
    // datos.append('nombre', nombre); //agrega el cammpo nombre y su valor
    datos.append('fecha', fecha); //POST = ['fecha' => XXX, 'hora' => XXX, 'usuarioId' => XXX, 'servicios' => XXX] en php
    datos.append('hora', hora);
    datos.append('usuarioId', id); //este es e id del usuairo
    datos.append('servicios', idServicios); //por el momento no se usa
    // console.log([...datos]);
    // return;    
    //Petición hacia la api

    try { //ejecuta este codigo si la petición al servidor funciona
        //petición hacia la api
        const url = `${location.origin}/api/citas`;
        const respuesta = await fetch(url, {
        method: 'POST',
        body: datos
        }); //fetch devuelve una promesa que se resuelve con la respuesta de la petición
        // return;
        // console.log(respuesta); //imprime estatus 200, conexión correcta
        const resultado = await respuesta.json(); //espera la respuesta 
        console.log(resultado); //resultado es lo que está en el metodo guardar de APIController
                                //{mensaje: "todo ok"}
        //alerta
        if(resultado.resultado){ 
            Swal.fire({
            icon: 'success',
            title: 'Cita creada',
            text: 'Tu cita fue reservada correctamente!',
            button: 'Cerrar'
          }).then( () => { //.then y llama un callback sin argumento
            setTimeout(() => { //espera tres segundos para recargar la página
                window.location.reload();    
            }, 3000);
            
          });

    }
    } catch (error) { //este codigo solo se ejecuta si no existe conexión con la api
        Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un error al guardar la cita!'
        })
    }
    
}