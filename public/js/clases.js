
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
        let mesas = this.mesas;
        // Lo borramos del array mesas
        mesas.forEach((meson,i) => {
            
            let id_actual = meson.data('mesa').id;
            if (id_actual == id) {
                this.mesas.splice(i,1);
            }
        });
    }

    removeMesas = function () {  
        this.mesas = [];

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

class Tramo {
    constructor(id,inicio,fin) {
        this.id = id;
        this.horaInicio = inicio;
        this.horaFin = fin;
    }
}

class Reserva {
    constructor(id, fecha, asiste, fechaCancelacion, user, juego, mesa, tramo) {
        this.id = id;
        this.fecha = fecha;
        this.asiste = asiste;
        this.fechaCancelacion = fechaCancelacion;
        this.usuario = user;
        this.juego = juego;
        this.mesa = mesa;
        this.tramo = tramo;
        
    }
}


class Evento {
    
    constructor(id, fecha, tramo, nombre, juegos, usuarios, max_asistentes) {
        this.id = id;
        this.nombre = nombre;
        this.fecha = fecha;
        this.tramo = tramo;
        this.juegos = juegos;
        this.usuarios = usuarios;
        this.max_asistentes = max_asistentes;
        
    }
}