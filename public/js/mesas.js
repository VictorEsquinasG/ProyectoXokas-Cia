/**
 * LIBRERÍA QUE PERMITE ARRASTRAR LAS MESAS ALMACENADAS EN BASE DE DATOS
 * MEDIANTE AJAX
 * UTILIZA JQUERY & UI
 */
$(function () {

    /* LA CLASE MESA */

    class Mesa {

        constructor(iden, anch, larg, sillas, x, y) {
            this.id = iden;
            this.ancho = anch;
            this.largo = larg;
            this.sillas = sillas;
            this.pos_x = x;
            this.pos_y = y;
        }


    }

    class Sala {

        constructor(div) {
            this.mesas = [];
            this.div = div;
            this.div.data("sala", this);
            // DIMENSIONES DEL CONTENEDOR
            this.dify = div.offset().top;
            this.difx = div.offset().left;
        }

        addMesa = function (mesa) {
            this.mesas.push(mesa);
            this.div.append(mesa);
            // console.log("Ya existe esta mesa");
        }

        removeMesa = function (mesa) {
            var id = mesa.id;
            // Lo borramos del array mesas
            this.mesas.splice(id, id);
        }
    }

    /* PETICIÓN AJAX */
    $.ajax({
        url: "/api/mesa",
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            mesas = data.mesas;
            if (mesas != null) {
                // Hay mesas
                $(mesas).each(function (ev, mesa) {
                    var Objeto_Mesa = new Mesa(mesa.id, mesa.ancho, mesa.largo, mesa.sillas, mesa.posicion_x, mesa.posicion_y);
                    coloca(Objeto_Mesa);
                });
            }
        }
    });

    /* CREAMOS LA SALA */
    new Sala($('#sala'));


    /* EL ALMACÉN */
    // var stock =
    $("#almacen").droppable({
        drop: function (ev, ui) {
            let mesa = ui.draggable;
            /* LO SACAMOS DEL ARRAY DE MESAS DE LA SALA */
            $('#sala').data('sala').removeMesa(mesa)
            /* LE DAMOS EL MISMO ESTILO A TODAS LAS MESAS E INDICAMOS SU TAMAÑO */
            mesa.css({
                position: "relative",
                top: "0",
                left: "0",
            });
            let obj = mesa.data('mesa');
            /* CAMBIAMOS LOS ATRIBUTOS DEL OBJETO */
            obj.pos_x = 0;
            obj.pos_y = 0;
            /* LO AÑADIMOS AL ALMACÉN */
            $(this).append(mesa);
            // Actualizamos su posición en la BD
            actualizaMesa(mesa);
        }
    }).css({ 'overflow': 'scroll' });

    /* LA SALA PRINCIPAL */
    // var sala = 
    $("#sala").droppable({
        // Cuando se suelte en él
        drop: function (ev, ui) {

            // Cogemos la mesa
            var mesa = ui.draggable;
            // Coordenadas donde hemos soltado el objeto
            mesa.left = parseInt(ui.offset.left);
            mesa.top = parseInt(ui.offset.top);

            if (!posicionValida(mesa)) {
                console.log("CHOQUE");
            } else {
                var sala = $('#sala').data('sala');
                // Si es la sala, lo metemos al objeto y la movemos
                sala
                    .addMesa(mesa.eq(0));

                // Cambiamos su estilo para poder visualizarla
                mesa.css({
                    "position": "absolute",
                    top: mesa.top + "px",
                    left: mesa.left + "px",
                });

                // Actualizamos su posición guardada en el objeto
                var obj = mesa.data('mesa');
                obj.pos_x = (mesa.left - sala.difx);
                obj.pos_y = (mesa.top - sala.dify);

                // Actualizamos su posición en la BD
                actualizaMesa(mesa);

                // mesa.pos_x = mesa.left-;
                // mesa.pos_y = top;
            }

        }


    });


    function posicionValida(mesa) {
        var solapada = false;  // Presuponemos que no se solapa
        var mesas = $('#sala .mesa[id^=mesa_]');
        // var mesas = $('#sala').data('sala').mesas;

        /* COMPROBAMOS QUE NO CHOCA CON CADA UNA DE LAS MESAS DE LA SALA */
        $(mesas).each(function () {
            actual = $(this);

            let idMesa_actual = actual.data('mesa').id;
            let idMesa_movida = mesa.eq(0).data('mesa').id;
            // No permitimos que se compare consigo misma
            if (idMesa_actual != idMesa_movida) {
                // Coordenadas de otra mesa    
                actual.left = parseInt(actual.offset().left);
                actual.top = parseInt(actual.offset().top);

                solapada = solapa(mesa, actual);
                if (solapada) {
                    // choca
                    return solapada; //Rompemos el bucle
                }
            }
        });
        /* SI NO HA CHOCADO Y NO SOBRESALE DE LA SALA */
        return (!solapada && !saleDeSala(mesa));
    }

    function saleDeSala(mesa) {
        var sala = $('#sala').data();
        let top = sala.sala.dify;
        let left = sala.sala.difx;
        let right = left + sala.sala.div.width();
        let bottom = top + sala.sala.div.height();

        var salidaVertical =
            ((top > mesa.top) || (bottom < mesa.bottom));
        var salidaHorizontal =
            ((mesa.left < left) || (mesa.right > right));

        return ((salidaVertical) || (salidaHorizontal));
    }

    function solapa(mesa1, mesa2) { //TODO revisar

        mesa1.bottom = mesa1.top + mesa1.height();
        mesa1.right = mesa1.left + mesa1.width();

        mesa2.bottom = mesa2.top + mesa2.height();
        mesa2.right = mesa2.left + mesa2.width();

        var contiene =
            (mesa1.left < mesa2.left && mesa1.right > mesa2.right ||
                mesa1.top < mesa2.top && mesa1.bottom > mesa2.bottom);

        var choqueHorizontal =
            (mesa1.right < mesa2.right && mesa1.right > mesa2.left ||
                mesa1.left > mesa2.left && mesa1.left < mesa2.right);

        var choqueVertical = // VERTICALES
            (mesa1.top < mesa2.bottom && mesa1.top > mesa2.top ||
                mesa1.bottom < mesa2.bottom && mesa1.bottom > mesa2.top);

        return ((choqueHorizontal && choqueVertical) || contiene);
    }

    /* CUANDO SE MANDA EL FORMULARIO -> SE CREA LA MESA */
    $('form').submit(function (e) {
        e.preventDefault();
        // Capturamos los datos del formulario
        var ancho = this.ancho.value;
        var largo = this.largo.value;
        var sillas = this.sillas.value;
        var mesa = new Mesa(null, ancho, largo, sillas, 0, 0);
        creaMesa(mesa);
    });

    function creaMesa(mesa) {
        // Guardamos la mesa creada en formato JSON
        var nueva_mesa = {
            "mesa": {
                "id": mesa.id,
                "ancho": mesa.ancho,
                "largo": mesa.largo,
                "sillas": mesa.sillas,
                "posicion_x": mesa.pos_x,
                "posicion_y": mesa.pos_y,
            }
        };
        console.log(nueva_mesa);
        // Petición al servidor
        $.ajax({
            type: "POST",
            url: "/api/mesa",
            data: JSON.stringify(nueva_mesa),
            dataType: "JSON",
            success: function (respuesta) {
                console.log(respuesta);
            }
        });

    }

    function actualizaMesa(Objmesa) {
        let mesa = Objmesa.data('mesa');

        mesa_movida = {
            "mesa": {
                "id": mesa.id,
                "ancho": mesa.ancho,
                "largo": mesa.largo,
                "sillas": mesa.sillas,
                "posicion_x": mesa.pos_x,
                "posicion_y": mesa.pos_y,
            }
        }
        $.ajax({
            type: "PUT",
            url: "/api/mesa",
            data: JSON.stringify(mesa_movida),
            dataType: "JSON",
            success: function (respuesta) {
                console.log(respuesta);
            }
        });
    }
    function borraMesa(Objmesa) {
        let mesa = Objmesa.data('mesa');
        console.log(mesa);

        mesa_removida = {
            "mesa": {
                "id": mesa.id,
                "ancho": mesa.ancho,
                "largo": mesa.largo,
                "sillas": mesa.sillas,
                "posicion_x": mesa.pos_x,
                "posicion_y": mesa.pos_y,
            }
        }
        $.ajax({
            type: "DELETE",
            url: "/api/mesa",
            data: JSON.stringify(mesa_removida),
            dataType: "JSON",
            success: function (respuesta) {
                console.log(respuesta);
                return respuesta.success;
            }
        });
    }

    function actualizaDisposicion() {
        // Guardamos la distribución actual
        var array_mesas = $("#sala").data('sala').mesas;
        //TODO
        $.ajax({
            url: "/api/distribucion",
            type: "GET",
            // data: array_mesas,
            // dataType: "json",
            success: function (respuesta) {
                console.log(respuesta);
            }
        });

    }
    //TODO actualizar realmente la posición
    actualizaDisposicion();

    function coloca(mesa) {
        var sala = $('#sala').data('sala');
        let caja = creaDiv(mesa);
        var top = parseInt(mesa.pos_y);
        var left = parseInt(mesa.pos_x);
        if (top != 0 && left != 0) {
            // Tiene posición => Se coloca de manera absoluta en la sala
            caja.css({
                position: 'absolute',
                top: (top + sala.dify) + "px",
                left: (left + sala.difx) + "px"
            });
            sala.addMesa(caja);
        } else {
            // No está colocado => Al almacén
            caja
                .appendTo($('#almacen'));
        }
    }

    function creaDiv(mesa) {

        // Icono de papelera
        var img = $('<img>').attr({
            src: './images/papelera-de-reciclaje.png',
            alt: 'Borrar',
            title: 'Eliminar'
        }).css({
            width: '90%',
            height: '100%',
        });
        // SPAN de borrar mesa
        var span = $('<span/>').append(img).attr('class', 'borrar')
            .css({
                float: 'right',
                width: '35%',
                marginTop: '-0.75rem',
                cursor: 'pointer',
                display: 'none'
            }).click(function () {
                // Mandamos la mesa a borrar
                let borrada = borraMesa($(this).parent());
                if (borrada) {
                    // La eliminamos de manera visual
                    $(this).parent().remove();
                }
            });

        return $('<div>')
            /* SON CLASE MESA */
            .attr('class', 'mesa')
            /* LE PONEMOS EL ID */
            .attr('id', 'mesa_' + mesa.id)
            /* GUARDAMOS EL OBJETO MESA EN DATA */
            .data('mesa', mesa)
            /* Adaptamos el tamaño (1px = 1cm) */
            .css({
                width: mesa.ancho,
                height: mesa.largo,
            }).append(span)
            .hover(function () {
                // over
                let clon = $(this);
                // Hacemos visible el botón de borrar
                clon.children().eq(0).css({ display: 'block' });


            }, function () {
                // out
                $(this).children().eq(0).css({ display: 'none' });
                $(this).css({ backgroundColor: 'transparent' })
            })
            /* LAS MESAS SE PUEDEN ARRASTRAR */
            .draggable({
                revert: true,
                revertDuration: 0,
                helper: 'clone',
                accept: '#almacen, #sala',
                cursor: "move",
                start: function (ev, ui) {
                    // Hacemos parcialmente transparente la mesa que estamos moviendo
                    ui.helper.prevObject.css({ 'opacity': '50%' });
                    $(this).children().eq(0).css({ display: 'none' });
                },
                drag: function (ev, ui) {
                    let clon = ui.helper;


                    if (!posicionValida(clon.prevObject)) { //TODO arreglar
                        // Pintamos bordes de rojo
                        clon.css({
                            'background-color': 'red',
                        });
                    }else {
                        clon.css({
                            'border': '0px',
                            'background-color': 'transparent',
                        });
                    }



                },
                stop: function (ev, ui) {
                    // Devolvemos su opacidad a la mesa colocada
                    $(ui.helper.prevObject[0]).css({ 'opacity': '100%' })
                }
            });

    }

});