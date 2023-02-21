/**
 * Ventana modal 
 * que impide que toquemos otras funciones mientras esté desplegada
 * @use JQUERY ui
 * @author Víctor Esquinas
 */
$(function () { // Window.onload

    // Cogemos los datos que necesitamos
    var allTramos = getTramos();
    var allJuegos = getJuegos();
    var allMesas = getMesas();


    /* PLANTILLAS */
    var plantillaReserva =
        `<h1>Haz tu reserva en minutos</h1>
        <article>
            <form>
                <div class="row mt-2">
                    <div class="col-12 col-md-6">
                        <label for="datePicker">Fecha de reserva:</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="text" id="datePicker">
                    </div>
                </div>
                    
                <div class="row mt-2">
                    <div class="col-12 col-md-6">
                        <label for="selecTramos">Tramo horario:</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <select name="tramo" id="selecTramos"></select>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-12 col-md-6">
                        <label for="selecJuego">Juego:</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <select name="juego" id="selecJuego"></select>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 col-md-6">
                        <label for="selecMesa">Mesa:</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <select name="" id="selecMesa"></select>
                    </div>
                </div>

                <input type="submit" class="btn btn-primary col-12 mt-3" id="btnSubmit" value="RESERVAR">

            </form>
        </article>
        `

    var plantillaJuego =
        `
        <article>

            <form>

                <div class="row">
                    <h5 class="nombre text-center text-md-start"></h5>
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

            </form>
        </article>
        ` //TODO El value debe llevarte a hacer una reserva con el juego seleccionado



    // Las convertimos en JQuery
    var JplantillaReserva = $(plantillaReserva);
    var JplantillaJuego = $(plantillaJuego);


    // El dialog
    var dialog = $('<div />').attr('id', 'dialog');

    // USAMOS 1 BOTON PARA ABRIR LA VENTANA MODAL
    $("#creaReserva").click(function (ev) {

        ev.preventDefault();

        /* RELLENAMOS LOS SELECTS */
        rellenaSelecReserva();

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
            .show(function () {
                //Volvemos a buscar datepickers
                ConvierteDatePicker();
            })
            .submit(function (e) {
                e.preventDefault();

                let formulario = $(this);
                // Cogemos todos los campos del formulario
                let fecha = formulario.find('#datePicker').val();
                let idTramo = parseInt(formulario.find('#selecTramos').val());
                let idJuego = parseInt(formulario.find('#selecJuego').val());
                let idMesa = parseInt(formulario.find('#selecMesa').val());

                // creamos la reserva  
                let reserva = new Reserva(null, fecha, true, null, null, idJuego, idMesa, idTramo);
                // Hacemos el POST
                if (setReserva(reserva)) {
                    $(this).parent().remove();
                } else {
                    console.log("ERROR al crear la reserva");
                }
            })
            ;

    });

    $('div[id^=ver_reserva_]').click(function (ev) {
        ev.preventDefault();
        let id = $(this).attr('id').split('_')[2];

        /* 
          PARA QUE SE PUEDA REUTILIZAR EL MODAL
          PEDIRÁ UN OBJETO RESERVA
          DEL CUAL COGEREMOS LOS CAMPOS QUE TENGA SELECCIONADOS
      */
        let reservaActual = getReserva(id).responseJSON.Reserva;

        console.log(reservaActual);
        let date = new Date(reservaActual.fecha.date);
        let fecha = (date.toLocaleDateString('es'));
        let tramo = reservaActual.tramo.id;
        let tramoString = reservaActual.tramo.string;
        let juego = reservaActual.juego.id;
        let mesa = reservaActual.mesa.id;

        /* RELLENAMOS LOS SELECTS */
        rellenaSelecReserva();

        // Damos los valores a la plantilla
        JplantillaReserva.find('#datePicker').val(fecha);
        JplantillaReserva.find('#selecTramos').val(tramo);
        JplantillaReserva.find('#selecJuego').val(juego);
        JplantillaReserva.find('#selecMesa').val(mesa);
        JplantillaReserva.find('#btnSubmit').val('Guardar cambios');


        dialog.dialog({
            modal: true,
            width: "700px",
            title: "Reserva del " + fecha + " durante las " + tramoString,
            hide: {
                effect: "explode",
                duration: 1000
            }
        })
            .append(JplantillaReserva)
            .submit(function () {
                // PUT
                let formulario = $(this);
                // Cogemos todos los campos del formulario
                let fecha = formulario.find('#datePicker').val();
                let idTramo = parseInt(formulario.find('#selecTramos').val());
                let idJuego = parseInt(formulario.find('#selecJuego').val());
                let idMesa = parseInt(formulario.find('#selecMesa').val());

                let reserva = new Reserva(reservaActual.id,fecha,true,null,null,idJuego,idMesa,idTramo);
                putReserva(reserva);
            })
            ;
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
            minHeight: "900px",
            title: "Juega a " + juego.nombre + "🎲♟️",
        }).append(JplantillaJuego);

    });


    function rellenaSelecReserva() {
        /* CAPTURAMOS LOS SELECT */
        let selecTramos = JplantillaReserva.find('#selecTramos');
        let selecJuegos = JplantillaReserva.find('#selecJuego');
        let selecMesas = JplantillaReserva.find('#selecMesa');
        /* VACIAMOS LOS SELECT */
        selecTramos.html('');
        selecJuegos.html('');
        selecMesas.html('');
        /* RELLENAMOS LOS SELECTS */
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
    }
});