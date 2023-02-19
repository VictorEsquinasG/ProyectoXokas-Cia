
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

class Tramo {
    constructor(id,inicio,fin) {
        this.id = id;
        this.horaInicio = inicio;
        this.horaFin = fin;
    }
}