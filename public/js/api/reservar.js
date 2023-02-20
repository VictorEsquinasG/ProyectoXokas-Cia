/**
 * @author Víctor J. Esquinas
 */

function setReserva(reserva) {
    // El JSON que vamos a enviar con los datos de la reserva
    let laReserva = {}

    laReserva.reserva = {
        "id": null,
        "fecha": reserva.fecha,
        "asiste": reserva.asiste,
        "fechaCancelacion": reserva.fechaCancelacion,
        "usuario": reserva.usuario, // El usuario que sea
        "mesa": reserva.mesa,
        "juego": reserva.juego,
        "tramo": reserva.tramo
    };

    return $.ajax({
        type: "POST",
        url: "api/reserva",
        data: JSON.stringify(laReserva),
        dataType: "json",
        async: false,
        success: function (response) {
            // Devolvemos si ha habido éxito o no
            return response.success;
        }
    });
}