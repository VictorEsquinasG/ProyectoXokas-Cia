/**
 * Ventana modal 
 * que impide que toquemos otras funciones mientras esté desplegada
 * Utiliza JQuery
 */
$(function () { // Window.onload

    // USAMOS 1 BOTON PARA ABRIR LA VENTANA MODAL
    $("#abreModal").click(function() {
        
        var contenedor = $("<div />").css({
            'z-index':'997',
            'position':'absolute',
            'top':'0',
            'left':'0',
            'padding':'0',
            'margin':'0',
            'opacity':'50%',
            'background-color':'#757575',
            'width':'100vw',
            'height':'100vh'
        }).attr('id','modal');
        
        var ventana = $("<div />").css({
            'z-index':'998',
            'position':'relative',
            'top':'25%',
            'left':'35%',
            'padding':'0',
            'margin':'0',
            'background-color':'#ffffff',
            'border-radius':'5px',
            'width':'30%',
            'height':'400px'
        });

        ventana.appendTo(contenedor);
        contenedor.appendTo($("body"));

        /* var cerrar = */ 
        $('<div />').css({
            'z-index':'999',
            'cursor':'pointer',
            'position':'absolute',
            'top':'1',
            'right':'0',
            'margin':'3.5px',
            'padding':'1px 3.5px',
            'border-radius':'10px',
            'font-size':'bold',
            'font-family':'Montserrat,monospace',
            'color':'white',
            'background-color':'#961212'
        }).text('X').click(function() {
            $('#modal').remove();
        }).appendTo(ventana);

    }) /* BORRAR (FIN DEL BOTON ACCIONADOR) */

});