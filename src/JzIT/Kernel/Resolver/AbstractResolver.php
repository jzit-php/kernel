<?php

declare(strict_types=1);

namespace JzIT\Kernel\Resolver;

use DI\Container;

abstract class AbstractResolver
{
    public const KEY_NAMESPACE = '|namespace|';
    public const KEY_BUNDLE = '|bundle|';

    public const POS_NAMESPACE = 0;
    public const POS_BUNDLE = 1;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $bundle;

    /**
     * @var string
     */
    protected $resolvedClassName;

    /**
     * @var \DI\Container
     */
    protected $container;

    /**
     * AbstractResolver constructor.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    abstract protected function getClassPattern();

    /**
     * @param string $className
     *
     * @return bool
     */
    public function canResolve(string $className)
    {
        $this->analyze($className);
        if ($this->checkIfClassExists()) {
            return true;
        }

        return false;
    }

    /**
     * @param string $className
     *
     * @return void
     */
    protected function analyze(string $className): void
    {
        $parts = explode('\\', $className);

        if (count($parts) === 1) {
            $parts = preg_split('/(?=[A-Z])/', $parts[0]);
            //ToDo try to find factory
        }
        $this->resolveClassName($parts);
    }

    /**
     * @param array $parts
     *
     * @return void
     */
    protected function setNamespace(array $parts): void
    {
        if (array_key_exists(self::POS_NAMESPACE, $parts)) {
            $this->namespace = $parts[self::POS_NAMESPACE];
        }
    }

    /**
     * @param array $parts
     *
     * @return void
     */
    protected function setBundle(array $parts): void
    {
        if (array_key_exists(self::POS_BUNDLE, $parts)) {
            $this->bundle = $parts[self::POS_BUNDLE];
        }
    }

    /**
     * @param array $parts
     *
     * @return void
     */
    protected function resolveClassName(array $parts): void
    {
        $this->setBundle($parts);
        $this->setNamespace($parts);
        if ($this->bundle !== null && $this->namespace !== null) {
            $resolvedClassName = $this->getClassPattern();
            $resolvedClassName = str_replace(self::KEY_NAMESPACE, $this->namespace, $resolvedClassName);
            $resolvedClassName = str_replace(self::KEY_BUNDLE, $this->bundle, $resolvedClassName);
            $this->resolvedClassName = $resolvedClassName;
        }
    }

    protected function checkIfClassExists(): bool
    {
        if ($this->resolvedClassName === null) {
            return false;
        }
        return class_exists($this->resolvedClassName);
    }

    /**
     * @return object
     */
    protected function getResolvedClassInstance()
    {
        return new $this->resolvedClassName();
    }
}
