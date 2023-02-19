/**
 * Ventana modal 
 * que impide que toquemos otras funciones mientras esté desplegada
 * @use JQUERY ui
 * @author Víctor Esquinas
 */
$(function () { // Window.onload








    /* PLANTILLAS */
    var plantillaReserva =
        `<h1>Haz tu reserva en minutos</h1>
        <div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <label for="datePicker">Fecha de reserva:</label>
                </div>
                <div class="col-12 col-md-6">
                    <input type="text" id="datePicker">
                </div>
            </div>
                
            <div class="row">
                <div class="col-12 col-md-6">
                    <label for="selecTramos">Tramo horario:</label>
                </div>
                <div class="col-12 col-md-6">
                    <select name="tramo" id="selecTramos"></select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-6">
                    <label for="selecJuego">Juego:</label>
                </div>
                <div class="col-12 col-md-6">
                    <select name="juego" id="selecJuego"></select>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <label for="selecMesa">Mesa:</label>
                </div>
                <div class="col-12 col-md-6">
                    <select name="" id="selecMesa"></select>
                </div>
            </div>

            <input type="hidden" name="_target_path" value="/">

            <button type="submit" class="btn btn-primary">RESERVAR</button>
        </div>
        `

    var plantillaJuego =
        `
        <div>
            <div class="row">
                <h5 class="nombre"></h5>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <img class="img" alt=""></img>
                </div>
                <div style="margin:auto;" class="col-12 col-md-3 text-center">
                    <p class="num_jugadores"></p>
                </div>
                <div style="margin:auto;" class="col-12 col-md-3 text-center">
                    <p class="tamaniotablero"></p>
                </div>
            </div>
                
            <div class="col-12">
                <h5>Descripción:</h5>
                <p class="desc"><p>
            </div>

            <p style="font-size:20px">¡Haz tu reserva y juegalo con tus amigos!</p>

            <input type="hidden" name="_target_path" value="/">

            <button type="submit" class="btn btn-primary">RESERVAR</button>
        </div>
        ` //TODO El value debe llevarte a hacer una reserva con el juego seleccionado



    // Las convertimos en JQuery
    var JplantillaReserva = $(plantillaReserva);
    var JplantillaJuego = $(plantillaJuego);


    // El dialog
    var dialog = $('<div />');

    // USAMOS 1 BOTON PARA ABRIR LA VENTANA MODAL
    $("#creaReserva").click(function (ev) {

        ev.preventDefault();
        var allTramos = getTramos();
        var allJuegos = getJuegos();
        var allMesas = getMesas();
        /* 
            PARA QUE SE PUEDA REUTILIZAR EL MODAL
            PEDIRÁ UN OBJETO RESERVA
            DEL CUAL COGEREMOS LOS CAMPOS QUE TENGA SELECCIONADOS
        */
        let info = $(this).data('reserva');
        console.log(info);

        /* RELLENAMOS LOS SELECTS */
        let selecTramos = JplantillaReserva.find('#selecTramos');
        selecTramos
                .append('<option value="-1" disabled selected></option>');
        $.each(allTramos.responseJSON.tramos, function (i, v) {
            
            selecTramos
                .append(
                    $('<option/>')
                        .data(v)
                        .val(v.id)
                        .html(v.string)
                );
        });
        // debugger
        let selecJuegos = JplantillaReserva.find('#selecJuego');
        selecJuegos
                .append('<option value="-1" disabled selected></option>');
        $.each(allJuegos.responseJSON.juegos, function (i, v) {

            selecJuegos
                .append(
                    $('<option/>')
                        .data(v)
                        .val(v.id)
                        .html(v.string)
                );
        });
        let selecMesas = JplantillaReserva.find('#selecMesa');
        selecMesas
                .append('<option value="-1" disabled selected></option>');
        $.each(allMesas.responseJSON.mesas, function (i, v) {

            selecMesas
                .append(
                    $('<option/>')
                        .data(v)
                        .val(v.id)
                        .html(v.string)
                );
        });


        /* CREAMOS UN MODAL  */
        dialog.dialog({
            modal: true,
            width: "700px",
            title: "Reservar mesa 📆🎲",
            show: {
                effect: "blind",
                duration: 1000
            },
            hide: {
                effect: "blind",
                duration: 500
            }
        })
            .append(JplantillaReserva)
            .submit(function (e) { 
                e.preventDefault();
                //TODO creamos la reserva  
            });
            ;

    });

    $('div[id^=ver_reserva_]').click(function (ev) {
        ev.preventDefault();

        let info = $(this).data('reserva');
        console.log(info);

        dialog.dialog({
            modal: true,
            width: "700px",
            title: "Juega a " + reserva.nombre + "🎲♟️",
            show: {
                effect: "blind",
                duration: 1000
            },
            hide: {
                effect: "explode",
                duration: 1000
            }
        }).append(JplantillaReserva);
    });


    /*
    * Clase que captura los botones de 'Ver más' de la pestaña de juegos y los enlaza
    * a una página personalizada con los detalles de cada juego
    */
    $('a[id^=juego_]').click(function (ev) {
        ev.preventDefault();
        // Averiguamos qué juego es
        let id = this.id.split('_')[1];
        let juego = getJuego(id);
        juego = juego.responseJSON.juego;
        console.log(juego);

        // Adaptamos la plantilla
        JplantillaJuego.find('.nombre').html(juego.nombre);
        JplantillaJuego.find('.img').attr("src", '../images/uploads/' + juego.imagen);
        JplantillaJuego.find('.desc').html(juego.descripcion);
        JplantillaJuego.find('.num_jugadores').html("Entre " + juego.jugadores.min + " y " + juego.jugadores.max);
        JplantillaJuego.find('.tamaniotablero').html(juego.tamañoTablero.ancho + "cm x " + juego.tamañoTablero.largo + "cm");

        dialog.dialog({
            modal: true,
            width: "700px",
            title: "Juega a " + juego.nombre + "🎲♟️",
        }).append(JplantillaJuego);

    });



});

/* FUNCIONES */
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