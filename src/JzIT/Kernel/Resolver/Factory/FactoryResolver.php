<?php

declare(strict_types=1);

namespace JzIT\Kernel\Resolver\Factory;

use DI\Container;
use JzIT\Kernel\AbstractFactory;
use JzIT\Kernel\Exception\FactoryNotFoundException;
use JzIT\Kernel\Exception\FactoryWrongInstanceException;
use JzIT\Kernel\Resolver\AbstractResolver;

class FactoryResolver extends AbstractResolver
{
    public const CLASS_NAME_PATTERN = '\\%1$s\\%2$s\\%2$s%3$s';

    public const KEY_PREFIX = 'Factory';

    /**
     * @param string $className
     *
     * @return \JzIT\Kernel\AbstractFactory|object
     *
     * @throws \JzIT\Kernel\Exception\FactoryNotFoundException
     * @throws \JzIT\Kernel\Exception\FactoryWrongInstanceException
     */
    public function resolve(string $className)
    {
        if ($this->canResolve($className)) {
            $factory = $this->getResolvedClassInstance();
            if ($factory instanceof AbstractFactory){
                $factory->setContainer($this->container);
                return $factory;
            }
            throw new FactoryWrongInstanceException(sprintf('%s has to be an instance of %s', $className, AbstractFactory::class));
        }

        throw new FactoryNotFoundException($className);
    }

    /**
     * @return string
     */
    public function getClassPattern()
    {
        return sprintf(
            self::CLASS_NAME_PATTERN,
            self::KEY_NAMESPACE,
            self::KEY_BUNDLE,
            self::KEY_PREFIX
        );
    }

    /**
     * @return \DI\Container
     */
    protected function getContainer(): Container{
        return $this->container;
    }
}
