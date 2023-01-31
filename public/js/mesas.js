/**
 * LIBRERÍA QUE PERMITE ARRASTRAR LAS MESAS ALMACENADAS EN BASE DE DATOS
 * MEDIANTE AJAX
 * UTILIZA JQUERY & UI
 */
$(function () {

    /* LAS MESAS */
    var mesas = [];
    mesas = $.ajax({
        method: "GET",
        url: "http://127.0.0.1:8000/api/mesa",
    }).done(function (data) {
        console.log(data.id);
    });
    
    /* CAPURAMOS EL ALMACÉN Y LA SALA */
    $("#container-mesas").css({
        "display": "grid",
        "grid-template-rows": "[fila] 100%",
        "grid-template-columns": "[a] 80% [b] 20%",
        "width": "100%",
        "min-width": "1300px",
        "height": "fit-content",
        "min-height": "800px"
    });
    /* LAS MESAS */
    // var mesa =
    $('.mesa').draggable({
        helper: 'clone',
        revert: true,
        containment: '#container-mesas',
        revertDuration: 0,
        start: function (ev, ui) {
            // Le ponemos las coordenadas a la mesa en un atributo
            $(this).attr("data-x", ui.offset.left);
            $(this).attr("data-y", ui.offset.top);
            // Ahora tenemos su coordenada inicial
        }
    }).css({
        "position": "relative", // Luego la pondremos absolute
        "width": "120px",
        "height": "150px",
        "margin": "5px",
        "border": "1px dotted red"
    });

    /* EL ALMACÉN */
    var stock = $("#almacen").css({
        "grid-row": "fila",
        "grid-column": "b",
        "border": "1.5px solid #84481d",
        "width": "100%",
        "display": "flex",
        "flexDirection": "row",
        "flexWrap": "wrap",
        // "overflow":"scroll"
    }).resizable().droppable({
        drop: function (ev, ui) {
            var mesita = ui.draggable;
            mesita.css({
                "position": "relative",
                // Le 'desbindeamos' el valor que tiene en TOP y LEFT 
                "top": 0 + 'px',
                "left": 0 + 'px',
                "width": "120px",
                "height": "150px",
                "margin": "5px",
                "border": "1px dotted red",
            });
            $(this).append(mesita);
        }
    });

    /* LA SALA PRINCIPAL */
    var sala = $("#sala").css({
        "gridRow": "fila",
        "gridColumn": "a",
        "border": "1.5px solid #332626",
        "width": "98%",
        "min-height": "700px"
    }).droppable({
        // Cuando se suelte en él
        drop: function (ev, ui) {
            let dify = $(this).offset().top;
            let difx = $(this).offset().left;
            console.log([difx,dify]);
            // Cogemos la mesa
            let mesita = ui.draggable;
            // Antes de insertarla, comprobaremos si se solapa con otra mesa
            let Mesas_Sala = $('#sala .mesa').eq(0);
            // Coordenadas donde hemos soltado el objeto
            let mi_posX = parseInt(ui.offset.left);
            let mi_posY = parseInt(ui.offset.top);
            console.log([mi_posX,mi_posY]);
            let mi_altura = mesita.height();
            let mi_anchura = mesita.width();
            // Si ya hay mesas en la sala
            if (Mesas_Sala.lenght > 0) {
                // $.each(Mesas_Sala, function (i, value) {
                // Coordenadas de otra mesa    
                var otra_posX = parseInt(Mesas_Sala.offset().left);
                var otra_posY = parseInt(Mesas_Sala.offset().top);
                console.log([otra_posX,otra_posY]);
                var otra_altura = Mesas_Sala.height();
                var otra_anchura = Mesas_Sala.width();
                // Las posiciones de las 2 mesas a comparar
                var pos1 = [mi_posX, mi_posX + mi_anchura, mi_posY, mi_posY + mi_altura];
                var pos2 = [otra_posX, otra_posX + otra_anchura, otra_posY, otra_posY + otra_altura];
                // Comprobamos las colisiones con las otras mesas
                if ( (pos1[0] > pos2[0] && pos1[0] < pos2[1] || // Que su coordenada izrda esté entre la coordenada izrda y drcha del otro
                    pos1[1] > pos2[0] && pos1[1] < pos2[1] || // Que su coordenada derecha ""
                    pos1[0] <= pos2[0] && pos1[1] >= pos2[1]) // Que coincidan o que esté 1 dentro de otra
                    
                    &&
                  
                    (pos1[2] > pos2[2] && pos1[2] < pos2[3] || // VERTICALES
                    pos1[3] > pos2[2] && pos1[3] < pos2[3] ||
                    pos1[2] <= pos2[2] && pos1[3] >= pos2[3]))
                {
                    console.log("CHOQUE");
                    alert("CHOCA");

                } else {
                    // La movemos
                    $(this).append(mesita);
                    // Cambiamos su estilo para poder visualizarla
                    mesita.css({
                        "position": "absolute",
                        "top": (mi_posY-dify) + 'px',
                        "left": (mi_posX-difx) + 'px',
                        "width": "120px",
                        "height": "150px",
                        "margin": "5px"
                    }); 
                }
                // });

            } else {
                // Si no hay mesas antes que ella, no puede chocarse con nadie
                // La movemos
                $(this).append(mesita);
                // Cambiamos su estilo para poder visualizarla
                mesita.css({
                    "position": "absolute",
                    "top": mi_posY + 'px',
                    "left": mi_posX + 'px',
                    "width": "120px",
                    "height": "150px",
                    "margin": "5px"
                }); 
            }


        }
    });;

    // La posibilidad de que la mesa se quede en la sala
    $("#sala, #almacen")

    //     class mesa {
    //         ancho;
    //         largo;
    //         num_max;
    //         posicion_x;
    //         posicion_y;

    //         /**
    //          * 
    //          * @param {*} larg El tamaño de largo 
    //          * @param {*} gordo El tamaño de ancho
    //          * @param {*} sillas El número de personas que caben sentados en la mesa
    //          * @param {*} posicion_eje_x Posición LEFT
    //          * @param {*} posicion_eje_y Posición TOP
    //          */
    //         constructor(larg,gordo,sillas,posicion_eje_x = 0,posicion_eje_y = 0) {
    //             posicion_x = posicion_eje_x;
    //             posicion_y = posicion_eje_y;
    //             largo = larg;
    //             ancho = gordo;
    //             num_max = sillas;
    //         }

    //     }

    //     // metodo solapa (Comprueba si 2 mesas se chocan)
    //     mesa.solapa = function (otraMesa) {

    //         return false;
    //     }
});