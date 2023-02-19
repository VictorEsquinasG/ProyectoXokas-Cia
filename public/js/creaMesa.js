/**
 * Librería que hace uso de JQUERY UI
 * 
 * @author Víctor Esquinas
 */


    let dialog = $('<div>');

    let plantilla =
    `<form action="" method="POST">
        <label for="ancho">Ancho:</label>
        <input type="number" name="ancho" id="ancho">
        <label for="largo">Largo:</label>
        <input type="number" name="largo" id="largo">
        <label for="sillas">Sillas:</label>
        <input type="number" name="sillas" id="sillas">
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