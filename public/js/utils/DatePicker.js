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
            // Las pintamos
            // pintaMesas(mesas);
        }
    });
}

function datePickerDistribucion(fecha, diasFestivos) {
    // Entrada mínima mañana y máxima 3 meses
    fecha.datepicker({
        dateFormat: "dd/mm/yy",
        firstDay: 1,    // Empieza en lunes
        beforeShowDay: $.datepicker.noWeekends,
        defaultDate: "+1D",
        minDate: 1, // A partir de mañana
        maxDate: "+3M +1D",
        onSelect: function (texto, obj) {
            console.log(texto);
            // $("calendario-2").datapicker("destroy").datepicker({
            //     minDate: new Date(obj.currentYear, obj.currentMonth, obj.currentDay + 1), // Seteamos el mínimo de la salida
            //     maxDate: new Date(obj.currentYear, obj.currentMonth, obj.currentDay + 23),
            //     beforeShowDay: function (fecha) {
            //         var dia = fecha.getDate();
            //         var mes = fecha.getMonth() + 1;
            //         var anio = fecha.getFullYear(0);
            //         var cadenaFecha =
            //             ((dia < 10) ? "0" + dia : dia) + '/' +
            //             ((mes < 10) ? "0" + mes : mes) + '/' + anio;
            //         var mostrar = [true, "", ""];
            //         if (fecha.getDay() % 6 == 0 || diasFestivos.indexOf(cadenaFecha) > -1) // Sin findes ni festivos
            //         {
            //             mostrar = [false, "", "CERRADO"];
            //         }
            //         return mostrar;
            //     },
            //     dateFormat: "dd-mm-yy",
            //     firstDay: 1
            // }).datapicker("refresh")
        }
    });
}


$(function () {
    // PILLAMOS LOS DATEPICKERS
    ConvierteDatePicker();

})

function ConvierteDatePicker() {
    var fechaReserva = $('#datePicker');
    var fechaDistribucion = $('#datePickerDisposicion');

    //TODO API días festivos
    let diasFestivos = getDiasFestivos();

    diasFestivos = ["27/02/2023", "28/02/2023", "01/03/2023"];

    // EN ESPAÑOL
    setDateFormatES();
    // Hacemos que sea datePicker
    datePicker(fechaReserva, diasFestivos);
    datePickerDistribucion(fechaDistribucion, diasFestivos);
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

    $.each(mesas, function (i, v) {
        // Cogemos la sala
        let sala = $('sala').data('sala');
        // Cogemos la mesa
        let mesa =
            $('<div/>')
                .attr('class', 'mesa')
                .data('mesa', v)
                .css({
                    position: "absolute",
                    width: v.ancho + 'px',
                    height: v.largo + 'px',
                    // Lo ubicamos respecto a la sala
                    top: (v.pos_y + sala.dify) + "px", 
                    left: (v.pos_x + sala.difx) + "px",
                    background: "red"
                })
            ;

        // La pintamos 
        sala.addMesa(mesa);
    });
}
