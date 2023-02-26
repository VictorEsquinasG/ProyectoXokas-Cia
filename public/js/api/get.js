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
            // Devolvemos los festivos
            return response.festivos;
        }
    });
}

function getJuegosByNumJugadores(num) {
    return $.ajax({
        type: "GET",
        url: "api/juego/byJugadores/"+num,
        async: false,
    })
        .done(function (respuesta) {
            let juegos = respuesta.juegos;

            return juegos;
        })
        ;
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

function getDisposiciones(all_distribuciones) {

    $.getJSON("/api/distribucion",
        function (data) {
            $.each(data.distribuciones, function (i, v) {
                all_distribuciones.push(v);
            });
        }
    );
}


function getDistribucion(fecha) {
    return $.ajax({
        type: "GET",
        url: "api/distribucion/fecha/" + fecha,
        async: false,
        success: function (response) {
            return (response.distribuciones);
        }
    });
}

function getFechaDisposicionByName(all_distribuciones,alias) {
    var distribucion = null;

    // Buscamos nuestra mesa
    $.each(all_distribuciones, function (i, v) {
        // Nuestra mesa = nuestra distribucion
        if (v.alias == alias) {
            console.log(v);
            distribucion = v.fecha.date;
            return false; // Rompemos el bucle
        }
    });
    // La devolvemos
    return distribucion;
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

function getMesasHoy(fecha) {
    // Las mesas que devolveremos
    const mesas = [];
    // Consultamos las distribuciones
    let disposiciones =
        $.ajax({
            type: "GET",
            url: "api/distribucion/fecha/" + JSON.stringify({"date": fecha}),
            async: false,
            success: function (response) {
                
                return response.distribuciones;
            }
        });

    disposiciones = disposiciones.responseJSON.distribuciones;
    
    if (disposiciones.length > 0) {
        // Hay disposiciones, buscamos las mesas de la nuestra 
        $.each(disposiciones, function (i, v) {
            let dist = v;
            // Cogemos la mesa
            let mesa = getMesa(v.mesa).responseJSON.mesa;
            // Alteramos la posición de la mesa
            mesa.pos_x = dist.pos_x;
            mesa.pos_y = dist.pos_y;
            mesas.push(mesa);
        });
    } else {
        let data = getMesas();
        let arrayMesas = data.responseJSON.mesas;
        $.each(arrayMesas, function (i, v) { 
            // Ponemos todas las mesas
            mesas.push(v);
        });
    }

    return mesas;
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