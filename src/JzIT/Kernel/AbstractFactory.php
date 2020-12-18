<?php

declare(strict_types=1);

namespace JzIT\Kernel;

use DI\Container;

abstract class AbstractFactory
{
    use ConfigResolverTrait;

    /**
     * @var \DI\Container
     */
    protected $container;

    /**
     * @param \DI\Container $container
     *
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }
}
