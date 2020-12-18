<?php

declare(strict_types=1);

namespace JzIT\Kernel;

use JzIT\Config\ConfigInterface;

abstract class AbstractConfig
{
    /**
     * @var \JzIT\Config\ConfigInterface
     */
    protected $config;

    /**
     * AbstractConfig constructor.
     *
     * @param \JzIT\Config\ConfigInterface|null $appConfig
     */
    public function __construct(?ConfigInterface $appConfig)
    {
        $this->config = $appConfig;
    }

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->config->get($key, $default);
    }
}
