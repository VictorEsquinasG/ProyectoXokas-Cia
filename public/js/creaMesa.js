/**
 * Librería que hace uso de JQUERY UI
 * 
 * @author Víctor Esquinas
 */


let dialog = $('<div>').attr('id','modal');

let plantilla =
    `<form action="" method="POST">
        <div class="row mt-2">
            <label class="col-12 col-md-6" for="ancho">Ancho:</label>
            <input class="col-12 col-md-6" type="number" name="ancho" id="ancho">
        </div>
        <div class="row mt-2">
            <label class="col-12 col-md-6" for="largo">Largo:</label>
            <input class="col-12 col-md-6" type="number" name="largo" id="largo">
        </div>
        <div class="row mt-2">
            <label class="col-12 col-md-6" for="sillas">Sillas:</label>
            <input class="col-12 col-md-6" type="number" name="sillas" id="sillas">
        </div>
        <input type="submit" class="btn btn-primary" id="btnCrear" value="CREAR">
	</form>`

var Jplantilla = $(plantilla);

function ModalMesa() {
    dialog.dialog({
        modal: true,
        width: '700px',
        title: 'Creando una nueva mesa'

    })
        .append(Jplantilla)
        /* CUANDO SE MANDA EL FORMULARIO -> SE CREA LA MESA */
        .submit(function (e) {
            e.preventDefault();
            let mesa = {};
            // El valor de los campos
            let ancho = $(this).find('#ancho').val();
            let largo = $(this).find('#largo').val();
            let sillas = $(this).find('#sillas').val();
            let mesita = new Mesa(null, ancho, largo, sillas, -1, -1, null, null);
            // Guardamos la mesa creada en formato JSON
            mesa.mesa = {
                "mesa": {
                    "id": mesita.id,
                    "ancho": mesita.ancho,
                    "largo": mesita.largo,
                    "sillas": mesita.sillas,
                    "posicion_x": mesita.pos_x,
                    "posicion_y": mesita.pos_y,
                }
            };
            $.ajax({
                type: "POST",
                url: "api/mesa/",
                data: JSON.stringify(mesa),
                dataType: "json",
                success: function (response) {
                    console.log(response);
                }
            });

            // Cerramos el dialog
            $('#modal').remove();
        });
    ;
}



$(function () {

    $('#creaMesa').click(function (ev) {
        ev.preventDefault();

        ModalMesa();
    })

})