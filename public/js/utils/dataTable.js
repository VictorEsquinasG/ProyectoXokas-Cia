$(function () {

    let data = getReservas().responseJSON.reservas;
    
    data.forEach(reserva => {
        reserva.mesa.string +=" cm"; // A cada mesa le añadimos su unidad de medida (centímetros)
        reserva.fecha_reserva.date = reserva.fecha_reserva.date.toLocaleString();
    });
    $('#dataTable_reservas').DataTable({
        data:data,
        columns: [
            {data:"id"},
            {data:"fecha_reserva.date"},
            {data:"juego.nombre"},
            {data:"mesa.string"},
            {data:"usuario.nombre"}
        ]
    });
    // $('#dataTable_reservas').dataTable(    
        // {
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
        // "data" : data
    // });
});