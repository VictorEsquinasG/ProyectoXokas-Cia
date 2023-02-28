$(function () {

    let data = getReservas().responseJSON.reservas;
    debugger
    $('#dataTable_reservas').dataTable(    
        {
        //para cambiar el lenguaje a español
        // "language": {
        //     "Lengthfenu": "Mostrar MENU registros",
        //     "zeroReords": "No se encontraron resultados",
        //     "info": "Mostrando registros del START al END de un total de -TOTAL registros",
        //     "infoEmpty": "Mostrando registros del 0 al 0 de un total de registros",
        //     "infoFfltered": "(f1ltrado de un total de MAX registros) ",
        //     "sSearch": "Buscar:",
        //     "oPaginate": {
        //         "sFirst": "Primero",
        //         "sLast": "Último",
        //         "sNext": "Siguiente",
        //         "sPrevious": "Anterior"
        //     },
        //     "sProcessing": "Procesando..",
        // },
        "data" : data
    });
});