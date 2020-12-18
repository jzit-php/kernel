<?php

declare(strict_types=1);

namespace JzIT\Kernel;

use DI\Container;

interface FactoryInterface
{
    /**
     * @param \DI\Container $container
     *
     * @return \JzIT\Kernel\FactoryInterface
     */
    public function setContainer(Container $container): FactoryInterface;
}
