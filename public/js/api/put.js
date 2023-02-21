/**
 * Clase que contiene todas las funciones PUT en JavaScript
 * @author Víctor Esquinas
 */

function putReserva(reserva) {
    let reser = {};
    reser.reserva = reserva;
    debugger;
    $.ajax({
        type: "PUT",
        url: "api/reserva",
        data: JSON.stringify(reser),
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
        }
    });
}