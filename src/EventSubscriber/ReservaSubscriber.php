<?php
// src/Event/ProductEventSubscriber.php

// namespace Product\Event;

// use Product\Event\ProductCreateEvent;
// use Symfony\Component\EventDispatcher\EventSubscriberInterface;
// use Symfony\Component\HttpKernel\Event\ResponseEvent;
// use Symfony\Component\HttpKernel\KernelEvents;

// class ProductEventSubscriber implements EventSubscriberInterface
// {
//     // Returns an array indexed by event name and value by method name to call
//     public static function getSubscribedEvents()
//     {
//         return [
//             ProductCreateEvent::NAME => 'onProductCreation',
//             //hook multiple functions with the events with priority for sequence of function calls
//             ProductUpdateEvent::NAME => [
//                 ['onProductCreation', 1],
//                 ['onProductUpdation', 2],
//             ],
//             ProductDeleteEvent::NAME => 'onProductDeletion',
//             KernelEvents::RESPONSE => 'onKernelResponse',
//         ];
//     }

//     public function onProductCreation(ProductCreateEvent $event)
//     {
//         // write code to execute on product creation event
//     }

//     public function onProductUpdation(ProductUpdateEvent $event)
//     {
//         // write code to execute on product updation event
//     }

//     public function onProductDeletion(ProductDeleteEvent $event)
//     {
//         // write code to execute on product deletion event
//     }

//     public function onKernelResponse(ResponseEvent  $event)
//     {
//         // write code to execute on in-built Kernel Response event
//     }
// }