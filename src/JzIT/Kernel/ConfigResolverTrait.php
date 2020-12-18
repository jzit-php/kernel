<?php

declare(strict_types=1);

namespace JzIT\Kernel;

use JzIT\Config\ConfigInterface;
use JzIT\Kernel\Resolver\Config\ConfigResolver;

trait ConfigResolverTrait
{
    /**
     * @var \JzIT\Kernel\AbstractConfig
     */
    private $config;

    /**
     * @param string|null $className
     *
     * @return \JzIT\Kernel\AbstractConfig
     * @throws \JzIT\Kernel\Exception\ConfigNotFoundException
     */
    protected function getConfig(?string $className = null)
    {
        if ($className === null){
            $className = get_class($this);
        }

        if ($this->config === null) {
            $this->config = $this->resolveConfig($className, $this->container->get('config'));
        }

        return $this->config;
    }

    /**
     * @param string $className
     *
     * @return \JzIT\Kernel\AbstractConfig
     *
     * @throws \JzIT\Kernel\Exception\FactoryNotFoundException
     * @throws \JzIT\Kernel\Exception\FactoryWrongInstanceException
     */
    private function resolveConfig(string $className): AbstractConfig
    {
        return $this->getConfigResolver()->resolve($className);
    }

    /**
     * @return \JzIT\Kernel\Resolver\Config\ConfigResolver
     */
    private function getConfigResolver(): ConfigResolver
    {
        return new ConfigResolver($this->container);
    }
}
