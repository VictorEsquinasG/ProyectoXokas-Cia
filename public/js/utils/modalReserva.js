/**
 * Esta librería hará lo mismo que el archivo del que "extiende" pero
 * lo hará de forma que los juegos tendrán un tablero arrastrable
 * @extends ventanaEmergente.js
 * @author Víctor Esquinas
 */


// Cogemos los datos que necesitamos
var allTramos = getTramos();
var allJuegos = getJuegos();
var allMesas = getMesas();
var all_distribuciones = [];

// El dialog
var dialog =
    $('<div />')
        .attr('id', 'dialog');

$(function () {

    getDisposiciones(all_distribuciones);

    // LA PLANTILLA COMO OBJETO JQUERY
    var Jplantilla = $(plantilla);

    /* EL ALMACÉN */
    // var stock =
    Jplantilla.find("#almacen").droppable({
        drop: function (ev, ui) {

            let tablero = ui.draggable;
            let texto = creaTexto(tablero.data('juego'));
            // guardaMesa(this, ui);
            tablero
                .append(texto)

            $(this).append(tablero);
        }
    }).css({
        overflow: 'scroll',
        backgroundColor: "#cccccc",
        backgroundImage: "url('../images/fondoAlmacen.jpg')",
        backgroundRepeat: "repeat",
        backgroundCover: "fill"
    })
        ;

    /* LA SALA PRINCIPAL */
    // var sala = 
    Jplantilla.find("#sala")
        .attr("class", "ml-3")

        .css({
            overflow: 'scroll',
            minHeight: '300px',
            backgroundColor: "#cccccc",
            backgroundImage: "url('../images/fondoSala.jpg')",
            backgroundRepeat: "repeat",
            backgroundCover: "fill"
        })
        ;


    // USAMOS 1 BOTON PARA ABRIR LA VENTANA MODAL
    $("#creaReserva").click(function (ev) {
        ev.preventDefault();

        /* CREAMOS EL FORMULARIO */
        let selecTramos = Jplantilla.find('#selecTramos');
        rellenaSelectTramos(selecTramos);

        let stock = Jplantilla.find('#almacen');
        rellenaStockJuegos(stock);
        /* CREAMOS UN MODAL  */
        dialog.dialog({
            modal: true,
            width: "85%",
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
            .append(Jplantilla)
            .show(
                /* CADA VEZ QUE MOSTRAMOS EL DIALOG */
                function () {
                    /* CREAMOS LA SALA */
                    new Sala($('#sala'));
                    // Volvemos a buscar datepickers
                    ConvierteDatePicker();

                    // El numero de jugadores cambiará los juegos disponibles
                    $('#numJugadores')
                        .change(function (ev) {
                            ev.preventDefault();

                            let jugadores = $(this).val();
                            vaciaStock();
                            rellenaStockJuegos($('#almacen'), jugadores);
                            validaNumSillas(parseInt(jugadores));
                        });

                    // Programamos el botón que retira el tablero de la mesa
                    $('#cancela_juego').click(function (ev) {
                        ev.preventDefault();

                        // Eliminamos el juego que haya seleccionado
                        $('.mesa').children('.juego').remove();
                    });
                    
                })
            /* SE HACE LA RESERVA */
            .submit(function (e) {
                e.preventDefault();

                let formulario = $(this);
                // Cogemos todos los campos del formulario
                let date = formulario.find('#datePicker').val().split('/');
                // pasamos la fecha a formato americano
                let fecha = date[2]+'-'+date[1]+'-'+date[0];
                let idTramo = parseInt(formulario.find('#selecTramos').val());
                let idJuego = parseInt($('#formu_reserva').data('Juego'));
                let idMesa = parseInt($('#formu_reserva').data('Mesa'));

                // creamos la reserva  
                let reserva = new Reserva(null, fecha, true, null, null, idJuego, idMesa, idTramo);

                // Borramos los juegos que hayan encima de las mesas (en caso que no sea la primera vez que vayamos a mostrar el dialog)
                $('#sala .mesa').remove('.juego');

                // Hacemos el POST
                if (setReserva(reserva)) {
                    // Vaciamos el formulario
                    formulario.find('#datePicker').val(null);
                    formulario.find('#selecTramos').val('');
                    $('#formu_reserva').data('Juego', '');
                    $('#formu_reserva').data('Mesa', '');
                    // Cerramos el modal
                    $(this).parent().remove();
                } else {
                    console.log("ERROR al crear la reserva");
                }
            })
            ;

    });


});//fin

/* PLANTILLA */
var plantilla =
    `
<form action="" id="formu_reserva">
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Fecha y hora de la reserva
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-12 col-md-6 l-grid-line">
                            <label class="m-1">Hora</label>
                            <select name="tramo" class="m-1" id="selecTramos"></select>
                        </div>
                        <div class="col-12 col-md-6 l-grid-line">
                            <label class="m-1">Fecha</label>
                            <input type="text" id="datePicker" autocomplete="off" class="m-1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Más Detalles
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="m-1">Número de jugadores:</label>
                        </div>
                        <div class="col-12 col-md-6">
                            <input type="number" name="num_jugadores" class="m-1" id="numJugadores">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Elegir juego
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row l-grid" id="container-salas">
                        <div id="almacen">
                            <!-- Los juegos -->
                        </div>
                        <div id="sala">
                            <!-- La mesa -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <a href="#" id="cancela_juego" class="btn btn-outline-danger">Deshacer selección</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    Hacer reserva
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row m-3">
                        <div class="col-12">
                            <p class="display-5 text-center">¿Has acabado ya?</p>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">HACER MI RESERVA AHORA</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
`

function rellenaSelectTramos(selecTramos) {
    // Vaciamos el select
    selecTramos.html('');
    // Rellenamos
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
}

function rellenaStockJuegos(salaJuegos, numJugadores = null) {
    let juegos = [];
    // Si no se nos da un nº de jugadores, quiere todos
    if (numJugadores == null) {
        juegos = allJuegos
    } else {
        juegos = getJuegosByNumJugadores(numJugadores);
    }
    // Vaciamos el select
    salaJuegos.html('');
    // Rellenamos

    $.each(juegos.responseJSON.juegos, function (i, v) {
        // Añadimos al select
        let texto = creaTexto(v);

        salaJuegos
            .append(
                $('<div/>')
                    .data('juego', v)
                    .attr('class', 'juego')
                    .attr('id', 'juego_' + v.id)
                    .attr('title', v.nombre) // TOOLTIP
                    .css({
                        position: "relative",
                        width: '100px',
                        height: '100px',
                        margin: '5px',
                        top: "0",
                        left: "0",
                        zIndex: "10",

                        background: '#fff url(../images/uploads/' + v.imagen + ') no-repeat',
                        backgroundPosition: 'center center'
                        // backgroundCover: "cover"
                    })
                    .draggable({
                        revert: true,
                        revertDuration: 0,
                        helper: 'clone',
                        accept: '#sala .mesa',
                        cursor: "move",
                        start: function (ev, ui) {
                            // Hacemos parcialmente transparente la mesa que estamos moviendo
                            ui.helper.prevObject.css({ 'opacity': '50%' });

                            ui.helper.css({
                                'border': '3.5px dotted #3de051',
                                'width': ui.helper.prevObject.eq(0).data('juego').tablero.ancho + 'px',
                                'height': ui.helper.prevObject.eq(0).data('juego').tablero.largo + 'px'
                            });
                           
                        },
                        stop: function (ev, ui) {
                            // Devolvemos su opacidad a la mesa colocada
                            $(ui.helper.prevObject[0]).css({ 'opacity': '100%' })
                        }
                    })
                    .append(texto)
            );
        // Creamos el tablero

    });
}





function creaTexto(juego) {
    let name = juego.nombre;
    let tamanio = juego.tablero.ancho + "x" + juego.tablero.largo + "cm";

    return $('<span/>')
        .text(name + ' (' + tamanio + ')')
        .css({
            display: 'block',
            width: '100%',
            height: '50%',
            'vertical-align': 'top',
            'text-align': 'center',
            color: '#faffed',
            backgroundColor: 'rgba(55, 56, 55,0.8)'
        });
}

function vaciaStock() {
    // Eliminamos todas las mesas de la página
    let juegos = $('#almacen > .juego');

    $.each(juegos, function (i, v) {

        $('#almacen').children('.juego[id=juego_' + v.id + ']').remove();

    });
}

function posicionValidaTablero(juego, mesa) {

    let entra = cabeTablero(juego, mesa) && mesaLibre(mesa);

    return (entra)
}

function cabeTablero(juego, mesa) {
    let game = juego.data('juego');
    let table = mesa.data('mesa');

    // El tablero debe ser más pequeño que la mesa (para caber dentro)
    // Le podemos dar la vuelta al tablero para que quepa 
    let cabeDerecho = (game.tablero.ancho < table.ancho && game.tablero.largo < table.largo);
    let cabeDoblado = (game.tablero.largo < table.ancho && game.tablero.ancho < table.largo);

    return (cabeDerecho || cabeDoblado);
}

function reservadaHoy(div) {
    let reservada = false; // A priori está disponible la mesa
    // Cogemos la mesa
    let mesa = div.data('mesa');

    // Miramos entre sus reservas
    $.each(mesa.reservas, function (i, reserva) {

        let fechaReserva = new Date(reserva.fechaReserva.date);
        let hoy = new Date($('#datePicker').val());

        // Si está reservada HOY (fecha seleccionada)
        if (fechaReserva.toDateString() == hoy.toDateString()) {
            reservada = true;
            return false; // rompemos el bucle
        }
    })
    return reservada;
}

function mesaLibre(mesa) {
    // Si ya tiene un juego (para no permitir que arrastre varios juegos)
    let ocupada = !(mesasLibres()); // ninguna mesa puede estar ocupada
    let reservada = (reservadaHoy(mesa));
    let disponible = (((mesa.data('reservada') == undefined) || !(mesa.data('reservada'))) && !(mesa.css("background-color") == 'rgb(255, 0, 0)'));

    return (!ocupada && !reservada && disponible);
}

function muestraReserva() {
    return function () {
        /* CREAMOS LA SALA */
        new Sala($('#sala'));
        // Volvemos a buscar datepickers
        ConvierteDatePicker();

        // El numero de jugadores cambiará los juegos disponibles
        $('#numJugadores')
            .change(function (ev) {
                ev.preventDefault();

                let jugadores = $(this).val();
                vaciaStock();
                rellenaStockJuegos($('#almacen'), jugadores);
            });

        // Programamos el botón que retira el tablero de la mesa
        $('#cancela_juego').click(function (ev) {
            ev.preventDefault();

            // Eliminamos el juego que haya seleccionado de la mesa (moviéndolo al almacen)
            let jogo = $('.mesa').children('.juego');

            $('#almacen').append(jogo);
        });
    }
}

function mesasLibres() {
    let libre = true;
    let mesas = $('#sala .mesa');

    $.each(mesas, function (i, v) {
        let mesa = $(v);

        // Cuando no tenga hijos es que la mesa está vacía (está libre)
        libre = !(mesa.children().length > 0);

        return libre ? true : false; // Rompemos el bucle       
    });

    return libre;
}

/**
 * Función que recorre las mesas de la sala en busca de mesas que no cumplen con el número de sillas mínimo
 * (no caben los jugadores) y bloquea la mesa para que no se pueda reservar
 * @param {*} numero 
 */
function validaNumSillas(numero) {

    let mesas = $('#sala .mesa');

    $.each(mesas, function (i, v) {
        
        let mesa = $(v).data('mesa');
        
        // Si ya está reservada no hay que comprobar su número de sillas
        if (($(v).data('reservada') == undefined || !$(v).data('reservada'))) {     
            // Son más personas que sillas?   (Número de sillas igual o mayor que el número de personas)
            if (numero > mesa.sillas) {
                // Si no caben los jugadores en la mesa
                $(v).css({
                    backgroundColor: 'red',
                })
                //No es válida para colocar el juego
                
                if ($(v).attr('title') == undefined) {
                    // Si ya tiene el tooltip de 'RESERVADA' no le vamos a poner 'nº de jugadores'
                    $(v).attr('title','No caben los jugadores')// ToolTip que nos dice por qué no podemos colocar tablero
                }
            }
            else
            {
                // Sobreescribimos por si cambiara el numero de sillas varias veces en el formulario
                $(v).css({
                    backgroundColor: 'green',
                })
                .attr('title','')
            }
        }

    });

}