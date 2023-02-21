/**
 * Librería que hace uso de JQUERY UI
 * 
 * @author Víctor Esquinas
 */


    let dialog = $('<div>');

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
        <input type="submit" id="btnCrear" value="CREAR">
	</form>`

    var Jplantilla = $(plantilla);

    function ModalMesa() {
        dialog.dialog({
            modal: true,
            width: '700px',
            title: 'Creando una nueva mesa'

        })
        .append(Jplantilla)
        .submit(function (e) { 
            e.preventDefault();
            let mesa = {};
            // El valor de los campos
            let ancho = $(this).find('#ancho').val();
            let largo = $(this).find('#largo').val();
            let sillas = $(this).find('#sillas').val();
            mesa.mesa = new Mesa(null, ancho,largo,sillas,-1,-1,null,null); 
            $.ajax({
                type: "POST",
                url: "api/mesa/",
                data: mesa,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                }
            });
        });
        ;
    }

   

$(function () {

    $('#creaMesa').click(function (ev) {
        ev.preventDefault();

        ModalMesa();
    })

})