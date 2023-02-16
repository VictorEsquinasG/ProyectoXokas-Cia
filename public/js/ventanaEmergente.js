/**
 * Ventana modal 
 * que impide que toquemos otras funciones mientras esté desplegada
 * @use JQUERY ui
 * @author Víctor Esquinas
 */
$(function () { // Window.onload








    /* PLANTILLAS */
    var plantillaReserva =
        `<h1>Haz tu reserva en minutos</h1> <br><br><br>
        <article>
            <div class="col-12 col-md-6">
                <label for="datePicker">Fecha de reserva:</label>
                <input type="text" id="datePicker">
            </div>

            <div class="col-12 col-md-6">
                <label for="selecTramos">Tramo horario:</label>
                <select name="" id="selecTramos">                    
                    <option value="-1" disabled selected></option>`;
    // $.each(allTramos, function (i, v) {
    //     plantillaReserva += `<option value="` + v.id + `">` + v + `</option>`;
    // });
    plantillaReserva += `
                </select>
            </div>
            
            <div class="col-12 col-md-6">
                <label for="selecJuego">Juego:</label>
                <select name="" id="selecJuego">                    
                    <option value="-1" disabled selected></option>`;
    // $.each(allJuegos, function (i, v) {
    //     plantillaReserva += `<option value="` + v.id + `">` + v + `</option>`;
    // });

    plantillaReserva += `
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="selecMesa">Mesa:</label>
                <select name="" id="selecMesa">
                    <option value="-1" disabled selected></option>`;
    // $.each(allMesas, function (i, v) {
    //     plantillaReserva += `<option value="` + v.id + `">` + v + `</option>`;
    // });
    plantillaReserva += `
                </select>
            </div>

            <input type="hidden" name="_target_path" value="/">

            <button type="submit" class="btn btn-primary">RESERVAR</button>
        </aritcle>
        `

    var plantillaJuego =
        `
        <article>
            <div class="row">
                <h5 class="nombre"></h5>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <img class="img" alt=""></img>
                </div>
                <div class="col-12 col-md-3">
                    <p class="num_jugadores"></p>
                </div>
                <div class="col-12 col-md-3">
                    <p class="tamaniotablero"></p>
                </div>
            </div>
                
            <div class="col-12">
                <h5>Descripción:</h5>
                <p class="desc"><p>
            </div>

            <h5>Haz tu reserva y juegalo con tus amigos</h5>

            <input type="hidden" name="_target_path" value="/">

            <button type="submit" class="btn btn-primary">RESERVAR</button>
        </aritcle>
        ` //TODO El value debe llevarte a hacer una reserva con el juego seleccionado



    // Las convertimos en JQuery
    var JplantillaReserva = $(plantillaReserva);
    var JplantillaJuego = $(plantillaJuego);


    // El dialog
    var dialog = $('<div />');

    // USAMOS 1 BOTON PARA ABRIR LA VENTANA MODAL
    debugger;
    $("#creaReserva").click(function (ev) {
        debugger;
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
        var optTramos = [];
        var optJuegos = [];
        var optMesas = [];
        console.log(info);

        $.each(allTramos, function (i, v) {
            optTramos += $('</option>').val(v.id).html(v);
        });
        $.each(allJuegos, function (i, v) {
            optJuegos += $('</option>').val(v.id).html(v);
        });
        $.each(allMesas, function (i, v) {
            optMesas += $('</option>').val(v.id).html(v);
        });
        JplantillaReserva.find('#selecTramos').html(optTramos);
        JplantillaReserva.find('#selecJuego').html(optJuegos);
        JplantillaReserva.find('#selecMesa').html(optMesas);


        /* CREAMOS UN MODAL  */
        dialog.dialog({
            modal: true,
            width: "700px",
            title: "Reservar mesa 📆🎲",
            // autoOpen: true,
            // show: {
            //     effect: "blind",
            //     duration: 1000
            // },
            // hide: {
            //     effect: "explode",
            //     duration: 1000
            // }
        })
            // .append(JplantillaReserva)
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
        $.ajax({
            type: "GET",
            url: "/api/juego/" + id,
        })
            .done(function (jogo) {
                let juego = jogo.juego;

                // Adaptamos la plantilla
                JplantillaJuego.find('.nombre').html(juego.nombre);
                JplantillaJuego.find('.img').attr("src", juego.imagen);
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




})

/* FUNCIONES */
function getTramos() {
    var get = [];
    return $.ajax({
        type: "GET",
        url: "api/tramo/",
        async: false,
    })
        .done(function (respuesta) {
            console.log(respuesta);
            let tramos = respuesta.tramos;
            $.each(tramos, function (i, v) {
                get.push(v);
            });
            return get;
        });

}
function getJuegos() {
    var get = [];
    $.ajax({
        type: "GET",
        url: "api/juego/",
        async: false,
    })
        .done(function (respuesta) {
            console.log(respuesta);
            let juegos = respuesta.juegos;
            $.each(juegos, function (i, v) {
                get.push(v);
            });
            return get;
        });

}
function getMesas() {
    var get = [];
    $.ajax({
        type: "GET",
        url: "api/mesa/",
        async: false,
    })
        .done(function (respuesta) {
            console.log(respuesta);
            let mesas = respuesta.mesas;
            $.each(mesas, function (i, v) {
                get.push(v);
            });
            return get;
        });

}