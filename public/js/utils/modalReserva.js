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
        .droppable({
            // Cuando se suelte en él
            drop: function (ev, ui) {

                if (!posicionValida(mesa)) {
                    console.log("No puedes jugar en esa mesa");
                } else {


                }
            }

        })
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
        rellenaSelecTramos(selecTramos);

        let stock = Jplantilla.find('#almacen');
        rellenaStockJuegos(stock);
        /* CREAMOS UN MODAL  */
        dialog.dialog({
            modal: true,
            width: "100%",
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
            .show(function () {
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



});//fin

/* PLANTILLA */
var plantilla =
    `
<div class="accordion" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Fecha de la reserva
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-12 col-md-6 l-grid-line">
                        <label>Fecha</label>
                        <input type="text" id="datePicker" autocomplete="off">
                    </div>
                    <div class="col-12 col-md-6 l-grid-line">
                        <label>Hora</label>
                        <select name="tramo" id="selecTramos"></select>
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
                        Número de jugadores:
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="number" name="num_jugadores" id="numJugadores">
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
                        <!-- <select name="juego" id="selecJuego"></select> -->
                    </div>
                    <div id="sala">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
`

function rellenaSelecTramos(selecTramos) {
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

                        background: '#fff url(../images/uploads/' + v.imagen + ') no-repeat',
                        backgroundPosition: 'center center'
                        // backgroundCover: "cover"
                    })
                    .draggable({
                        revert: true,
                        revertDuration: 0,
                        helper: 'clone',
                        accept: '#almacen, .mesa',
                        cursor: "move",
                        start: function (ev, ui) {
                            // Hacemos parcialmente transparente la mesa que estamos moviendo
                            ui.helper.prevObject.css({ 'opacity': '50%' });

                            ui.helper.css({
                                'border': '3.5px dotted #3de051',
                                'width': ui.helper.prevObject.eq(0).data('juego').tablero.ancho + 'px',
                                'height': ui.helper.prevObject.eq(0).data('juego').tablero.largo + 'px'
                            });
                            // QUITAMOS EL SPAN INFORMATIVO
                            $(this).eq(0).children().remove();
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