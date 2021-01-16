<?php

declare(strict_types=1);

namespace JzIT\Kernel;

use ArrayObject;
use Di\Container;
use DI\ContainerBuilder;
use JzIT\Container\ServiceProvider\AbstractServiceProvider;
use JzIT\Container\ServiceProvider\ServiceProviderInterface;
use JzIT\Kernel\Exception\EnvVarNotSetException;
use JzIT\Kernel\Exception\InvalidServiceContainerException;

class Kernel implements KernelInterface
{
    protected const SERVICE_PROVIDERS_FILE_NAME = 'service_providers.php';
    protected const APP_DIRECTORY_NAME = 'app';
    protected const SRC_DIRECTORY_NAME = 'src';

    /**
     * @var \Di\Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var string
     */
    protected $appDir;

    /**
     * @var string
     */
    protected $srcDir;

    /**
     * @var string
     */
    protected $environment;

    /**
     * Kernel constructor.
     * @param string $rootDir
     * @param bool $useAutowiring
     * @param bool $useAnnotations
     *
     * @throws \JzIT\Kernel\Exception\EnvVarNotSetException
     * @throws \JzIT\Kernel\Exception\InvalidServiceContainerException
     */
    public function __construct(string $rootDir, bool $useAutowiring = false, bool $useAnnotations = false)
    {
        $this->rootDir = \rtrim($rootDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->appDir = $this->rootDir . static::APP_DIRECTORY_NAME . DIRECTORY_SEPARATOR;
        $this->srcDir = $this->rootDir . static::SRC_DIRECTORY_NAME . DIRECTORY_SEPARATOR;

        $this->environment = $this->setEnvironment();
        $this->container = $this->setupContainer($this->createContainer($useAutowiring, $useAnnotations));
    }

    /**
     * @return \Di\Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @return string
     *
     * @throws \JzIT\Kernel\Exception\EnvVarNotSetException
     */
    protected function setEnvironment(): string
    {
        $environment = \getenv('APPLICATION_ENV', true) ?: \getenv('APPLICATION_ENV');

        if (!$environment) {
            throw new EnvVarNotSetException('Environment variable "APPLICATION_ENV" is not set.');
        }

        return $environment;
    }

    /**
     * @param bool $useAutowiring
     * @param bool $useAnnotations
     *
     * @return \Di\Container
     *
     * @throws \Exception
     */
    protected function createContainer(bool $useAutowiring, bool $useAnnotations): Container
    {
        return (new ContainerBuilder())
            ->useAutowiring($useAutowiring)
            ->useAnnotations($useAnnotations)
            ->build();
    }

    /**
     * @param \Di\Container $container
     *
     * @return \Di\Container
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \JzIT\Kernel\Exception\InvalidServiceContainerException
     */
    protected function setupContainer(Container $container): Container
    {
        $container->set('app_dir', $this->appDir);
        $container->set('src_dir', $this->srcDir);
        $container->set('environment', $this->environment);

        foreach ($this->registerServiceProviders() as $serviceProvider) {
            if (!$serviceProvider instanceof AbstractServiceProvider) {
                throw new InvalidServiceContainerException(sprintf('%s needs to extend %s', get_class($serviceProvider), AbstractServiceProvider::class));
            }
            try {
                $serviceProvider->setContainer($container);
            }catch (\Exception $exception){}
            $serviceProvider->register($container);
        }

        return $container;
    }

    /**
     * @return \ArrayObject|\JzIT\Container\ServiceProvider\ServiceProviderInterface[]
     */
    protected function registerServiceProviders(): ArrayObject
    {
        $serviceProviders = new ArrayObject();
        $pathToServiceProvidersFile = $this->appDir . static::SERVICE_PROVIDERS_FILE_NAME;

        if (\file_exists($pathToServiceProvidersFile)) {
            include $pathToServiceProvidersFile;
        }

        return $serviceProviders;
    }

}
