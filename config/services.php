<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $containerConfigurator): void {

    $containerConfigurator->import('parameters.php');

    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services->load('App\\', '../src/')
        ->exclude([
            '../src/DependencyInjection/',
            '../src/Entity/',
            '..src/Kernel.php',
        ])
    ;

};
