<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CreaReservaEvent extends Event
{
    public const NAME = 'reserva.creada';

    // Cuando se crea una reserva sumamos puntos al usuario que la efectúa
    
}