document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    buscarPorFecha();
}

function buscarPorFecha(){
    const fechaInput=document.querySelector('#fecha');
    fechaInput.addEventListener('input', function(e){ //cuando se seleccione una fecha en input se ejecuta un callback con el evento
        const fechaSeleccionada = e.target.value;
        
        window.location = `?fecha=${fechaSeleccionada}`; //redirecciona la url con la fecha seleccionada

    });
}