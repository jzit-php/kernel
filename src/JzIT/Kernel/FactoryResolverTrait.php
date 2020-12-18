<?php

declare(strict_types=1);

namespace JzIT\Kernel;

use JzIT\Kernel\Resolver\Factory\FactoryResolver;

trait FactoryResolverTrait
{
    /**
     * @var \JzIT\Kernel\AbstractFactory
     */
    private $factory;

    /**
     * @param string|null $className
     *
     * @return \JzIT\Kernel\AbstractFactory
     * @throws \JzIT\Kernel\Exception\FactoryNotFoundException
     */
    protected function getFactory(?string $className = null)
    {
        if ($className === null){
            $className = get_class($this);
        }

        if ($this->factory === null) {
            $this->factory = $this->resolveFactory($className);
        }

        return $this->factory;
    }

    /**
     * @param string $className
     *
     * @return \JzIT\Kernel\AbstractFactory
     * @throws \JzIT\Kernel\Exception\FactoryNotFoundException
     */
    private function resolveFactory(string $className): AbstractFactory
    {
        return $this->getFactoryResolver()->resolve($className);
    }

    /**
     * @return \JzIT\Kernel\Resolver\Factory\FactoryResolver
     */
    private function getFactoryResolver(): FactoryResolver
    {
        return new FactoryResolver($this->container);
    }
}
