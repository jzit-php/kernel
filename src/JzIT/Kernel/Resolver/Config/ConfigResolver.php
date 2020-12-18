<?php

declare(strict_types=1);

namespace JzIT\Kernel\Resolver\Config;

use DI\Container;
use JzIT\Config\ConfigInterface;
use JzIT\Kernel\AbstractConfig;
use JzIT\Kernel\Exception\FactoryNotFoundException;
use JzIT\Kernel\Exception\FactoryWrongInstanceException;
use JzIT\Kernel\Resolver\AbstractResolver;

class ConfigResolver extends AbstractResolver
{
    public const CLASS_NAME_PATTERN = '\\%1$s\\%2$s\\%2$s%3$s';

    public const KEY_PREFIX = 'Config';

    /**
     * @param string $className
     *
     * @return \JzIT\Kernel\AbstractConfig|object
     *
     * @throws \JzIT\Kernel\Exception\FactoryNotFoundException
     * @throws \JzIT\Kernel\Exception\FactoryWrongInstanceException
     */
    public function resolve(string $className)
    {
        if ($this->canResolve($className)) {
            $config = $this->getInstance($this->container->get('config'));
            if ($config instanceof AbstractConfig){
                return $config;
            }
            throw new FactoryWrongInstanceException(sprintf('%s has to be an instance of %s', $className, AbstractConfig::class));
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
     * @param \JzIT\Config\ConfigInterface|null $configData
     *
     * @return mixed
     */
    protected function getInstance(?ConfigInterface $configData)
    {
        return new $this->resolvedClassName($configData);
    }
}
