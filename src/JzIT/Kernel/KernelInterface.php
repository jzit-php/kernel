<?php

declare(strict_types=1);

namespace JzIT\Kernel;

use Di\Container;

interface KernelInterface
{
    /**
     * @return \Di\Container
     */
    public function getContainer(): Container;
}
