/**
 * Ventana modal 
 * que impide que toquemos otras funciones mientras esté desplegada
 * Utiliza JQuery
 */
$(function () { // Window.onload


    /* PLANTILLAS */
    var plantillaReserva =
        `<h1>Haz tu reserva en minutos</h1> <br><br><br>
        <article>
            <div>
                <label for="datePicker">Fecha de reserva:</label>
                <input type="text" id="datePicker">
            </div>

            <div>
                <label for="selecTramos">Tramo horario:</label>
                <select name="" id="selecTramos">
                    <option value="-1" disabled selected></option>
                    <option value="a">a</option>
                </select>
            </div>

            <input type="hidden" name="_target_path" value="/">

            <button type="submit" class="btn btn-primary">RESERVAR</button>
        </aritcle>
        `
    // Lo convertimos en JQuery
    var JplantillaReserva = $(plantillaReserva);

    // El dialog
    var dialog = $('<div />');

    // USAMOS 1 BOTON PARA ABRIR LA VENTANA MODAL
    $("#creaReserva").click(function (ev) {
        ev.preventDefault();
        /* CREAMOS UN MODAL  */
        dialog.dialog({
            modal: true,
            width: "700px",
            title: "Reservar mesa 📆🎲",
        }).append(JplantillaReserva);

    });
})  
