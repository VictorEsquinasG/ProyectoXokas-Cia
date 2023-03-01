/**
 * Clase que contiene todas las funciones PUT en JavaScript
 * @author Víctor Esquinas
 */

function putReserva(reserva) {
    let reser = {};
    reser.reserva = reserva;
    
    $.ajax({
        type: "PUT",
        url: "/api/reserva",
        data: JSON.stringify(reser),
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
        }
    });
}

function persistEvento(evento) {
    let id = evento.id;
    
    if (id !== undefined && id !== null && id !== '') {
        // Ya existe, lo actualizamos
        putEvento(evento);
    }else {
        // Lo creamos
        postEvento(evento);
    }
}

function putEvento(evento) {
    let ev = {};
    ev.evento = evento;
    
    $.ajax({
        type: "PUT",
        url: "/api/evento",
        data: JSON.stringify(ev),
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
        }
    });
}
function postEvento(evento) {
    let ev = {};
    
    // evento.fecha = [evento.fecha[0] + "-" + evento.fecha[1] + "-" + evento.fecha[2]];
    
    ev.evento = evento;
    
    $.ajax({
        type: "POST",
        url: "/api/evento",
        data: JSON.stringify(ev),
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
        }
    });
}