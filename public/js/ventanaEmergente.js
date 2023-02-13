/**
 * Ventana modal 
 * que impide que toquemos otras funciones mientras esté desplegada
 * @use JQUERY ui
 * @author Víctor Esquinas
 */
$(function () { // Window.onload


    
    
    
    
    var seltramos = selectTramos();
    var selJuegos = selectJuegos();
    var selMesas = selectMesas();


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
                    <option value="-1" disabled selected></option>` +
                    seltramos +
                `
                </select>
            </div>
            
            <div class="col-12 col-md-6">
                <label for="selecJuego">Juego:</label>
                <select name="" id="selecJuego">                    
                    <option value="-1" disabled selected></option>` +
                    selJuegos +
                `
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="selecMesa">Mesa:</label>
                <select name="" id="selecMesa">
                    <option value="-1" disabled selected></option>` +
                    selMesas +
                `
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
    $("#creaReserva").click(function (ev) {
        ev.preventDefault();
        /* 
            PARA QUE SE PUEDA REUTILIZAR EL MODAL
            PEDIRÁ UN OBJETO RESERVA
            DEL CUAL COGEREMOS LOS CAMPOS QUE TENGA SELECCIONADOS
        */
       let info = $(this).data('reserva');
       console.log(info);
        

        /* CREAMOS UN MODAL  */
        dialog.dialog({
            modal: true,
            width: "700px",
            title: "Reservar mesa 📆🎲",
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




    /* FUNCIONES */
    function selectTramos() {
        let select = [];
        $.ajax({
            type: "GET",
            url: "api/tramo/",
        })
        .done(function (respuesta) {
            console.log(respuesta);
            let tramos = respuesta.tramos;
            $.each(tramos, function (i, v) { 
                select.push('<option value="'+v.id+'">'+v+'</option>');
            });
            return select;
        });

    }
    function selectJuegos() {
        let select = [];
        $.ajax({
            type: "GET",
            url: "api/juego/",
        })
        .done(function (respuesta) {
            console.log(respuesta);
            let juegos = respuesta.juegos;
            $.each(juegos, function (i, v) { 
                select.push('<option value="'+v.id+'">'+v+'</option>');
            });
            return select;
        });

    }
    function selectMesas() {
        let select = [];
        $.ajax({
            type: "GET",
            url: "api/mesa/",
        })
        .done(function (respuesta) {
            console.log(respuesta);
            let mesas = respuesta.mesas;
            $.each(mesas, function (i, v) { 
                select.push('<option value="'+v.id+'">'+v+'</option>');
            });
            return select;
        });

    }
})  
