/**
 * JAVASCRIPT creado por mi
 * Utiliza JQuery, JQuery-ui
 * @author Víctor J. Esquinas
 */


function datePicker(fecha, diasFestivos) {
    // Entrada mínima mañana y máxima 3 meses
    fecha.datepicker({
        dateFormat: "yy-mm-dd",
        firstDay: 1,    // Empieza en lunes
        beforeShowDay: $.datepicker.noWeekends,
        defaultDate: "+1D",
        minDate: 1, // A partir de mañana
        maxDate: "+3M +1D",

        onSelect: function (texto, obj) {
            // Las mesas de la determinada fecha aparecen    
            let fecha = [obj.currentYear, obj.currentMonth + 1, obj.currentDay];

            // Consultamos las distribuciones y si no hay distribuciones, cargamos la base.
            let mesas = getMesasHoy(fecha);
            // Vaciamos la mesa
            vaciaSala();
            // Las pintamos
            pintaMesas(mesas);
        }
    });
}

// function datePickerDistribucion(fecha, diasFestivos) {
//     // Entrada mínima mañana y máxima 3 meses
//     fecha.datepicker({
//         dateFormat: "dd/mm/yy",
//         firstDay: 1,    // Empieza en lunes
//         beforeShowDay: $.datepicker.noWeekends,
//         defaultDate: "+1D",
//         minDate: 1, // A partir de mañana
//         maxDate: "+3M +1D",
//         onSelect: function (texto, obj) {
//             // console.log(texto);
//             // $("calendario-2").datapicker("destroy").datepicker({
//             //     minDate: new Date(obj.currentYear, obj.currentMonth, obj.currentDay + 1), // Seteamos el mínimo de la salida
//             //     maxDate: new Date(obj.currentYear, obj.currentMonth, obj.currentDay + 23),
//             //     beforeShowDay: function (fecha) {
//             //         var dia = fecha.getDate();
//             //         var mes = fecha.getMonth() + 1;
//             //         var anio = fecha.getFullYear(0);
//             //         var cadenaFecha =
//             //             ((dia < 10) ? "0" + dia : dia) + '/' +
//             //             ((mes < 10) ? "0" + mes : mes) + '/' + anio;
//             //         var mostrar = [true, "", ""];
//             //         if (fecha.getDay() % 6 == 0 || diasFestivos.indexOf(cadenaFecha) > -1) // Sin findes ni festivos
//             //         {
//             //             mostrar = [false, "", "CERRADO"];
//             //         }
//             //         return mostrar;
//             //     },
//             //     dateFormat: "dd-mm-yy",
//             //     firstDay: 1
//             // }).datapicker("refresh")
//         }
//     });
// }


$(function () {
    // PILLAMOS LOS DATEPICKERS
    ConvierteDatePicker();

})

function ConvierteDatePicker() {
    var fechaReserva = $('#datePicker');
    // var fechaDistribucion = $('#datePickerDisposicion');

    // API días festivos
    let diasFestivos = getDiasFestivos();

    diasFestivos = ["27/02/2023", "28/02/2023", "01/03/2023"];

    // EN ESPAÑOL
    setDateFormatES();
    // Hacemos que sea datePicker
    datePicker(fechaReserva, diasFestivos);
    // datePickerDistribucion(fechaDistribucion, diasFestivos);
}

function setDateFormatES() {
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd-mm-yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
}

/**
 * Método que nos ayuda a pintar una mesa para las reservas
 * @param {*} mesas 
 * @author Víctor Esquinas
 */
function pintaMesas(mesas) {

    $.each(mesas, function (i, mesa) {

        // La convertimos en un objeto mesa
        if (mesa.pos_x !== undefined && mesa.pos_x !== null) {
            // TIENE POSICION DE DISTRIBUCION
            var objActual = new Mesa(mesa.id, mesa.ancho, mesa.largo, mesa.sillas, mesa.pos_x, mesa.pos_y, mesa.distribuciones, mesa.reservas);
        } else {
            // ES UNA MESA DE LA DISTRIBUCION BASE
            var objActual = new Mesa(mesa.id, mesa.ancho, mesa.largo, mesa.sillas, mesa.posicion_x, mesa.posicion_y, mesa.distribuciones, mesa.reservas);
        }
        // Cogemos la sala
        let sala = $('#sala').data('sala');

        // Si está colocada en la sala la colocamos y si no la ignoramos (no hay almacén)
        if (objActual.pos_x > 0 && objActual.pos_y > 0) {
            // Cogemos la mesa
            let caja =
                $('<div/>')
                    .attr('id', 'mesa_' + objActual.id)
                    .attr('class', 'mesa')
                    .data('mesa', objActual)
                    .css({
                        position: "relative",
                        width: objActual.ancho + 'px',
                        height: objActual.largo + 'px',
                        // Lo ubicamos respecto a la sala
                        top: (objActual.pos_y) + "px", //+ 380
                        left: (objActual.pos_x) + "px", //+ 280

                        background: "green" // Por defecto está disponible
                    })
                        /* LAS MESAS SON DROPABLES PARA DEJAR LOS TABLEROS */
                    .droppable({
                        // Cuando se suelte en él
                        drop: function (ev, ui) {
                            let juego = ui.draggable;
                            let mesa = $(this);
                
                            if (!posicionValidaTablero(juego, mesa)) {
                                console.log("No puedes jugar en esa mesa");
                            } else {
                                let objMesa = mesa.data('mesa');
                                let objJuego = juego.data('juego');
                                // Le damos el estilo
                                juego.css({
                                    'position':'relative',
                                    /* Lo ponemos en medio de la mesa */
                                    'top': ((objMesa.largo)/2),
                                    'left':((objMesa.ancho)/2),
                                    /* Le dejamos con su tamaño real */
                                    'width': objJuego.tablero.ancho + 'px',
                                    'height': objJuego.tablero.largo + 'px'
                                });
                                
                                // Lo añadimos a la mesa
                                juego.appendTo(mesa);
                                // Lo marcamos en el select oculto
                                $('#formu_reserva').data("Juego",objJuego.id);
                                $('#formu_reserva').data("Mesa",objMesa.id);

                            }
                        }
                
                    })
                ;

            // Estilo de la mesa si está reservada
            marcaReservada(caja,$('#datePicker').val(),$('#selecTramos').val());

            // La pintamos 
            sala.addMesa(caja);
        }
    });
}
/**
 * 
 *  
 */
function vaciaSala() {
    // Eliminamos todas las mesas de la página
    let mesasSala = $('#sala .mesa');
    let sala = $('#sala').data('sala');
    $.each(mesasSala, function (i, div) {
        // Cogemos la mesa
        let mesa = $(div).data('mesa');

        // Borramos las mesas
        $('#sala').children('.mesa[id=mesa_' + mesa.id + ']').remove();
        
        // Borramos la mesa de la sala
        sala.removeMesa(mesa);
    });
}

function marcaReservada(div,fecha,tramo) {
    // Cogemos la mesa
    let mesa = div.data('mesa');
    // Miramos entre sus reservas
    $.each(mesa.reservas, function (i, reserva) {

        let fechaReserva = new Date(reserva.fechaReserva.date);
        let tramoReserva = reserva.tramo.id;
        let fechaElegida = new Date(fecha);

        let cancelada = (reserva.fecha_cancelacion !== null);
        let mismaFecha = (fechaReserva.toDateString() == fechaElegida.toDateString());
        let mismaHora = (tramoReserva == tramo);

        // Si está reservada HOY AHORA
        if (mismaHora && mismaFecha && !cancelada) {
            div.css({
                // Está reservada y se ve de manera visual
                background: "red"
            }).data('reservada',true)
        }
    })
}
