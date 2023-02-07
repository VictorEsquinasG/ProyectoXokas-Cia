/**
 * JAVASCRIPT creado por mi
 * Utiliza JQuery, JQuery-ui
 */
$(function () {
    // PILLAMOS LOS DATEPICKERS
    var desde = $('#datePicker');

    //TODO API días festivos
    diasFestivos = 
    $.ajax({
        type: "GET",
        url: "/api/festivos",
        success: function (response) {
            console.log(response);
        }
    });
    diasFestivos = ["27/02/2023", "28/02/2023", "01/03/2023"];

    // EN ESPAÑOL
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);

    // Entrada mínima mañana y máxima 3 meses
    desde.datepicker({
        dateFormat: "dd-mm-yy",
        firstDay: 1,    // Empieza en lunes
        beforeShowDay: $.datepicker.noWeekends,
        defaultDate: "+1D",
        minDate: 1, // A partir de mañana
        maxDate: "+3M +1D",
        onSelect: function (texto, obj) {
            $("calendario-2").datapicker("destroy").datepicker({
                minDate: new Date(obj.currentYear, obj.currentMonth, obj.currentDay + 1), // Seteamos el mínimo de la salida
                maxDate: new Date(obj.currentYear, obj.currentMonth, obj.currentDay + 23),
                beforeShowDay: function (fecha) {
                    var dia = fecha.getDate();
                    var mes = fecha.getMonth() + 1;
                    var anio = fecha.getFullYear(0);
                    var cadenaFecha =
                        ((dia < 10) ? "0" + dia : dia) + '/' +
                        ((mes < 10) ? "0" + mes : mes) + '/' + anio;
                    var mostrar = [true, "", ""];
                    if (fecha.getDay() % 6 == 0 || diasFestivos.indexOf(cadenaFecha) > -1) // Sin findes ni festivos
                    {
                        mostrar = [false, "", "CERRADO"];
                    }
                    return mostrar;
                },
                dateFormat: "dd-mm-yy",
                firstDay: 1
            }).datapicker("refresh")
        }
    });
   
})