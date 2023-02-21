/**
 *   FUNCIONES PARA OBTENER ENTIDADES MEDIANTE API
 * @description Contiene funciones que realizan peticiones GET a distintas APIs
 * @author Víctor Esquinas
 * @version 1.0
 * */


function getTramos() {
    return $.ajax({
        type: "GET",
        url: "api/tramo/",
        async: false,
    })
        .done(function (respuesta) {
            let tramos = respuesta.tramos;

            return tramos;
        })
        ;

}

function getTramo(id) {
    return $.ajax({
        type: "GET",
        url: "api/tramo/" + id,
        async: false,
    })
        .done(function (respuesta) {
            let tramos = respuesta.tramo;

            return tramos;
        })
        ;

}

function getDiasFestivos() {
    return $.ajax({
        type: "GET",
        url: "/api/festivos",
        async: false,
        success: function (response) {
            console.log(response);
        }
    });
}


function getJuegos() {
    return $.ajax({
        type: "GET",
        url: "api/juego/",
        async: false,
    })
        .done(function (respuesta) {
            let juegos = respuesta.juegos;

            return juegos;
        })
        ;

}
function getJuego(id) {
    return $.ajax({
        type: "GET",
        url: "api/juego/" + id,
        async: false,
    })
        .done(function (respuesta) {
            let juegos = respuesta.juego;

            return juegos;
        })
        ;

}

function getMesas() {
    return $.ajax({
        type: "GET",
        url: "api/mesa/",
        async: false,
    })
        .done(function (respuesta) {
            let mesas = respuesta.mesas;

            return mesas;
        });

}

function getMesa(id) {
    return $.ajax({
        type: "GET",
        url: "api/mesa/" + id,
        async: false,
    })
        .done(function (respuesta) {
            let mesa = respuesta.mesa;

            return mesa;
        });

}

function getReserva(id) {
    return $.ajax({
        type: "GET",
        url: "api/reserva/" + id,
        async: false,
    })
        .done(function (respuesta) {
            let reserva = respuesta.reserva;

            return reserva;
        });

}