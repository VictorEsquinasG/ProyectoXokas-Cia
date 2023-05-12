/**
 * LIBRERÍA QUE PERMITE ARRASTRAR LAS MESAS ALMACENADAS EN BASE DE DATOS
 * MEDIANTE AJAX
 * @use UTILIZA JQUERY & UI
 * @author Víctor Esquinas
 */

$(function () {

    /* VARIABLES 'GLOBALES' */
    var all_distribuciones = []

    /* PETICIÓN AJAX */
    disposicionEstandar();
    getDisposiciones(all_distribuciones);

    /* CREAMOS LA SALA */
    new Sala($('#sala'));


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
    }).css({
        overflow: 'scroll',
        backgroundColor: "#cccccc",
        backgroundImage: "url('../images/fondoAlmacen.jpg')",
        backgroundRepeat: "repeat",
        backgroundCover: "fill"
    });

    /* LA SALA PRINCIPAL */
    // var sala = 
    $("#sala")
        .attr("class", "ml-3")
        .droppable({
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

                    if (dispActual !== '-1') {
                        // No es la disposición estándard

                        if (dispActual == 'crear') {
                            // Está creando una distribución

                            dialog.dialog({
                                modal: true,
                                width: "700px",
                                title: "Crear distribucion 📅🎲",
                            })
                                .append(JPlantillaDisp)
                                .show(
                                    // 
                                    creaDatePicker()
                                    )
                                .submit(function (ev) {
                                    ev.preventDefault();

                                    let form = $(this);
                                    let nombre = form.find('input[name=nombre]').val();
                                    let fecha = form.find('#datePicker').val();

                                    // Creamos la disposición
                                    $('<option>').val(nombre).html(nombre).data('fecha', fecha)
                                        .appendTo('#selDisposicion');
                                    // Cambiamos el select a la disposición creada
                                    $('#selDisposicion').val(nombre);
                                   
                                    cambiaDisposicion($('#dialog').data('disp'));
                                    // Cerramos el modal
                                    dialog.remove();
                                })
                                ;
                        } else {
                            // Cogemos la disposición
                            var $disp = getDisposicion(dispActual, mesa.data('mesa').id); 
                            // Si no existe creamos la nueva disposición
                            if ($disp == null) {
                                // La fecha será la de la distribución, excepto si es nueva, entonces será la que dijo el usuario
                                let fecha = getFechaDisposicionByName(all_distribuciones, dispActual)?getFechaDisposicionByName(all_distribuciones, dispActual):$('select[name="dispo"]').find(":selected").data('fecha').split('/')[2]+'-'+$('select[name="dispo"]').find(":selected").data('fecha').split('/')[1]+'-'+$('select[name="dispo"]').find(":selected").data('fecha').split('/')[0];
                                // Creamos un objeto distribucion y lo persistimos
                                let dispo = new Distribucion(null, mesa.data('mesa').id, fecha, -1, -1, dispActual, false);
                                $disp = creaDisposicion(dispo);
                            }
    
                            let $objDisp = {};
                            // Anotamos su nueva posición 
                            $objDisp = new Distribucion($disp.id, $disp.mesa, $disp.fecha.date, mesa.left, mesa.top, $disp.alias, $disp.reservada);
                            // La posición la actualizamos ese día
                            actualizaDisposicion($objDisp);
                        }
                    } else { /* Posición BASE / ESTÁNDAR */
                        // Su nueva posición
                        let obj = mesa.data('mesa');
                        obj.pos_x = parseInt(mesa.left - sala.difx);
                        obj.pos_y = parseInt(mesa.top - sala.dify);
                        // Actualizamos su posición en la BD
                        actualizaMesa(mesa);
                    }
                }
            }

        })
        .css({
            backgroundColor: "#cccccc",
            backgroundImage: "url('../images/fondoSala.jpg')",
            backgroundRepeat: "repeat",
            backgroundCover: "fill"
        })
        ;



    

    function getDisposicion(alias, mesa_id) {
        var distribucion = null;
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

    function creaDisposicion(disp) {
        var nueva = null;
        let dist = {};
        dist.distribucion = disp;
        $.ajax({
            type: "POST",
            url: "/api/distribucion",
            data: JSON.stringify(dist),
            dataType: "json",
            async: false,
            success: function (response) {                
                console.log("Posición de la mesa " + response.id + " establecida");
                nueva = response.distribucion;
            }
        });

        return nueva;
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
                console.log(response);
                console.log("Posición de la mesa " + response.id + " actualizada");
            }
        });
        // debugger;
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
            async: false,
            success: function (respuesta) {
                // console.log(respuesta);
                return respuesta.Success;
            }
        });
        // console.log(success);
        return success;
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
        // Vaciamos la sala
        vaciaSala();

        $.ajax({
            type: "GET",
            url: "/api/mesa",
            success: function (data) {
                let mesas = data.mesas;

                $.each(mesas, function (i, mesita) {
                    // A cada mesa le preguntamos por sus distribuciones
                    let mesaActual = $('#mesa_' + mesita.id);
                    let objActual = mesaActual.data('mesa');
                    let distribuciones = mesita.distribuciones;

                    $.each(distribuciones, function (i, v) {
                        let dist = v;
                        let nombre = dist.nombre;
                        // Hablamos de la misma distribucion (mismo nombre o misma fecha)
                        if (nombre == selected) {
                            // Guardamos sus coordenadas actuales
                            mesaActual.posicionYAnterior = mesita.pos_y;
                            mesaActual.posicionXAnterior = mesita.pos_x;
                            // Es la distribucion que queremos
                            mesaActual.top = dist.pos_y;
                            mesaActual.left = dist.pos_x;

                            objActual.pos_y = dist.pos_y;
                            objActual.pos_x = dist.pos_x;
                            // La mandamos a pintar
                            recoloca(mesaActual);
                        }
                    });
                });
            }
        });
    }

    /**
     * 
     * @param {boolean} borrado 
     */
    function vaciaSala(borrado = false) {
        // Eliminamos todas las mesas de la página
        let mesasSala = $('#sala > .mesa');
        let sala = $('#sala').data('sala');
        $.each(mesasSala, function (i, v) {
            let mesa = $(v);
            // Borramos la mesa de la sala
            sala.removeMesa(v);

            if (borrado) {
                // Borramos las mesas
                $('#sala').children('.mesa[id=mesa_' + v.id + ']').remove();
            } else {
                // Guardamos cada mesa en el almacén
                almacena(mesa);
            }
        });
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
    }

    function creaDiv(mesa) {

        // Icono de papelera
        var img = $('<img>').attr({
            src: '../images/papelera-de-reciclaje.png',
            alt: 'Borrar',
            title: 'Eliminar'
        }).css({
            width: '80%',
            height: '90%',
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
            $.each(mesas, function (i, v) {
                let mesa = $(v);

                // Le pasamos la mesa
                if ((typeof mesa.posicionXAnterior !== 'undefined') && (mesa.posicionXAnterior !== null)) {

                    // Si existe esta propiedad es que está colocada en la disposición BASE original
                    mesa.pos_x = mesa.posicionXAnterior;
                    mesa.pos_y = mesa.posicionYAnterior;
                }
                // HAY QUE REASIGNAR SUS COORDENADAS Y MANDARLA A PINTARSE
                recoloca(mesa);
            });

        } else {
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
        $('#sala').data('sala').removeMesa(mesa.data('mesa'));
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

});

var dialog = $('<div />').attr('id', 'dialogDistribucion');

let plantillaDisp = `
<article>
    <div class="row">
        <form>
            <div class="row m-2">
                <div class="col-12 col-md-6">
                    <label>Nombre
                    </label>
                </div>
                <div class="col-12 col-md-6">
                    <input type="text" name="nombre" required>
                </div>
            </div>
            <div class="row m-2">
                <div class="col-12 col-md-6">
                    <label>Fecha
                    </label>
                </div>
                <div class="col-12 col-md-6">
                    <input type="text" id="datePicker" name="fecha" required autocomplete="off">
                </div>
            </div>
            <div class="row m-2">
                <div class="col-12">
                    <input type="submit" class="btn btn-outline-primary" value="Crear distrib">
                </div>
            </div>
        </form>
    </div>
</article>
`;

var JPlantillaDisp = $(plantillaDisp);

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

/**
 * 
 * @returns una función que busca id=datePicker para convertirlo en un datePicker de JQuery UI
 */
function creaDatePicker() {
    return function () {
        // Buscamos #datePicker 
        ConvierteDatePicker();
    }
}

function recoloca(mesa) {
    // El DIV ya está creado
    let obj = mesa.data('mesa');
    var top = parseInt(obj.pos_y);
    var left = parseInt(obj.pos_x);

    if (top >= 0 && left >= 0) {
        // Tiene posición => Se coloca de manera absoluta en la sala
        appendSala($('#sala').data('sala'), mesa);
    } else {
        // No está colocado => Al almacén
        almacena(mesa);
    }

    console.log("Recolocada " + obj.id);
}

function appendSala(sala, mesa) {
    let objmesa;
    if (mesa.data('mesa')) {
        objmesa = mesa.data('mesa');
    } else {
        objmesa = mesa[0];

        mesa.posicionYAnterior = parseInt(mesa.pos_y);
        mesa.posicionXAnterior = parseInt(mesa.pos_x);
    }

    // Actualizamos su posición guardada en el objeto
    if (mesa.top == undefined) {
        mesa.top = (parseInt(objmesa.pos_y));
        mesa.left = (parseInt(objmesa.pos_x));
    }

    sala
        .addMesa(mesa);
    // Cambiamos su estilo para poder visualizarla
    mesa.css({
        position: "absolute",
        top: mesa.top + "px",
        left: mesa.left + "px",
        width: objmesa.ancho + "px",
        height: objmesa.largo + "px"
    })
        /* ELIMINAMOS EL TEXTO INFORMATIVO */
        .children('p').remove()
        ;
}

/**
     * Almacena una mesa sin cambiar su posición en la base de datos
     * @param {*} mesa 
     */
function almacena(mesa) {
    let obj = mesa.data('mesa');

    /* CREAMOS EL TEXTO CON SU TAMAÑO */
    var textTamanio = creaTextTamanio(obj);
    /* LE DAMOS EL MISMO ESTILO A TODAS LAS MESAS E INDICAMOS SU TAMAÑO */
    mesa.css({
        position: "relative",
        width: '100px',
        height: '100px',
        top: "0",
        left: "0",
    });

    if (!(mesa.children('p').length > 0)) {
        // No tiene la etiqueta
        mesa.append(textTamanio);
    }
    /* CAMBIAMOS LOS ATRIBUTOS DEL OBJETO */
    obj.pos_x = -1;
    obj.pos_y = -1;
    /* LO AÑADIMOS AL ALMACÉN */
    $('#almacen').append(mesa);
}