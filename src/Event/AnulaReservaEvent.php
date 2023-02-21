<?php

namespace Product\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ProductCreateEvent extends Event
{
    public const NAME = 'product.created';

    // ....
}