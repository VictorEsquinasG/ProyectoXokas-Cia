/**
 * LIBRERÍA QUE PERMITE ARRASTRAR LAS MESAS ALMACENADAS EN BASE DE DATOS
 * MEDIANTE AJAX
 * UTILIZA JQUERY & UI
 */
$(function () {

    /* LA CLASE MESA */

    class Mesa {

        constructor(iden, anch, larg, sillas, x, y, distribuciones, reservas) {
            this.id = iden;
            this.ancho = anch;
            this.largo = larg;
            this.sillas = sillas;
            this.pos_x = x;
            this.pos_y = y;
            this.disposiciones = distribuciones;
            this.reservas = reservas;
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

    class Distribucion {
        constructor(id, mesa, fecha, posX, posY, alias, reservada) {
            this.id = id;
            this.mesa_id = mesa;
            this.fecha = fecha;
            this.pos_x = posX;
            this.pos_y = posY;
            this.alias = alias;
            this.reservada = reservada;
        }
    }

    /* VARIABLES 'GLOBALES' */
    var all_distribuciones = []

    /* PETICIÓN AJAX */
    disposicionEstandar();
    getDisposiciones();

    /* CREAMOS LA SALA */
    new Sala($('#sala'));
    console.log(all_distribuciones);


    /* EL ALMACÉN */
    // var stock =
    $("#almacen").droppable({
        drop: function (ev, ui) {
            //TODO Comprobamos que NO esté reservada
            if (true) {
                // La mandamos a guardar
                guardaMesa(this, ui);
            }
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
                appendSala(sala, mesa);
                // Comprobamos la disposición
                let dispActual = $('select[name="dispo"]').val();
                if (dispActual != -1) {
                    let $disp = getDisposicion(dispActual, mesa.data('mesa').id);
                    console.log($disp);
                    let $objDisp = new Distribucion($disp.id, $disp.mesa, $disp.fecha.date, $disp.pos_x, $disp.pos_y,$disp.alias, $disp.reservada);
                    // Anotamos su posición 
                    $objDisp.pos_x = (mesa.left - sala.difx);
                    $objDisp.pos_y = (mesa.top - sala.dify);
                    // La posición la actualizamos ese día
                    actualizaDisposicion($objDisp)
                } else {
                    // Posición BASE / ESTÁNDAR
                    // Actualizamos su posición en la BD
                    actualizaMesa(mesa);
                }

            }
        }

    });

    function appendSala(sala, mesa) {
        sala
            .addMesa(mesa.eq(0));
        let objmesa = mesa.data('mesa');
        // Cambiamos su estilo para poder visualizarla
        mesa.css({
            "position": "absolute",
            top: mesa.top + "px",
            left: mesa.left + "px",
            width: objmesa.ancho + "px",
            height: objmesa.largo + "px"
        })
            /* ELIMINAMOS EL TEXTO INFORMATIVO */
            .children('p').remove();
        // Actualizamos su posición guardada en el objeto
        var obj = mesa.data('mesa');
        obj.pos_x = (mesa.left - sala.difx);
        obj.pos_y = (mesa.top - sala.dify);

    }

    function getDisposicion(alias, mesa_id) {
        let distribucion;
        
        // Buscamos nuestra mesa
        $.each(all_distribuciones, function (i, v) {
            // Nuestra mesa = nuestra distribucion
            if (v.mesa == mesa_id && v.alias == alias) {
                distribucion = v;
                return false; // Rompemos el bucle
            }
        });
        // La devolvemos
        return distribucion;
    }

    function actualizaDisposicion(disp) {

        let dist = {};
        dist.distribucion = disp;
        // dist.distribucion.fecha = dist.distribucion.fecha.substr(0,10); //19
        $.ajax({
            type: "PUT",
            url: "/api/distribucion",
            data: JSON.stringify(dist),
            dataType: "json",
            success: function (response) {
                console.log("mesa " + response.id + " actualizada");
            }
        });
        // debugger;
    }


    function posicionValida(mesa) {
        var solapada = false;  // Presuponemos que no se solapa
        var mesas = $('#sala .mesa[id^=mesa_]');
        // var mesas = $('#sala').data('sala').mesas;

        /* COMPROBAMOS QUE NO CHOCA CON CADA UNA DE LAS MESAS DE LA SALA */
        $(mesas).each(function () {
            actual = $(this);

            var idMesa_actual = actual.data('mesa').id;
            var idMesa_movida = mesa.eq(0).data('mesa').id;
            // No permitimos que se compare consigo misma
            if (idMesa_actual != idMesa_movida) {
                // Coordenadas de otra mesa    
                actual.left = parseInt(actual.offset().left);
                actual.top = parseInt(actual.offset().top);

                solapada = solapa(mesa, actual);
            }

            if (solapada) {
                // choca
                return false; //Rompemos el bucle
            }
        });

        /* SI NO HA CHOCADO Y NO SOBRESALE DE LA SALA */
        return (!solapada && !saleDeSala(mesa));
    }

    function saleDeSala(mesa) {
        var sala = $('#sala').data('sala');
        console.log(sala);
        let top = sala.dify;
        let left = sala.difx;
        let right = left + sala.div.width();
        let bottom = top + sala.div.height();

        var salidaVertical =
            ((top > mesa.top) || (bottom < mesa.bottom));
        var salidaHorizontal =
            ((mesa.left < left) || (mesa.right > right));

        return ((salidaVertical) || (salidaHorizontal));
    }

    function solapa(mesa1, mesa2) {

        mesa1.bottom = mesa1.top + mesa1.height();
        mesa1.right = mesa1.left + (mesa1.width() - 2); // Para que los bordes puedan solaparse levemente

        mesa2.bottom = mesa2.top + mesa2.height();
        mesa2.right = mesa2.left + (mesa2.width() - 2);

        var contiene =
            (mesa1.left <= mesa2.left && mesa1.right >= mesa2.right ||
                mesa1.top <= mesa2.top && mesa1.bottom >= mesa2.bottom);

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
        // Capturamos los datos del     
        var ancho = this.ancho.value;
        var largo = this.largo.value;
        var sillas = this.sillas.value;
        var mesa = new Mesa(null, ancho, largo, sillas, -1, -1);
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

    function creaTextTamanio(mesa) {
        return $('<p>')
            .html(
                mesa.ancho +
                " x " +
                mesa.largo +
                " cm"
            ).css({
                width: '100%',
                height: '26px',
                'vertical-align': 'top',
                'text-align': 'center',
                backgroundColor: 'rgba(55, 56, 55,0.8)'
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

        success = $.ajax({
            type: "DELETE",
            url: "/api/mesa",
            data: JSON.stringify(mesa_removida),
            dataType: "JSON",
            success: function (respuesta) {
                // console.log(respuesta);
                return respuesta.Success;
            }
        });
        // console.log(success);
        return true;
    }

    // Capturamos el SELECT que definirá el cambio
    $('#selDisposicion').change(function (e) {
        e.preventDefault();

        // Cogemos el valor seleccionado
        var disposicion_seleccionada = $('select[name="dispo"]').val();

        if (disposicion_seleccionada !== '-1') {
            // Mandamos a actualizar
            cambiaDisposicion(disposicion_seleccionada);
        } else {
            disposicionEstandar();
        }
    });

    function cambiaDisposicion(selected) {
        // // Guardamos la distribución actual
        // var array_mesas = $("#sala").data('sala').mesas;

        // Vaciamos la sala
        vaciaSala();

        $.ajax({
            type: "GET",
            url: "/api/mesa",
            success: function (data) {
                let mesas = data.mesas;
                $.each(mesas, function (i, v) {
                    let mesaActual = v;
                    let distribuciones = v.distribuciones;

                    $.each(distribuciones, function (i, v) {
                        v = JSON.parse(v);
                        let nombre = v.alias;
                        // Hablamos de la misma distribucion (mismo nombre o misma fecha)
                        if (nombre == selected) {
                            // capturamos la mesa y la colocamos
                            let mesa = $('#almacen > .mesa[id=mesa_'+mesaActual.id+']');
                            // Guardamos sus coordenadas actuales
                            mesa.posicionXAnterior = mesa.pos_x;
                            mesa.posicionYAnterior = mesa.pos_y;
                            // Es la distribucion que queremos
                            mesa.pos_y = v.pos_y;
                            mesa.pos_x = v.pos_x;
                            // La mandamos a pintar
                            recoloca(mesa);
                        }
                    });
                });
            }
        });
    }

    function vaciaSala(borrado = false) {
        // Eliminamos todas las mesas de la página
        let mesasSala = $('#sala > .mesa');
        let sala = $('#sala').data('sala');
        $.each(mesasSala, function (i, v) {
            
            sala.removeMesa(v);
            if (borrado) {
                // Borramos las mesas
                $('#sala').children('.mesa[id=mesa_'+v.id+']').remove();
            } else {   
                // Guardamos cada mesa 
                almacena($(v));
            }
        });
    }

    function almacena(mesa) {
        /* LO SACAMOS DEL ARRAY DE MESAS DE LA SALA */
        $('#sala').data('sala').removeMesa(mesa);
        /* CREAMOS EL TEXTO CON SU TAMAÑO */
        var textTamanio = creaTextTamanio(mesa.data('mesa'));
        /* LE DAMOS EL MISMO ESTILO A TODAS LAS MESAS E INDICAMOS SU TAMAÑO */
        mesa.css({
            position: "relative",
            width: '100px',
            height: '100px',
            top: "0",
            left: "0",
        });
       
        if (!(mesa.children('p').length>0)) {
            // No tiene la etiqueta
            mesa.append(textTamanio);
        }
        let obj = mesa.data('mesa');
        /* CAMBIAMOS LOS ATRIBUTOS DEL OBJETO */
        obj.pos_x = -1;
        obj.pos_y = -1;
        /* LO AÑADIMOS AL ALMACÉN */
        $('#almacen').append(mesa);
    }


    function recoloca(mesa) {
    
        var top = parseInt(mesa.pos_y);
        var left = parseInt(mesa.pos_x);

        if (top >= 0 && left >= 0) {
            // Tiene posición => Se coloca de manera absoluta en la sala
            appendSala($('#sala').data('sala'),mesa);
        } else {
            // No está colocado => Al almacén
            almacena($(mesa));
        }
        
        console.log("Recolocada " + mesa.data('mesa').id);
    }

    function coloca(mesa) {
        var sala = $('#sala').data('sala');
        let caja = creaDiv(mesa);
        var top = parseInt(mesa.pos_y);
        var left = parseInt(mesa.pos_x);

        var textTamanio = creaTextTamanio(mesa);

        if (top >= 0 && left >= 0) {
            // Tiene posición => Se coloca de manera absoluta en la sala
            caja.css({
                position: 'absolute',
                top: (top + sala.dify) + "px",
                left: (left + sala.difx) + "px"
            });
            // Si tiene el texto informativo se lo quitamos
            if (caja.children().length > 0) {
                caja.children('p').remove();
            }
            // Lo añadimos
            sala.addMesa(caja);
        } else {
            // No está colocado => Al almacén
            caja.css({
                width: "100px",
                height: "100px"
            }).append(textTamanio)
                .appendTo($('#almacen'));
        }
        console.log("Colocada " + mesa.id);
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

                    ui.helper.css({ 'border': '3.5px dotted #3de051' });
                    // QUITAMOS LA PAPELERA
                    $(this).children().eq(0).css({ display: 'none' });
                },
                stop: function (ev, ui) {
                    // Devolvemos su opacidad a la mesa colocada
                    $(ui.helper.prevObject[0]).css({ 'opacity': '100%' })
                }
            });

    }
    /**
     * Para la distribución BASE
     * 
     * 
     */
    function disposicionEstandar() {
        // Comprovamos si tenemos mesas
        if ($('.mesa').length > 0) {
            // Si ya las tenemos solo debemos recolocarlas
            let mesas = $('.mesa');
            $.each(mesas, function (i, mesa) { 
                // Le pasamos la mesa
                if ((typeof mesa.posicionXAnterior !== 'undefined') && (mesa.posicionXAnterior !== null)) {
                    debugger
                    // Si existe esta propiedad es que está colocada en la disposición BASE original
                    mesa.pos_x = mesa.posicionXAnterior;
                    mesa.pos_y = mesa.posicionYAnterior;
                    // HAY QUE REASIGNAR SUS COORDENADAS Y MANDARLA A PINTARSE
                    recoloca($(mesa));
                }
            });
        }else {
            // Si no tenemos mesas, las pedimos
            $.ajax({
                url: "/api/mesa",
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    let mesas = data.mesas;
                    if (mesas != null) {
                        // Hay mesas, por lo tanto vaciamos la sala
                        vaciaSala(true);
                        // Y colocamos las mesas donde corresponda
                        $(mesas).each(function (ev, mesa) {
    
                            var objeto_Mesa = new Mesa(mesa.id, mesa.ancho, mesa.largo, mesa.sillas, mesa.posicion_x, mesa.posicion_y, mesa.distribuciones, mesa.reservas);
    
                            coloca(objeto_Mesa);
                        });
                    }
                }
            });
        }
    }

    /**
     * Guardamos las mesas en el almacen
     * @param {*} div El almacén donde se guardan las mesas
     * @param {*} ui La mesa 
     * @param {*} creaTextTamanio 
     * @param {*} actualizaMesa 
     */
    function guardaMesa(div, ui) {
        let mesa = ui.draggable;
        /* LO SACAMOS DEL ARRAY DE MESAS DE LA SALA */
        $('#sala').data('sala').removeMesa(mesa);
        /* CREAMOS EL TEXTO CON SU TAMAÑO */
        var textTamanio = creaTextTamanio(mesa.data('mesa'));
        /* LE DAMOS EL MISMO ESTILO A TODAS LAS MESAS E INDICAMOS SU TAMAÑO */
        mesa.css({
            position: "relative",
            width: '100px',
            height: '100px',
            top: "0",
            left: "0",
        }).append(textTamanio);
        let obj = mesa.data('mesa');
        /* CAMBIAMOS LOS ATRIBUTOS DEL OBJETO */
        obj.pos_x = -1;
        obj.pos_y = -1;
        /* LO AÑADIMOS AL ALMACÉN */
        $(div).append(mesa);
        // Actualizamos su posición en la BD
        actualizaMesa(mesa);
    }

    function getDisposiciones() {

        $.getJSON("/api/distribucion",
            function (data) {
                $.each(data.distribuciones, function (i, v) {
                    all_distribuciones.push(v);
                });
            }
        );
    }
});


